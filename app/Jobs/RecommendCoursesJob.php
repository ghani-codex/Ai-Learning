<?php

namespace App\Jobs;

use App\Models\CvProfile;
use App\Models\RecommendedCourse;
use App\Models\SkillGap;
use App\Services\YouTubeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RecommendCoursesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 120;

    public function __construct(public CvProfile $cvProfile) {}

    public function handle(YouTubeService $youTubeService): void
    {
        try {
            $userId = $this->cvProfile->user_id;

            $skillGaps = SkillGap::where('user_id', $userId)
                ->orderByDesc('importance')
                ->take(7)
                ->get();

            RecommendedCourse::where('user_id', $userId)->delete();

            foreach ($skillGaps as $gap) {
                $courses = $youTubeService->searchCourses($gap->skill_name, 3);

                foreach ($courses as $course) {
                    RecommendedCourse::create([
                        'user_id' => $userId,
                        'course_title' => $course['title'],
                        'youtube_url' => $course['youtube_url'],
                        'thumbnail_url' => $course['thumbnail_url'] ?? null,
                        'channel_name' => $course['channel_name'] ?? null,
                        'skill_target' => $gap->skill_name,
                        'fetched_at' => now(),
                    ]);
                }
            }

            $this->cvProfile->update(['status' => 'parsed']);
        } catch (\Exception $e) {
            Log::error('RecommendCoursesJob failed', ['cv_profile_id' => $this->cvProfile->id, 'error' => $e->getMessage()]);
            $this->cvProfile->update(['status' => 'parsed']);
        }
    }
}
