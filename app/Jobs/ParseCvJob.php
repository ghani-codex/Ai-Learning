<?php

namespace App\Jobs;

use App\Models\CvProfile;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class ParseCvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    public int $backoff = 15;

    public function __construct(public CvProfile $cvProfile) {}

    public function handle(GeminiService $geminiService): void
    {
        $this->cvProfile->update(['status' => 'parsing']);

        try {
            $rawText = $this->cvProfile->raw_text;

            if (!$rawText && $this->cvProfile->file_path) {
                $filePath = Storage::disk('local')->path($this->cvProfile->file_path);
                $parser = new PdfParser();
                $pdf = $parser->parseFile($filePath);
                $rawText = $pdf->getText();
                $this->cvProfile->update(['raw_text' => $rawText]);
            }

            if (empty(trim($rawText ?? ''))) {
                Log::error('ParseCvJob: No text extracted', ['cv_profile_id' => $this->cvProfile->id]);
                $this->cvProfile->update(['status' => 'failed']);
                return;
            }

            $parsedData = $geminiService->parseCv($rawText);

            if (!$parsedData) {
                Log::error('ParseCvJob: Gemini returned null', ['cv_profile_id' => $this->cvProfile->id]);
                $this->cvProfile->update(['status' => 'failed']);
                return;
            }

            $this->cvProfile->update([
                'parsed_data' => $parsedData,
                'parsed_at' => now(),
                'status' => 'parsed',
            ]);

            FetchJobsJob::dispatch($this->cvProfile);
        } catch (\Exception $e) {
            Log::error('ParseCvJob failed', ['cv_profile_id' => $this->cvProfile->id, 'error' => $e->getMessage()]);
            $this->cvProfile->update(['status' => 'failed']);
            throw $e;
        }
    }
}
