<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\DashboardController;
use App\Services\MusicBrainzService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/settings', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Albums
    Route::post('/albums', [AlbumController::class, 'store'])->name('albums.store');

    //review
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});
Route::get('/testMusic',function(MusicBrainzService $service){
    $results = $service->searchAlbums('Abbey Road');

    return response()->json($results);

});

//Search
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/album/{mbid}', [SearchController::class, 'show'])->name('albums.show');
Route::get('/song/{mbid}', [SongController::class, 'show'])->name('songs.show');

//Review

//Genres
Route::get('/genres', [GenreController::class, 'genresIndex'])->name('genres.index');
Route::get('/genres/{slug}', [GenreController::class, 'genreShow'])->name('genres.show');

//Charts
Route::get('/charts/decade/{decade}', [ChartController::class, 'decade'])->name('charts.decade');

//Albums

require __DIR__.'/auth.php';
