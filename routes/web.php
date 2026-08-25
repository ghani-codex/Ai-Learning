<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\CvController;
use App\Http\Controllers\User\JobController;
use App\Http\Controllers\User\SkillGapController;
use App\Http\Controllers\User\CourseController;
use App\Http\Controllers\User\SubscriptionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes (Public)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Auth Redirect (role-based)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| User Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::prefix('user')
    ->name('user.')
    ->middleware(['auth', 'verified', 'role:user,admin'])
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        Route::get('/cv', [CvController::class, 'index'])->name('cv.index');
        Route::post('/cv/upload', [CvController::class, 'upload'])->name('cv.upload');
        Route::post('/cv/manual', [CvController::class, 'manual'])->name('cv.manual');
        Route::get('/cv/status', [CvController::class, 'status'])->name('cv.status');
        Route::post('/cv/refresh', [CvController::class, 'refresh'])->name('cv.refresh');

        // CV upgrade (dedicated sidebar page + AI rewrite + downloadable PDF)
        Route::get('/cv-upgrade', [CvController::class, 'upgradePage'])->name('cv-upgrade.index');
        Route::post('/cv/upgrade', [CvController::class, 'upgrade'])
            ->middleware('throttle:5,1')
            ->name('cv.upgrade');
        Route::get('/cv/upgrade/status', [CvController::class, 'upgradeStatus'])->name('cv.upgrade.status');
        Route::get('/cv/download', [CvController::class, 'download'])->name('cv.download');

        Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
        Route::post('/jobs/refresh', [JobController::class, 'refresh'])->name('jobs.refresh');

        Route::get('/skill-gaps', [SkillGapController::class, 'index'])->name('skill-gaps.index');
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

        Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    });

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // User CRUD
        Route::get('/users',              [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit',  [AdminUserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}',     [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',    [AdminUserController::class, 'destroy'])->name('users.destroy');
    });

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
