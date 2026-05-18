<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ChartController;
use App\Services\MusicBrainzService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/testMusic',function(MusicBrainzService $service){
    $results = $service->searchAlbums('Abbey Road');

    return response()->json($results);

});

//Search
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/album/{mbid}', [SearchController::class, 'show'])->name('albums.show');
Route::get('/song/{mbid}', [SearchController::class, 'showSong'])->name('song.show');

//Review
Route::post('/reviews/store', [ReviewController::class, 'store'])->name('reviews.store');

//Genres
Route::get('/genres', [GenreController::class, 'genresIndex'])->name('genres.index');

//Charts
Route::get('/charts/decade/{decade}', [ChartController::class, 'decade'])->name('charts.decade');

require __DIR__.'/auth.php';
