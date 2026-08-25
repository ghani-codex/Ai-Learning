<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillGapController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->query('q', ''));
        $priority = $request->query('priority', 'all');
        $sort     = $request->query('sort', 'priority');

        $query = Auth::user()->skillGaps();

        if ($q !== '') {
            $query->where('skill_name', 'like', "%{$q}%");
        }

        if ($priority === 'high') {
            $query->where('importance', '>=', 8);
        } elseif ($priority === 'medium') {
            $query->whereBetween('importance', [5, 7]);
        } elseif ($priority === 'low') {
            $query->where('importance', '<', 5);
        }

        $query = match ($sort) {
            'alpha'      => $query->orderBy('skill_name'),
            'priority-asc' => $query->orderBy('importance'),
            default      => $query->orderByDesc('importance'),
        };

        $skillGaps = $query->paginate(12)->withQueryString();
        $totalCount = Auth::user()->skillGaps()->count();
        $cvProfile = Auth::user()->cvProfile;

        return view('user.skill-gaps.index', compact(
            'skillGaps', 'cvProfile', 'q', 'priority', 'sort', 'totalCount'
        ));
    }
}
