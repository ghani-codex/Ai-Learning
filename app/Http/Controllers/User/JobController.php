<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\FetchJobsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->query('q', ''));
        $match    = $request->query('match', 'all');
        $type     = $request->query('type', 'all');
        $location = trim((string) $request->query('location', ''));
        $sort     = $request->query('sort', 'match');

        $query = Auth::user()->jobMatches();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('job_title', 'like', "%{$q}%")
                  ->orWhere('company_name', 'like', "%{$q}%");
            });
        }

        if ($match === 'high') {
            $query->where('match_score', '>=', 70);
        } elseif ($match === 'medium') {
            $query->whereBetween('match_score', [40, 69]);
        } elseif ($match === 'low') {
            $query->where('match_score', '<', 40);
        }

        if ($type !== 'all') {
            $map = [
                'fulltime'   => 'FULLTIME',
                'parttime'   => 'PARTTIME',
                'contractor' => 'CONTRACTOR',
                'intern'     => 'INTERN',
            ];
            if (isset($map[$type])) {
                $query->where('employment_type', $map[$type]);
            }
        }

        if ($location !== '') {
            $query->where('location', 'like', "%{$location}%");
        }

        $query = match ($sort) {
            'newest'  => $query->orderByDesc('fetched_at'),
            'oldest'  => $query->orderBy('fetched_at'),
            'company' => $query->orderBy('company_name'),
            default   => $query->orderByDesc('match_score'),
        };

        $jobs = $query->paginate(12)->withQueryString();
        $totalCount = Auth::user()->jobMatches()->count();
        $cvProfile = Auth::user()->cvProfile;

        return view('user.jobs.index', compact(
            'jobs', 'cvProfile', 'q', 'match', 'type', 'location', 'sort', 'totalCount'
        ));
    }

    public function refresh()
    {
        $cvProfile = Auth::user()->cvProfile;

        if (! $cvProfile || ! $cvProfile->parsed_data) {
            return redirect()->route('user.jobs.index')
                ->with('error', 'Please upload a CV first to get job matches.');
        }

        FetchJobsJob::dispatch($cvProfile);

        return redirect()->route('user.jobs.index')
            ->with('success', 'Refreshing job matches...');
    }
}
