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

    /**
     * Search for albums (Release Groups) based on title and year.
     * Fulfils RF-04 (Motor de búsqueda) and RF-06 (Filtrado por cronología)[cite: 57, 59].
     */
    /*public function searchAlbums($title, $year = null)
    {
        // Construct the Lucene query for precise filtering [cite: 10]
        $query = "releasegroup:\"{$title}\"";

        if ($year) {
            $query .= " AND date:{$year}";
        }

        return $this->makeRequest('release-group', [
            'query' => $query,
            'fmt' => 'json',
        ]);
    }*/
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
                'total' => $response['count'] ?? 0 // MusicBrainz returns total matches here
            ];
        }

    /**
     * Fetch full album details, including the tracklist (songs).
     * Necessary for RF-08 (Registro de escucha) at the song level[cite: 62].
     */
    public function getAlbumDetails($mbid)
    {
       // Step 1: Fetch Release Group (The logs show this works perfectly!)
        $data = $this->makeRequest("release-group/{$mbid}", [
            'inc' => 'releases+artist-credits+genres',
            'fmt' => 'json',
        ]);

        // If Step 1 fails, stop here.
        if (!$data) {
            return null;
        }

        // Step 2: Try to get tracks from the first release
        if (!empty($data['releases'])) {
            // Wait to avoid 503
            usleep(1100000);

            $releaseId = $data['releases'][0]['id'];
            $releaseData = $this->makeRequest("release/{$releaseId}", [
                'inc' => 'recordings',
                'fmt' => 'json',
            ]);

            // Only attach tracks if the second call worked
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

        // ONLY log if it's NOT a 200/Success
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
        // The tag endpoint allows us to browse the most frequently used terms
        return $this->makeRequest('tag', [
            'limit' => $limit,
            'fmt' => 'json'
        ]);
    }
    public function getAlbumsByTag($tag, $limit = 24) :array
    {
        // This safely accesses the protected makeRequest method internally
        return $this->makeRequest('release-group', [
            'query' => 'tag:"'.$tag.'" AND primarytype:album',
            'limit' => $limit,
            'fmt' => 'json'
        ]);
    }

}
