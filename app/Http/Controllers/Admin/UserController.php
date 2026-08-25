<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->query('q', ''));
        $role     = $request->query('role', 'all');
        $verified = $request->query('verified', 'all');
        $cv       = $request->query('cv', 'all');
        $sort     = $request->query('sort', 'newest');

        $query = User::query();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if (in_array($role, ['user', 'admin'], true)) {
            $query->where('role', $role);
        }

        if ($verified === 'yes') {
            $query->whereNotNull('email_verified_at');
        } elseif ($verified === 'no') {
            $query->whereNull('email_verified_at');
        }

        if ($cv === 'uploaded') {
            $query->whereHas('cvProfile');
        } elseif ($cv === 'none') {
            $query->whereDoesntHave('cvProfile');
        }

        $query->withCount(['jobMatches', 'skillGaps', 'recommendedCourses'])
              ->with('cvProfile');

        $query = match ($sort) {
            'oldest' => $query->oldest(),
            'name'   => $query->orderBy('name'),
            default  => $query->latest(),
        };

        $users = $query->paginate(15)->withQueryString();

        $totalCount = User::count();

        return view('admin.users.index', compact(
            'users', 'q', 'role', 'verified', 'cv', 'sort', 'totalCount'
        ));
    }

    public function edit(User $user)
    {
        $user->loadCount(['jobMatches', 'skillGaps', 'recommendedCourses']);
        $user->load('cvProfile');

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'  => 'required|in:user,admin',
        ]);

        // Self-protection: admin can't demote themselves
        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];

        // Verified toggle
        if ($request->boolean('verified')) {
            if (! $user->email_verified_at) {
                $user->email_verified_at = now();
            }
        } else {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$name}' deleted successfully.");
    }
}
