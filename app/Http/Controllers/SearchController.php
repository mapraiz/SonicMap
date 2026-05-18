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
        $page = $request->input('page', 1); // Default to page 1
        $offset = ($page - 1) * $perPage;

        $results = [];
        $paginator = null;

        if ($searchTerm) {
            // Fetch from API with pagination parameters
            $apiData = $this->mbService->searchAlbums($searchTerm, $perPage, $offset);

            // Create the custom Laravel Paginator
            $paginator = new LengthAwarePaginator(
                $apiData['results'],
                $apiData['total'],   // Total items across all pages
                $perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => $request->query() // Keeps the ?query=loveless in pagination links
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
        $localAlbum =Album::where('mbid', $mbid)->with('reviews.user')->first();
        $averageRating = $localAlbum ? $localAlbum->reviews->avg('rating') : null;
        $isLogged = $localAlbum ? $localAlbum->reviews()->where('user_id', auth()->id())->exists() : false;

       if (!$mbData) {
        return "The API returned null. Check your logs or wait a second and refresh.";
    }
        return view('albums.show', [
            'album' => $mbData,
            'localAlbum' => $localAlbum,
            'averageRating' => $averageRating,
            'isLogged' =>$isLogged,
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
