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
    public function searchAlbums($title, $year = null)
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
    }

    /**
     * Fetch full album details, including the tracklist (songs).
     * Necessary for RF-08 (Registro de escucha) at the song level[cite: 62].
     */
    public function getAlbumDetails($mbid)
    {
        return $this->makeRequest("release-group/{$mbid}", [
            // 'inc' stands for 'include'. We want releases and their recordings[cite: 39].
            'inc' => 'releases+recordings',
            'fmt' => 'json',
        ]);
    }

    /**
     * Standardized request method to handle headers and format.
     */
    protected function makeRequest($endpoint, $params)
    {
        $response = Http::withHeaders([
            'User-Agent' => $this->userAgent
        ])
        ->withoutVerifying()
        ->get("{$this->baseUrl}/{$endpoint}", $params);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
