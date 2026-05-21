<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Artist;

class AlbumController extends Controller
{
    public function store(Request $request)
    {
       $validated = $request->validate([
            'mbid' => 'required|string',
            'title' => 'required|string',
            'artist_mbid' => 'required|string',
            'artist_name' => 'required|string',
            'release_date' => 'nullable|string',
        ]);


        $artist = Artist::firstOrCreate(
            ['mbid' => $validated['artist_mbid']],
            ['name' => $validated['artist_name']]
        );

        $releaseYear = !empty($validated['release_date'])
            ? substr($validated['release_date'], 0, 4)
            : null;

        $album = Album::firstOrCreate(
            ['mbid' => $validated['mbid']],
            [
                'title' => $validated['title'],
                'artist_id' => $artist->id,
                'release_year' => $releaseYear,
            ]
        );

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->savedAlbums()->syncWithoutDetaching([$album->id]);

        return redirect()->back()->with('success', '¡Álbum registrado con éxito!');
    }
}
