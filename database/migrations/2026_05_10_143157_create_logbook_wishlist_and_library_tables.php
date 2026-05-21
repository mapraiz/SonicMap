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

            $table->numericMorphs('reviewable');
            $table->integer('rating')->nullable();// 1-5 stars [cite: 20, 63]
            $table->text('review_text')->nullable(); // [cite: 22, 64]
            $table->date('listened_at')->nullable(); // Manual input [cite: 21, 65]
            $table->timestamps();
        });
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('wishlistable');
            $table->timestamps();
        });
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->morphs('librariable');

            $table->timestamps();

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
