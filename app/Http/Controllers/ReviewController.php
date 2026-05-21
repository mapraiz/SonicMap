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
            'reviewable_type' => 'required|string|in:album,song',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_text' => 'nullable|string|max:5000',
        ]);

        $artist = Artist::firstOrCreate(
            ['mbid' => $validated['artist_mbid']],
            ['name' => $validated['artist_name']]
        );


        if ($validated['reviewable_type'] === 'album') {
            $model = Album::firstOrCreate(
                ['mbid' => $validated['mbid']],
                ['title' => $validated['title'], 'artist_id' => $artist->id]
            );
        } else {

            $model = Song::firstOrCreate(
                ['mbid' => $validated['mbid']],
                ['title' => $validated['title'], 'artist_id' => $artist->id]
            );
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($validated['reviewable_type'] === 'album') {
            $user->savedAlbums()->syncWithoutDetaching([$model->id]);
        }

        $model->reviews()->updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'rating' => $validated['rating'] > 0 ? $validated['rating'] : null,
                'review_text' => $validated['reviewable_type'] === 'album' ? ($validated['review_text'] ?? null) : null,
            ]
        );

        return redirect()->back()->with('success', '¡Tu calificación ha sido guardada con éxito!');
    }
}
