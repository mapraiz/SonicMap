<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MusicBrainzService;

class ChartController extends Controller
{
    protected $mbService;
    public function __construct(MusicBrainzService $mbService){
        $this->mbService = $mbService;
    }
    public function decade($decade)
    {
        $startYear = (int) $decade;
        $endYear = $startYear + 9;

        // Call the public service method we just created
        $response = $this->mbService->getAlbumsByDecade($startYear, $endYear, 25);

        $albums = $response['release-groups'] ?? [];

        return view('charts.decade', [
            'albums' => $albums,
            'decade' => $decade
        ]);
    }
}
