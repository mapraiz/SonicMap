<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Calculate Aggregate Statistics
        $totalAlbums = Review::where('user_id', $userId)->where('reviewable_type', 'album')->count();
        $totalSongs  = Review::where('user_id', $userId)->where('reviewable_type', 'song')->count();
        $averageScore = Review::where('user_id', $userId)->avg('rating') ?? 0;

        // 2. Fetch the 5 most recent updates
        $recentReviews = Review::with('reviewable') // Eager-load polymorphic relation models
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // 3. Fetch the user's ultimate favorites
        $topGems = Review::with('reviewable')
            ->where('user_id', $userId)
            ->where('rating', '>=', 4.5)
            ->latest()
            ->take(4)
            ->get();

        return view('dashboard', compact('totalAlbums', 'totalSongs', 'averageScore', 'recentReviews', 'topGems'));
    }
}
