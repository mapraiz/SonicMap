<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function show()
    {
        $user = Auth::user();

        // 1. Get the albums saved in their library, eager loading their artists
        $savedAlbums = $user->savedAlbums()
            ->with('artist') // Prevents N+1 queries when loading artist names
            ->take(12)
            ->get();

        // 2. Get the recent ratings, eager loading the polymorphic 'reviewable' relation and its artist
        // THE CRITICAL FIX: Loads the morphed model and nested artist model cleanly
        $recentRatings = $user->ratings()
            ->with('reviewable.artist')
            ->latest() // Orders by newest first
            ->take(10)
            ->get();

        // 3. Calculate stats for the profile header
        $stats = [
            'total_saved' => $user->savedAlbums()->count(),
            'total_ratings' => $user->ratings()->count(),
            'average_rating' => round($user->ratings()->avg('rating') ?? 0, 1)
        ];

        return view('profile.show', compact('user', 'savedAlbums', 'recentRatings', 'stats'));
    }
}
