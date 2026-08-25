<?php

namespace App\Jobs;

use App\Models\CvProfile;
use App\Models\JobMatch;
use App\Services\CvUpgradeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpgradeCvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    public int $backoff = 15;

    public function __construct(public CvProfile $cvProfile) {}

    public function handle(CvUpgradeService $service): void
    {
        try {
            $topJobs = JobMatch::where('user_id', $this->cvProfile->user_id)
                ->orderByDesc('match_score')
                ->take(5)
                ->get(['job_title', 'job_description']);

            $upgraded = $service->upgrade($this->cvProfile, $topJobs);

            if (! $upgraded) {
                Log::error('UpgradeCvJob: service returned null', ['cv_profile_id' => $this->cvProfile->id]);
                $this->cvProfile->update(['upgrade_status' => 'failed']);
                return;
            }

            $this->cvProfile->update([
                'upgraded_data'  => $upgraded,
                'upgrade_status' => 'ready',
                'upgraded_at'    => now(),
            ]);

        } catch (\Exception $e) {
            Log::error('UpgradeCvJob failed', [
                'cv_profile_id' => $this->cvProfile->id,
                'error' => $e->getMessage(),
            ]);
            $this->cvProfile->update(['upgrade_status' => 'failed']);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        $this->cvProfile->update(['upgrade_status' => 'failed']);
    }
}
