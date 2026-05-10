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
       Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Polymorphic columns: reviewable_id and reviewable_type
            $table->morphs('reviewable');
            $table->integer('rating'); // 1-5 stars [cite: 20, 63]
            $table->text('review_text')->nullable(); // [cite: 22, 64]
            $table->date('listened_at')->nullable(); // Manual input [cite: 21, 65]
            $table->timestamps();
        });
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('wishlistable'); // Can add Album or Song [cite: 67]
            $table->timestamps();
        });
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Polymorphic: This links to an Album, Song, or even Artist
            $table->morphs('librariable');

            $table->timestamps();

            // Ensure a user can't save the same album twice
            $table->unique(['user_id', 'librariable_id', 'librariable_type'], 'user_library_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('libraries');
        Schema::dropIfExists('reviews');
    }
};
