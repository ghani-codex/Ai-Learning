<?php

namespace App\Jobs;

use App\Models\CvProfile;
use App\Models\JobMatch;
use App\Models\SkillGap;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeSkillGapsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 30;

    private const NICHE_SKILLS = [
        'frontend developer' => ['html', 'css', 'javascript', 'typescript', 'react', 'vue.js', 'angular', 'tailwind css', 'sass', 'webpack', 'git', 'responsive design', 'rest api', 'jest', 'figma'],
        'backend developer' => ['php', 'python', 'java', 'node.js', 'sql', 'mysql', 'postgresql', 'rest api', 'graphql', 'docker', 'redis', 'git', 'linux', 'aws', 'ci/cd'],
        'full stack developer' => ['html', 'css', 'javascript', 'typescript', 'react', 'node.js', 'python', 'sql', 'mysql', 'mongodb', 'rest api', 'docker', 'git', 'aws', 'ci/cd'],
        'data scientist' => ['python', 'r', 'sql', 'pandas', 'numpy', 'scikit-learn', 'tensorflow', 'pytorch', 'tableau', 'power bi', 'statistics', 'machine learning', 'deep learning', 'nlp', 'jupyter'],
        'mobile developer' => ['react native', 'flutter', 'swift', 'kotlin', 'java', 'dart', 'firebase', 'rest api', 'git', 'ui/ux'],
        'devops engineer' => ['docker', 'kubernetes', 'aws', 'azure', 'terraform', 'ansible', 'jenkins', 'ci/cd', 'linux', 'bash', 'python', 'monitoring', 'prometheus', 'grafana', 'git'],
        'ui/ux designer' => ['figma', 'adobe xd', 'sketch', 'prototyping', 'wireframing', 'user research', 'usability testing', 'design systems', 'html', 'css', 'accessibility'],
        'project manager' => ['agile', 'scrum', 'jira', 'stakeholder management', 'risk management', 'budgeting', 'communication', 'leadership', 'ms project', 'confluence'],
        'data analyst' => ['sql', 'excel', 'python', 'power bi', 'tableau', 'statistics', 'data visualization', 'etl', 'google analytics', 'r'],
        'laravel developer' => ['php', 'laravel', 'mysql', 'html', 'css', 'javascript', 'vue.js', 'livewire', 'rest api', 'redis', 'docker', 'git', 'tailwind css', 'eloquent', 'phpunit'],
        'machine learning engineer' => ['python', 'tensorflow', 'pytorch', 'scikit-learn', 'mlops', 'docker', 'sql', 'feature engineering', 'model deployment', 'aws sagemaker', 'deep learning'],
    ];

    public function __construct(public CvProfile $cvProfile) {}

    public function handle(): void
    {
        try {
            $parsedData = $this->cvProfile->parsed_data;
            $userId = $this->cvProfile->user_id;
            $userSkills = array_map('strtolower', $parsedData['skills'] ?? []);
            $targetNiche = strtolower($parsedData['target_niche'] ?? '');

            $requiredSkills = $this->extractSkillsFromJobs($userId);
            $nicheSkills = $this->getNicheSkills($targetNiche);
            $requiredSkills = array_unique(array_merge($requiredSkills, $nicheSkills));

            $gaps = [];
            foreach ($requiredSkills as $requiredSkill) {
                $requiredLower = strtolower($requiredSkill);
                $found = false;
                foreach ($userSkills as $userSkill) {
                    if ($requiredLower === $userSkill || str_contains($userSkill, $requiredLower) || str_contains($requiredLower, $userSkill)) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $gaps[] = $requiredSkill;
                }
            }

            SkillGap::where('user_id', $userId)->delete();

            foreach ($gaps as $index => $gap) {
                $importance = in_array(strtolower($gap), $nicheSkills)
                    ? min(10, 10 - (int)($index / 2))
                    : max(3, 7 - (int)($index / 2));

                SkillGap::create([
                    'user_id' => $userId,
                    'skill_name' => ucfirst($gap),
                    'reason' => $this->generateReason($gap, $targetNiche),
                    'importance' => max(1, min(10, $importance)),
                ]);
            }

            RecommendCoursesJob::dispatch($this->cvProfile);
        } catch (\Exception $e) {
            Log::error('AnalyzeSkillGapsJob failed', ['cv_profile_id' => $this->cvProfile->id, 'error' => $e->getMessage()]);
            RecommendCoursesJob::dispatch($this->cvProfile);
        }
    }

    private function extractSkillsFromJobs(int $userId): array
    {
        $jobMatches = JobMatch::where('user_id', $userId)->get();
        $skills = [];
        $knownSkills = [
            'html', 'css', 'javascript', 'typescript', 'react', 'react.js', 'vue', 'vue.js',
            'angular', 'node.js', 'express', 'next.js', 'nuxt.js', 'svelte',
            'php', 'laravel', 'symfony', 'python', 'django', 'flask', 'fastapi',
            'java', 'spring boot', 'c#', '.net', 'ruby', 'rails', 'go', 'golang', 'rust',
            'sql', 'mysql', 'postgresql', 'mongodb', 'redis', 'elasticsearch',
            'docker', 'kubernetes', 'aws', 'azure', 'gcp', 'terraform', 'ansible',
            'git', 'github', 'gitlab', 'ci/cd', 'jenkins',
            'rest api', 'graphql', 'microservices',
            'tailwind', 'bootstrap', 'sass', 'webpack', 'vite',
            'jest', 'cypress', 'phpunit', 'pytest', 'selenium',
            'figma', 'sketch', 'adobe xd',
            'agile', 'scrum', 'jira',
            'machine learning', 'deep learning', 'tensorflow', 'pytorch',
            'pandas', 'numpy', 'scikit-learn', 'power bi', 'tableau',
            'linux', 'bash', 'nginx', 'apache',
        ];

        foreach ($jobMatches as $job) {
            if ($job->job_description) {
                $textLower = strtolower($job->job_description);
                foreach ($knownSkills as $skill) {
                    if (str_contains($textLower, $skill)) {
                        $skills[] = $skill;
                    }
                }
            }
        }

        return array_unique($skills);
    }

    private function getNicheSkills(string $niche): array
    {
        if (isset(self::NICHE_SKILLS[$niche])) {
            return self::NICHE_SKILLS[$niche];
        }
        foreach (self::NICHE_SKILLS as $nicheKey => $skills) {
            if (str_contains($niche, $nicheKey) || str_contains($nicheKey, $niche)) {
                return $skills;
            }
        }
        $nicheWords = explode(' ', $niche);
        foreach (self::NICHE_SKILLS as $nicheKey => $skills) {
            foreach ($nicheWords as $word) {
                if (strlen($word) > 3 && str_contains($nicheKey, $word)) {
                    return $skills;
                }
            }
        }
        return [];
    }

    private function generateReason(string $skill, string $niche): string
    {
        $templates = [
            '%s is commonly required for %s roles and was found in multiple job listings.',
            'Employers frequently ask for %s in %s positions. Adding this skill will improve your match rate.',
            '%s is a core competency for %s. Learning this will strengthen your profile significantly.',
        ];
        $template = $templates[array_rand($templates)];
        return sprintf($template, ucfirst($skill), ucfirst($niche));
    }
}
