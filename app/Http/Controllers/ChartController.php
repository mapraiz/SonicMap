<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MusicBrainzService;
use Illuminate\Pagination\LengthAwarePaginator;

class ChartController extends Controller
{
    protected $mbService;
    public function __construct(MusicBrainzService $mbService){
        $this->mbService = $mbService;
    }
    public function decade(Request $request, $decade)
    {
        $startYear = (int) $decade;
        $endYear = $startYear + 9;

        // 1. Determine pagination layout numbers
        $perPage = 20;
        $currentPage = (int) $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        // 2. Fetch the specific page slice from MusicBrainz
        $response = $this->mbService->getAlbumsByDecade($startYear, $endYear, $perPage, $offset);

        // 3. Extract the items and the global database total count
        $apiAlbums = $response['release-groups'] ?? [];
        $totalAlbumsFound = $response['count'] ?? 0;

        // Safety filter to guarantee clean release strings inside our page pool
        $albumsCollection = collect($apiAlbums)->sortBy('first-release-date')->values();

        // 4. Build the dynamic LengthAwarePaginator instance
        $paginator = new LengthAwarePaginator(
            $albumsCollection,
            $totalAlbumsFound, // MusicBrainz tells us exactly how many exist overall
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query() // Keeps track of pagination query string flags
            ]
        );

        return view('charts.decade', [
            'albums' => $paginator,
            'decade' => $decade
        ]);
    }
}
