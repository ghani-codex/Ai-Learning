<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $q     = trim((string) $request->query('q', ''));
        $skill = trim((string) $request->query('skill', ''));
        $sort  = $request->query('sort', 'newest');

        $query = Auth::user()->recommendedCourses();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('course_title', 'like', "%{$q}%")
                  ->orWhere('channel_name', 'like', "%{$q}%");
            });
        }

        if ($skill !== '') {
            $query->where('skill_target', $skill);
        }

        // Decide grouped vs flat
        $grouped = ($skill !== '' || $sort === 'skill');

        // List of all skills the user has courses for (for the dropdown)
        $availableSkills = Auth::user()->recommendedCourses()
            ->select('skill_target')
            ->distinct()
            ->orderBy('skill_target')
            ->pluck('skill_target');

        $totalCount = Auth::user()->recommendedCourses()->count();
        $cvProfile = Auth::user()->cvProfile;

        if ($grouped) {
            $courses = (clone $query)
                ->orderBy('skill_target')
                ->orderByDesc('fetched_at')
                ->get()
                ->groupBy('skill_target');

            return view('user.courses.index', compact(
                'courses', 'cvProfile', 'q', 'skill', 'sort', 'grouped', 'availableSkills', 'totalCount'
            ));
        }

        // Flat paginated mode
        $query = match ($sort) {
            'alpha'  => $query->orderBy('course_title'),
            default  => $query->orderByDesc('fetched_at'),
        };

        $courses = $query->paginate(12)->withQueryString();

        return view('user.courses.index', compact(
            'courses', 'cvProfile', 'q', 'skill', 'sort', 'grouped', 'availableSkills', 'totalCount'
        ));
    }
}
