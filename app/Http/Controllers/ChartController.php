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

        $perPage = 20;
        $currentPage = (int) $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $response = $this->mbService->getAlbumsByDecade($startYear, $endYear, $perPage, $offset);

        $apiAlbums = $response['release-groups'] ?? [];
        $totalAlbumsFound = $response['count'] ?? 0;

        $albumsCollection = collect($apiAlbums)->sortBy('first-release-date')->values();

        $paginator = new LengthAwarePaginator(
            $albumsCollection,
            $totalAlbumsFound,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        return view('charts.decade', [
            'albums' => $paginator,
            'decade' => $decade
        ]);
    }
}
