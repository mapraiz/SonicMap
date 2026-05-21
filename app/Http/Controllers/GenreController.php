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
        $genres = [
            ['slug' => 'shoegaze', 'name' => 'Shoegaze', 'icon' => '🎸', 'desc' => 'Guitarras ruidosas, muros de distorsión, atmósferas envolventes y voces etéreas.'],
            ['slug' => 'post-punk', 'name' => 'Post Punk', 'icon' => '⛓️', 'desc' => 'Líneas de bajo oscuras, ritmos mecánicos y energía cruda.'],
            ['slug' => 'synth-pop', 'name' => 'Synth-Pop', 'icon' => '🎹', 'desc' => 'Sintetizadores retro, cajas de ritmos nostálgicas y melodías pop pegadizas.'],
            ['slug' => 'grunge', 'name' => 'Grunge', 'icon' => '🧥', 'desc' => 'El sonido distorsionado de Seattle, angustia adolescente y actitud alternativa.'],
            ['slug' => 'techno', 'name' => 'Techno', 'icon' => '🎧', 'desc' => 'Ritmos electrónicos repetitivos de club diseñados para el hipnotismo en la pista.'],
            ['slug' => 'jazz', 'name' => 'Jazz', 'icon' => '🎷', 'desc' => 'Improvisación pura, acordes complejos, síncopa y texturas clásicas norteamericanas.'],
            ['slug' => 'hip-hop', 'name' => 'Hip Hop', 'icon' => '🎤', 'desc' => 'Beats contundentes, sampleo analógico clásico de vinilos y poesía rítmica callejera.'],
            ['slug' => 'psychedelic-rock', 'name' => 'Rock Psicodélico', 'icon' => '🌀', 'desc' => 'Efectos de sonido experimentales, reverbs expansivas y viajes sonoros alucinantes.'],
            ['slug' => 'heavy-metal', 'name' => 'Heavy Metal', 'icon' => '⚡', 'desc' => 'Riffs pesados de guitarra, baterías atronadoras y la energía más pura del rock duro.'],
            ['slug' => 'ambient', 'name' => 'Ambient', 'icon' => '🌌', 'desc' => 'Paisajes sonoros minimalistas enfocados en texturas atmosféricas sobre la estructura.'],
        ];

        return view('genres.index', compact('genres'));
    }
    public function genreShow($slug)
    {
        $tag = strtolower($slug);

        $response = $this->mbService->getAlbumsByTag($tag, 24);
        $albums = $response['release-groups'] ?? [];

        $genreName = ucfirst(str_replace('-', ' ', $slug));

        return view('genres.show', [
            'albums' => $albums,
            'genreName' => $genreName,
            'slug' => $slug
        ]);
    }


}
