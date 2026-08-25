<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cvProfile = $user->cvProfile;
        $jobsCount = $user->jobMatches()->count();
        $gapsCount = $user->skillGaps()->count();
        $coursesCount = $user->recommendedCourses()->count();
        $recentJobs = $user->jobMatches()->orderByDesc('match_score')->take(5)->get();

        return view('user.dashboard', compact('cvProfile', 'jobsCount', 'gapsCount', 'coursesCount', 'recentJobs'));
    }
}
