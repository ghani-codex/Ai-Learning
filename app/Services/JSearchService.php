<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JSearchService
{
    private string $apiKey;
    private string $host;
    private string $baseUrl;
    private int $cacheTtl;

    public function __construct()
    {
        $this->apiKey = config('services.jsearch.api_key');
        $this->host = config('services.jsearch.host');
        $this->baseUrl = config('services.jsearch.base_url');
        $this->cacheTtl = config('services.jsearch.cache_ttl', 86400);
    }

    public function searchJobs(string $query, ?string $location = null, int $page = 1, int $numPages = 1): array
    {
        $cacheKey = 'jsearch_' . md5($query . $location . $page);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($query, $location, $page, $numPages) {
            try {
                $params = [
                    'query' => $query . ($location ? ' in ' . $location : ''),
                    'page' => $page,
                    'num_pages' => $numPages,
                    'date_posted' => 'month',
                    'remote_jobs_only' => 'false',
                ];

                $response = Http::timeout(15)
                    ->withHeaders([
                        'X-RapidAPI-Key' => $this->apiKey,
                        'X-RapidAPI-Host' => $this->host,
                    ])
                    ->get("{$this->baseUrl}/search", $params);

                if ($response->failed()) {
                    Log::error('JSearch API request failed', ['status' => $response->status(), 'body' => $response->body()]);
                    return [];
                }

                return $response->json()['data'] ?? [];
            } catch (\Exception $e) {
                Log::error('JSearch API exception', ['message' => $e->getMessage()]);
                return [];
            }
        });
    }

    public function calculateMatchScore(array $userSkills, array $jobData): array
    {
        $normalizedUserSkills = array_map('strtolower', $userSkills);

        $jobText = strtolower(
            ($jobData['job_title'] ?? '') . ' ' .
            ($jobData['job_description'] ?? '') . ' ' .
            implode(' ', $jobData['job_highlights']['Qualifications'] ?? []) . ' ' .
            implode(' ', $jobData['job_highlights']['Responsibilities'] ?? [])
        );

        $matchedSkills = [];
        foreach ($normalizedUserSkills as $index => $skill) {
            if (str_contains($jobText, $skill)) {
                $matchedSkills[] = $userSkills[$index];
            }
        }

        $totalSkills = count($userSkills);
        if ($totalSkills === 0) {
            return ['score' => 0, 'matched_skills' => []];
        }

        $skillScore = (count($matchedSkills) / $totalSkills) * 70;

        $titleScore = 0;
        $jobTitle = strtolower($jobData['job_title'] ?? '');
        foreach ($normalizedUserSkills as $skill) {
            if (str_contains($jobTitle, $skill)) {
                $titleScore += 5;
            }
        }
        $titleScore = min($titleScore, 20);

        $qualBonus = !empty($jobData['job_highlights']['Qualifications'] ?? []) ? 10 : 5;

        $totalScore = min(100, (int) round($skillScore + $titleScore + $qualBonus));

        return ['score' => $totalScore, 'matched_skills' => $matchedSkills];
    }
}
