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
            'artist_mbid' => 'required|string', // MusicBrainz Artist UUID
            'artist_name' => 'required|string', // MusicBrainz Artist Name string
            'release_date' => 'nullable|string',
        ]);

        // 2. Sync Local Artist records first (using our original table structure rules)
        // If the artist doesn't exist locally, create them using their unique MusicBrainz UUID
        $artist = Artist::firstOrCreate(
            ['mbid' => $validated['artist_mbid']],
            ['name' => $validated['artist_name']]
        );

        // 3. Extract the clean 4-digit release year format safely
        $releaseYear = !empty($validated['release_date'])
            ? substr($validated['release_date'], 0, 4)
            : null;

        // 4. Create the Album using the local integer id ($artist->id) for the foreign key slot
        $album = Album::firstOrCreate(
            ['mbid' => $validated['mbid']],
            [
                'title' => $validated['title'],
                'artist_id' => $artist->id, // <-- THE CRITICAL FIX: Passing a local bigint instead of a UUID string!
                'release_year' => $releaseYear,
            ]
        );

        // 5. Connect relationship data rows inside your user pivot table grid mappings
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->savedAlbums()->syncWithoutDetaching([$album->id]);

        return redirect()->back()->with('success', '¡Álbum registrado con éxito!');
    }
}
