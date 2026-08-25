<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\FetchJobsJob;
use App\Jobs\ParseCvJob;
use App\Jobs\UpgradeCvJob;
use App\Models\CvProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CvController extends Controller
{
    public function index()
    {
        $cvProfile = Auth::user()->cvProfile;
        return view('user.cv.index', compact('cvProfile'));
    }

    public function upload(Request $request)
    {
        $request->validate(['cv_file' => 'required|file|mimes:pdf|max:5120']);
        $path = $request->file('cv_file')->store('cvs', 'local');

        $cvProfile = CvProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'file_path' => $path,
                'raw_text' => null,
                'parsed_data' => null,
                'parsed_at' => null,
                'status' => 'pending',
            ]
        );

        ParseCvJob::dispatch($cvProfile);

        return redirect()->route('user.cv.index')
            ->with('success', 'CV uploaded successfully! Processing will begin shortly.');
    }

    public function manual(Request $request)
    {
        $request->validate([
            'skills' => 'required|string',
            'experience_years' => 'required|integer|min:0|max:50',
            'target_niche' => 'required|string|max:255',
            'preferred_location' => 'nullable|string|max:255',
            'education_level' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:1000',
        ]);

        $skills = array_values(array_filter(array_map('trim', explode(',', $request->skills))));

        $rawText = "Skills: " . implode(', ', $skills) . "\n"
            . "Experience: {$request->experience_years} years\n"
            . "Target Role: {$request->target_niche}\n"
            . "Location: {$request->preferred_location}\n"
            . "Education: {$request->education_level}\n"
            . "Summary: {$request->summary}";

        $parsedData = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => null,
            'skills' => $skills,
            'experience_years' => (int) $request->experience_years,
            'target_niche' => $request->target_niche,
            'preferred_location' => $request->preferred_location,
            'education_level' => $request->education_level,
            'education_details' => [],
            'work_experience' => [],
            'top_keywords' => array_slice($skills, 0, 5),
            'summary' => $request->summary ?? 'Professional with ' . $request->experience_years . ' years of experience.',
        ];

        $cvProfile = CvProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'file_path' => null,
                'raw_text' => $rawText,
                'parsed_data' => $parsedData,
                'parsed_at' => now(),
                'status' => 'parsed',
            ]
        );

        FetchJobsJob::dispatch($cvProfile);

        return redirect()->route('user.cv.index')
            ->with('success', 'Profile saved! Fetching matching jobs...');
    }

    public function status()
    {
        $cvProfile = Auth::user()->cvProfile;
        if (!$cvProfile) {
            return response()->json(['status' => 'none']);
        }
        return response()->json([
            'status' => $cvProfile->status,
            'jobs_count' => Auth::user()->jobMatches()->count(),
            'gaps_count' => Auth::user()->skillGaps()->count(),
            'courses_count' => Auth::user()->recommendedCourses()->count(),
        ]);
    }

    public function refresh()
    {
        $cvProfile = Auth::user()->cvProfile;
        if (!$cvProfile || !$cvProfile->parsed_data) {
            return redirect()->route('user.cv.index')
                ->with('error', 'Please upload a CV or fill your profile first.');
        }
        $cvProfile->update(['status' => 'parsing']);
        FetchJobsJob::dispatch($cvProfile);
        return redirect()->route('user.cv.index')
            ->with('success', 'Refreshing your job matches and recommendations...');
    }

    /**
     * Dedicated sidebar page for the CV upgrade feature.
     */
    public function upgradePage()
    {
        $cvProfile = Auth::user()->cvProfile;
        return view('user.cv.upgrade', compact('cvProfile'));
    }

    /**
     * Trigger AI rewrite of the user's CV.
     */
    public function upgrade()
    {
        $cv = Auth::user()->cvProfile;

        if (! $cv || ! $cv->isParsed()) {
            return redirect()->route('user.cv.index')
                ->with('error', 'Please upload and parse your CV first.');
        }

        if ($cv->isUpgrading()) {
            return redirect()->route('user.cv.index')
                ->with('info', 'Your upgrade is already being generated.');
        }

        $cv->update([
            'upgrade_status' => 'generating',
            'upgraded_at'    => null,
        ]);

        UpgradeCvJob::dispatch($cv);

        return redirect()->route('user.cv.index')
            ->with('success', 'Crafting your upgraded CV — this usually takes 10–20 seconds.');
    }

    /**
     * AJAX status endpoint — frontend polls this every 3s while generating.
     */
    public function upgradeStatus()
    {
        $cv = Auth::user()->cvProfile;

        return response()->json([
            'status' => $cv?->upgrade_status ?? 'none',
        ]);
    }

    /**
     * Stream the upgraded CV as a PDF download.
     */
    public function download()
    {
        $cv = Auth::user()->cvProfile;
        abort_unless($cv && $cv->upgradeReady(), 404);

        $pdf = Pdf::loadView('user.cv.pdf', [
            'data'   => $cv->upgraded_data,
            'parsed' => $cv->parsed_data ?? [],
            'user'   => Auth::user(),
        ])->setPaper('a4');

        $slug = Str::slug(Auth::user()->name) ?: 'caria';
        $filename = "{$slug}-cv-" . now()->format('Y-m-d') . ".pdf";

        return $pdf->download($filename);
    }
}
