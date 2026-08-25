<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('components.dashboard-sidebar', function ($view) {
            $user = Auth::user();

            if ($user && $user->isAdmin()) {
                $menuItems = config('sidebar.admin');
            } else {
                $menuItems = config('sidebar.user');
            }

            $view->with('menuItems', $menuItems);
        });
    }
}
