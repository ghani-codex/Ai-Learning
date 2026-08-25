<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CvProfile;
use App\Models\JobMatch;
use App\Models\RecommendedCourse;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'     => User::count(),
            'verified_users'  => User::whereNotNull('email_verified_at')->count(),
            'new_this_month'  => User::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)
                                     ->count(),
            'cvs_uploaded'    => CvProfile::count(),
            'cvs_parsed'      => CvProfile::where('status', 'parsed')->count(),
            'jobs_matched'    => JobMatch::count(),
            'courses_curated' => RecommendedCourse::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
