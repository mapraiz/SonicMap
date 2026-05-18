<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Song;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'mbid' => 'required|string',
            'rating' => 'required|numeric|min:0|max:5', // min changed to 0
            'item_type' => 'required|in:album,song',
        ]);

        $item = ($request->item_type === 'album')
            ? \App\Models\Album::firstOrCreate(['mbid' => $request->mbid])
            : \App\Models\Song::firstOrCreate(['mbid' => $request->mbid]);

        // Check if a review already exists
        $existingReview = $item->reviews()->where('user_id', auth()->id())->first();

        // If it's a "Quick Register" (rating 0) and a review already exists, don't change anything
        if ($request->rating == 0 && $existingReview) {
            return back()->with('status', 'Ya tienes este álbum en tu logbook.');
        }

        $item->reviews()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'rating' => ($request->rating > 0) ? $request->rating : ($existingReview->rating ?? 0),
                'review_text' => $request->review_text ?? ($existingReview->review_text ?? null),
            ]
        );

        return back()->with('status', '¡Logbook actualizado!');
    }
}
