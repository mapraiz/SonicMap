<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['mbid', 'artist_id', 'title', 'release_year', 'genre'];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function libraryEntries()
    {
        return $this->morphMany(Library::class, 'librariable');
    }
}
