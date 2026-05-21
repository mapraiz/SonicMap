<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->uuid('mbid')->unique(); // MusicBrainz ID
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->uuid('mbid')->unique();
            $table->foreignId('artist_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('release_year')->nullable();
            $table->timestamps();
        });
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->uuid('mbid')->unique();
            $table->foreignId('album_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('artist_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
        Schema::dropIfExists('songs');
        Schema::dropIfExists('albums');
    }
};
