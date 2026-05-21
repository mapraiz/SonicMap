<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Http;

class SongController extends Controller
{
    public function show($id)
    {
        $response = Http::withoutVerifying()
            ->withHeaders([
                'User-Agent' => 'SonicMap/1.0.0 ( your-email@example.com )'
            ])->get("https://musicbrainz.org/ws/2/recording/{$id}", [
                'fmt' => 'json',
                'inc' => 'releases+artist-credits'
            ]);

        if ($response->failed()) {
            abort(404, 'No se pudo obtener la información de la canción.');
        }

        $song = $response->json();

        $localSong = Song::where('mbid', $id)->first();
        $averageRating = 0;
        if ($localSong) {
            $averageRating = $localSong->reviews()->avg('rating') ?? 0;
        }

        return view('songs.show', compact('song', 'averageRating', 'localSong'));
    }
}
