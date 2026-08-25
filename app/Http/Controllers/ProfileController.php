<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();

        $dir = public_path('uploads/avatars');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Drop the previous file (if any) so we don't pile up orphans.
        if ($user->profile_image && file_exists($dir . DIRECTORY_SEPARATOR . $user->profile_image)) {
            @unlink($dir . DIRECTORY_SEPARATOR . $user->profile_image);
        }

        $file     = $request->file('profile_image');
        $filename = 'u' . $user->id . '_' . Str::random(12) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);

        $user->update(['profile_image' => $filename]);

        return Redirect::route('profile.edit')->with('success', 'Profile photo updated.');
    }

    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_image) {
            $path = public_path('uploads/avatars/' . $user->profile_image);
            if (file_exists($path)) {
                @unlink($path);
            }
            $user->update(['profile_image' => null]);
        }

        return Redirect::route('profile.edit')->with('success', 'Profile photo removed.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);
        $user = $request->user();

        // Clean up the avatar file too, since we're nuking the row.
        if ($user->profile_image) {
            $path = public_path('uploads/avatars/' . $user->profile_image);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }
}
