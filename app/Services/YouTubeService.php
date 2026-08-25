<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
        $this->baseUrl = config('services.youtube.base_url');
    }

    public function searchCourses(string $skill, int $maxResults = 3): array
    {
        $cacheKey = 'youtube_courses_' . md5($skill . $maxResults);

        return Cache::remember($cacheKey, 86400, function () use ($skill, $maxResults) {
            try {
                $response = Http::timeout(15)->get("{$this->baseUrl}/search", [
                    'part' => 'snippet',
                    'q' => $skill . ' full course tutorial for beginners',
                    'type' => 'video',
                    'order' => 'relevance',
                    'maxResults' => $maxResults,
                    'videoDuration' => 'long',
                    'relevanceLanguage' => 'en',
                    'safeSearch' => 'strict',
                    'key' => $this->apiKey,
                ]);

                if ($response->failed()) {
                    Log::error('YouTube API request failed', ['status' => $response->status(), 'body' => $response->body()]);
                    return [];
                }

                $data = $response->json();
                $courses = [];

                foreach ($data['items'] ?? [] as $item) {
                    $courses[] = [
                        'title' => $item['snippet']['title'] ?? 'Unknown',
                        'youtube_url' => 'https://www.youtube.com/watch?v=' . ($item['id']['videoId'] ?? ''),
                        'thumbnail_url' => $item['snippet']['thumbnails']['high']['url']
                            ?? $item['snippet']['thumbnails']['medium']['url']
                            ?? $item['snippet']['thumbnails']['default']['url']
                            ?? null,
                        'channel_name' => $item['snippet']['channelTitle'] ?? null,
                    ];
                }

                return $courses;
            } catch (\Exception $e) {
                Log::error('YouTube API exception', ['message' => $e->getMessage()]);
                return [];
            }
        });
    }
}
