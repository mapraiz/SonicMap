<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MusicBrainzService;
use Illuminate\Support\Facades\Cache;

class GenreController extends Controller
{
    protected $mbService;
    public function __construct(MusicBrainzService $mbService){
        $this->mbService = $mbService;
    }
    public function genresIndex()
    {
        // Cache the API response for 1 day (86400 seconds)
        $genres = Cache::remember('musicbrainz_genres', 86400, function () {
            $apiData = $this->mbService->getGenres(100, 0);

            return collect($apiData['genres'] ?? [])
                ->sortBy('name') // Sort alphabetically
                ->map(function ($genre) {
                    return [
                        'slug' => strtolower(str_replace(' ', '-', $genre['name'])),
                        'name' => ucfirst($genre['name']),
                        'desc' => $genre['comment'] ?? 'Explora lanzamientos e hitos musicales dentro de esta corriente.'
                    ];
                });
        });

        return view('genres.index', compact('genres'));
    }

}
