<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans       = config('plans.plans', []);
        $comparison  = config('plans.comparison', []);
        $faq         = config('plans.faq', []);

        // Every signed-in user is treated as Free for v1 (no DB column yet).
        $currentPlan = 'free';

        return view('user.subscription.index', compact(
            'plans', 'comparison', 'faq', 'currentPlan'
        ));
    }
}
