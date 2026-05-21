<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MusicBrainzService
{
    /** * MusicBrainz requires a descriptive User-Agent to avoid being blocked.
     * This ensures we comply with their API policy.
     */
    protected $baseUrl = 'https://musicbrainz.org/ws/2';
    protected $userAgent = 'SonicMap/1.0 ( mapraizclase@gmail.com)';


        public function searchAlbums($term, $limit = 10, $offset = 0)
        {
            $response = $this->makeRequest('release-group', [
                'query' => 'releasegroup:' . $term,
                'limit' => $limit,
                'offset' => $offset,
                'fmt' => 'json'
            ]);

            return [
                'results' => $response['release-groups'] ?? [],
                'total' => $response['count'] ?? 0
            ];
        }


    public function getAlbumDetails($mbid)
    {
        $data = $this->makeRequest("release-group/{$mbid}", [
            'inc' => 'releases+artist-credits+genres',
            'fmt' => 'json',
        ]);

        if (!$data) {
            return null;
        }

        if (!empty($data['releases'])) {
            usleep(1100000);

            $releaseId = $data['releases'][0]['id'];
            $releaseData = $this->makeRequest("release/{$releaseId}", [
                'inc' => 'recordings',
                'fmt' => 'json',
            ]);

            if ($releaseData && isset($releaseData['media'][0]['tracks'])) {
                $data['tracks_data'] = $releaseData['media'][0]['tracks'];
            }
        }

        return $data;
    }

    /**
     * Standardized request method to handle headers and format.
     */
    protected function makeRequest($endpoint, $params)
    {
        $response = Http::withHeaders(['User-Agent' => $this->userAgent])
        ->withoutVerifying()
        ->get("{$this->baseUrl}/{$endpoint}", $params);

        if ($response->successful()) {
            return $response->json();
        }

        \Log::error("MB API Actual Failure: " . $response->status());
        return null;
    }
    public function getSongDetails($mbid)
    {
        return $this->makeRequest("recording/{$mbid}", [
            'inc' => 'releases+artist-credits',
            'fmt' => 'json'
        ]);
    }
    public function getGenres($limit = 100, $offset = 0)
    {
        return $this->makeRequest('genre', [
            'limit' => $limit,
            'offset' => $offset,
            'fmt' => 'json'
        ]);
    }
    public function getAlbumsByDecade($startYear, $endYear, $limit = 25, $offset=0)
    {
        $queryString = "primarytype:album AND firstreleasedate:[{$startYear} TO {$endYear}]";

        return $this->makeRequest('release-group', [
            'query' => $queryString,
            'limit' => $limit,
            'offset' => $offset,
            'fmt' => 'json'
        ]);
    }
    public function getTopTags($limit = 100)
    {
        return $this->makeRequest('tag', [
            'limit' => $limit,
            'fmt' => 'json'
        ]);
    }
    public function getAlbumsByTag($tag, $limit = 24) :array
    {
        return $this->makeRequest('release-group', [
            'query' => 'tag:"'.$tag.'" AND primarytype:album',
            'limit' => $limit,
            'fmt' => 'json'
        ]);
    }

}
