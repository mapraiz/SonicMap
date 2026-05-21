<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Song;
use App\Models\Artist;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
       $validated = $request->validate([
            'mbid' => 'required|string',
            'title' => 'required|string',
            'artist_mbid' => 'required|string',
            'artist_name' => 'required|string',
            'reviewable_type' => 'required|string|in:album,song', // Safe validation check
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_text' => 'nullable|string|max:5000',
        ]);

        // 1. Sync the local Artist baseline row standard
        $artist = Artist::firstOrCreate(
            ['mbid' => $validated['artist_mbid']],
            ['name' => $validated['artist_name']]
        );

        // 2. Determine if the parent model target is an Album or a Song row
        if ($validated['reviewable_type'] === 'album') {
            $model = Album::firstOrCreate(
                ['mbid' => $validated['mbid']],
                ['title' => $validated['title'], 'artist_id' => $artist->id]
            );
        } else {
            // For future song implementation
            $model = Song::firstOrCreate(
                ['mbid' => $validated['mbid']],
                ['title' => $validated['title'], 'artist_id' => $artist->id]
            );
        }

        // 3. Automatically save it to the logged-in user's personal collection shelf layout
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($validated['reviewable_type'] === 'album') {
            $user->savedAlbums()->syncWithoutDetaching([$model->id]);
        }

        // 4. Save Polymorphic morph relation rows natively
        $model->reviews()->updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'rating' => $validated['rating'] > 0 ? $validated['rating'] : null,
                // Only save text content if it's an album review attempt
                'review_text' => $validated['reviewable_type'] === 'album' ? ($validated['review_text'] ?? null) : null,
            ]
        );

        return redirect()->back()->with('success', '¡Tu calificación ha sido guardada con éxito!');
    }
}
