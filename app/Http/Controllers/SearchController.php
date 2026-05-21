<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MusicBrainzService;
use App\Models\Album;
use App\Models\Song;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    protected $mbService;
    public function __construct(MusicBrainzService $mbService){
        $this->mbService = $mbService;
    }
    public function index(Request $request){
        $searchTerm = $request->input('query');
        $perPage = 10;
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;

        $results = [];
        $paginator = null;

        if ($searchTerm) {
            $apiData = $this->mbService->searchAlbums($searchTerm, $perPage, $offset);

            $paginator = new LengthAwarePaginator(
                $apiData['results'],
                $apiData['total'],
                $perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => $request->query()
                ]
            );
        }

        return view('search.index', [
            'results' => $paginator,
            'searchTerm' => $searchTerm
        ]);

    }
    public function show($mbid){
        $mbData = $this->mbService->getAlbumDetails($mbid);

        if (!$mbData) {
            return "The API returned null. Check your logs or wait a second and refresh.";
        }

        $localAlbum = Album::where('mbid', $mbid)->with('reviews.user')->first();
        $averageRating = $localAlbum ? $localAlbum->reviews->avg('rating') : null;

        $isLogged = false;
        if (auth()->check() && $localAlbum) {
            $isLogged = $localAlbum->users()->where('user_id', auth()->id())->exists();
        }

        return view('albums.show', [
            'album' => $mbData,
            'localAlbum' => $localAlbum,
            'averageRating' => $averageRating,
            'isLogged' => $isLogged,
        ]);
    }
    public function showSong($mbid)
    {
        $song = $this->mbService->getSongDetails($mbid);

        if (!$song) {
            abort(404, 'Song not found');
        }

        $localSong = Song::where('mbid', $mbid)->first();
        $averageRating = $localSong ? $localSong->ratings()->avg('rating') : null;

        return view('songs.show', [
            'song' => $song,
            'averageRating' => $averageRating
        ]);
    }

}
