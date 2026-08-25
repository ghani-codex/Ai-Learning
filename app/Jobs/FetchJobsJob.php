<?php

namespace App\Jobs;

use App\Models\CvProfile;
use App\Models\JobMatch;
use App\Services\JSearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchJobsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 30;

    public function __construct(public CvProfile $cvProfile) {}

    public function handle(JSearchService $jSearchService): void
    {
        try {
            $parsedData = $this->cvProfile->parsed_data;
            $userId = $this->cvProfile->user_id;

            $targetNiche = $parsedData['target_niche'] ?? null;
            $location = $parsedData['preferred_location'] ?? null;
            $skills = $parsedData['skills'] ?? [];
            $keywords = $parsedData['top_keywords'] ?? [];

            if (!$targetNiche) {
                AnalyzeSkillGapsJob::dispatch($this->cvProfile);
                return;
            }

            $query = $targetNiche;
            if (!empty($keywords)) {
                $query .= ' ' . implode(' ', array_slice($keywords, 0, 2));
            }

            // Extract just the country from location (avoids "Rahim Yar Khan, Pakistan" returning 0 results)
            $simpleLocation = null;
            if ($location) {
                $parts = array_map('trim', explode(',', $location));
                $simpleLocation = end($parts) ?: null;
            }

            // Try: full query with country, then without location as fallback
            $jobs = $jSearchService->searchJobs($query, $simpleLocation, 1, 1);

            if (empty($jobs) && $simpleLocation) {
                \Illuminate\Support\Facades\Log::info('FetchJobsJob: No jobs with location, retrying without...', [
                    'query' => $query,
                    'location' => $simpleLocation,
                ]);
                $jobs = $jSearchService->searchJobs($query, null, 1, 1);
            }

            if (empty($jobs)) {
                \Illuminate\Support\Facades\Log::info('FetchJobsJob: No jobs with niche, trying simpler query...', [
                    'query' => $query,
                ]);
                // Try with just the target niche (no keywords appended)
                $jobs = $jSearchService->searchJobs($targetNiche, null, 1, 1);
            }

            JobMatch::where('user_id', $userId)->delete();

            foreach ($jobs as $job) {
                $matchResult = $jSearchService->calculateMatchScore($skills, $job);

                $salary = null;
                if (!empty($job['job_min_salary']) && !empty($job['job_max_salary'])) {
                    $currency = $job['job_salary_currency'] ?? 'USD';
                    $salary = $currency . ' ' . number_format($job['job_min_salary']) . ' - ' . number_format($job['job_max_salary']);
                } elseif (!empty($job['job_min_salary'])) {
                    $salary = ($job['job_salary_currency'] ?? 'USD') . ' ' . number_format($job['job_min_salary']) . '+';
                }

                $locationStr = implode(', ', array_filter([
                    $job['job_city'] ?? null,
                    $job['job_state'] ?? null,
                    $job['job_country'] ?? null,
                ]));

                JobMatch::create([
                    'user_id' => $userId,
                    'job_title' => $job['job_title'] ?? 'Unknown Title',
                    'company_name' => $job['employer_name'] ?? 'Unknown Company',
                    'location' => $locationStr ?: null,
                    'job_url' => $job['job_apply_link'] ?? $job['job_google_link'] ?? '#',
                    'salary' => $salary,
                    'employment_type' => $job['job_employment_type'] ?? null,
                    'job_description' => $job['job_description'] ?? null,
                    'match_score' => $matchResult['score'],
                    'skills_matched' => $matchResult['matched_skills'],
                    'fetched_at' => now(),
                ]);
            }

            AnalyzeSkillGapsJob::dispatch($this->cvProfile);
        } catch (\Exception $e) {
            Log::error('FetchJobsJob failed', ['cv_profile_id' => $this->cvProfile->id, 'error' => $e->getMessage()]);
            AnalyzeSkillGapsJob::dispatch($this->cvProfile);
        }
    }
}
