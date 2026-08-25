<?php

namespace App\Services;

use App\Models\CvProfile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CvUpgradeService
{
    private string $apiKey;
    private string $baseUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey  = config('services.gemini.api_key');
        $this->baseUrl = config('services.gemini.base_url');
        $this->model   = config('services.gemini.model');
    }

    /**
     * Rewrite the candidate's CV content using their parsed data + top job-market signal.
     *
     * @param  CvProfile  $profile
     * @param  Collection $topJobs   Up to 5 highest-scored JobMatch rows for context.
     * @return array|null            Upgraded CV data, or null on failure.
     */
    public function upgrade(CvProfile $profile, Collection $topJobs): ?array
    {
        $parsed = $profile->parsed_data ?? [];
        $targetRole = $parsed['target_niche'] ?? 'their target role';

        $jobSignals = $topJobs->map(function ($j) {
            $title = $j->job_title ?? '';
            $desc  = mb_substr((string) ($j->job_description ?? ''), 0, 400);
            return "- {$title}: {$desc}";
        })->implode("\n");

        $systemPrompt = <<<'PROMPT'
You are a senior career coach and ATS-optimization specialist. Rewrite a
candidate's CV content to maximize their chances of passing applicant
tracking systems and impressing hiring managers for a specific target role.
Never invent facts — only rewrite, reorder, and reframe what's already in
the candidate's data.

Return ONLY valid JSON in this exact shape:

{
  "headline":      "1-line professional headline",
  "summary":       "3-4 sentence achievement-focused summary aligned to TARGET ROLE",
  "skills":        ["skill1", "skill2", "..."],
  "ats_keywords":  ["keyword1", "keyword2", "..."],
  "work_experience": [
    {
      "title":    "as given",
      "company":  "as given",
      "duration": "as given",
      "bullets":  ["Achievement-focused bullet starting with strong action verb...", "..."]
    }
  ]
}

Rules:
- Never invent jobs, companies, dates, degrees, or skills the candidate doesn't have.
- Bullets must start with strong action verbs (Built, Led, Reduced, Architected, Shipped, Scaled, Drove, Delivered).
- Include numbers and quantified results wherever the original CV implies them.
- Weave ATS keywords naturally — don't keyword-stuff.
- Keep bullets to 1-2 lines each, 3-5 bullets per role.
- "skills" must be top 15 most relevant to TARGET ROLE first.
- "ats_keywords" should be 10-15 high-priority keywords.
- Output JSON only, no markdown, no code fences, no commentary.
PROMPT;

        $userMessage  = "TARGET ROLE: {$targetRole}\n\n";
        $userMessage .= "CANDIDATE DATA (JSON):\n" . json_encode($parsed, JSON_PRETTY_PRINT) . "\n\n";
        if (filled($jobSignals)) {
            $userMessage .= "JOB MARKET SIGNAL (top matched jobs' qualifications):\n{$jobSignals}\n\n";
        }
        $userMessage .= "Rewrite the candidate's CV now.";

        // Try primary model, then fallback (mirrors GeminiService::parseCv pattern)
        $models = [$this->model, 'gemini-2.0-flash'];

        foreach ($models as $model) {
            $url = "{$this->baseUrl}/models/{$model}:generateContent?key={$this->apiKey}";

            for ($attempt = 1; $attempt <= 3; $attempt++) {
                try {
                    $result = $this->callGemini($url, $systemPrompt, $userMessage);
                    if ($result !== null && $this->looksValid($result)) {
                        return $result;
                    }
                    if ($attempt < 3) {
                        Log::warning("CvUpgrade {$model} attempt {$attempt} failed, retrying in " . ($attempt * 5) . "s");
                        sleep($attempt * 5);
                    }
                } catch (\Exception $e) {
                    Log::error("CvUpgrade {$model} attempt {$attempt} exception", ['message' => $e->getMessage()]);
                    if ($attempt < 3) {
                        sleep($attempt * 5);
                    }
                }
            }
            Log::warning("CvUpgrade: all attempts failed for {$model}, trying next model...");
        }

        return null;
    }

    private function callGemini(string $url, string $systemPrompt, string $userMessage): ?array
    {
        $response = Http::timeout(60)->post($url, [
            'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
            'contents' => [['parts' => [['text' => $userMessage]]]],
            'generationConfig' => [
                'temperature'      => 0.4,
                'topP'             => 0.95,
                'maxOutputTokens'  => 4096,
                'responseMimeType' => 'application/json',
            ],
        ]);

        if ($response->failed()) {
            Log::error('CvUpgrade API request failed', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        }

        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (! $text) {
            Log::error('CvUpgrade API returned no text content', ['response' => $data]);
            return null;
        }

        $text = preg_replace('/^```json\s*/', '', trim($text));
        $text = preg_replace('/\s*```$/', '', $text);

        $parsed = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('CvUpgrade: failed to parse JSON', ['error' => json_last_error_msg(), 'raw' => $text]);
            return null;
        }

        return $parsed;
    }

    private function looksValid(array $upgraded): bool
    {
        return isset($upgraded['headline'])
            && isset($upgraded['summary'])
            && is_array($upgraded['skills'] ?? null)
            && is_array($upgraded['ats_keywords'] ?? null)
            && is_array($upgraded['work_experience'] ?? null);
    }
}
