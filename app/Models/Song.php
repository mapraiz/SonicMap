<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Song extends Model
{

    protected $fillable = ['mbid', 'artist_id', 'title', 'release_year', 'genre'];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function reviews(): MorphMany
    {
        // 'reviewable' must match the morph prefix name used in your migrations
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function libraryEntries()
    {
        return $this->morphMany(Library::class, 'librariable');
    }
}
