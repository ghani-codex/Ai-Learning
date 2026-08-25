<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $baseUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->baseUrl = config('services.gemini.base_url');
        $this->model = config('services.gemini.model');
    }

    public function parseCv(string $rawText): ?array
    {
        $systemPrompt = <<<'PROMPT'
You are an expert CV/resume parser. Analyze the following CV text and extract structured information.

Return ONLY valid JSON with exactly this structure (no markdown, no code fences, no explanation):

{
    "name": "Full name of the candidate",
    "email": "Email address if found, otherwise null",
    "phone": "Phone number if found, otherwise null",
    "skills": ["skill1", "skill2", "skill3"],
    "experience_years": 0,
    "target_niche": "The most likely job role/niche this person is targeting",
    "preferred_location": "Location if mentioned, otherwise null",
    "education_level": "highest education level",
    "education_details": [
        {"degree": "Degree name", "institution": "Institution name", "year": "Graduation year or null"}
    ],
    "work_experience": [
        {"title": "Job title", "company": "Company name", "duration": "Duration string"}
    ],
    "top_keywords": ["keyword1", "keyword2", "keyword3", "keyword4", "keyword5"],
    "summary": "A brief 2-3 sentence professional summary"
}

Rules:
- "skills" must be individual technical and soft skills. Normalize them (e.g., "JavaScript" not "javascript").
- "experience_years" must be an integer. Use 0 for fresh graduates.
- "target_niche" must be a specific role like "Frontend Developer", "Data Scientist".
- Extract ALL skills you can identify.
- If info is not available, use null for strings, empty arrays for arrays.
PROMPT;

        $models = [$this->model, 'gemini-2.0-flash'];

        foreach ($models as $model) {
            $url = "{$this->baseUrl}/models/{$model}:generateContent?key={$this->apiKey}";

            for ($attempt = 1; $attempt <= 3; $attempt++) {
                try {
                    $result = $this->callGemini($url, $systemPrompt, $rawText);
                    if ($result !== null) {
                        return $result;
                    }
                    if ($attempt < 3) {
                        Log::warning("Gemini {$model} attempt {$attempt} failed, retrying in " . ($attempt * 5) . "s");
                        sleep($attempt * 5);
                    }
                } catch (\Exception $e) {
                    Log::error("Gemini {$model} attempt {$attempt} exception", ['message' => $e->getMessage()]);
                    if ($attempt < 3) {
                        sleep($attempt * 5);
                    }
                }
            }
            Log::warning("All attempts failed for {$model}, trying next model...");
        }

        return null;
    }

    private function callGemini(string $url, string $systemPrompt, string $rawText): ?array
    {
        try {
            $response = Http::timeout(60)->post($url, [
                'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                'contents' => [['parts' => [['text' => "Parse this CV:\n\n" . $rawText]]]],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'topP' => 0.95,
                    'maxOutputTokens' => 4096,
                    'responseMimeType' => 'application/json',
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini API request failed', ['status' => $response->status(), 'body' => $response->body()]);
                return null;
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$text) {
                Log::error('Gemini API returned no text content', ['response' => $data]);
                return null;
            }

            $text = preg_replace('/^```json\s*/', '', trim($text));
            $text = preg_replace('/\s*```$/', '', $text);

            $parsed = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Gemini JSON', ['error' => json_last_error_msg(), 'raw_text' => $text]);
                return null;
            }

            return $parsed;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
