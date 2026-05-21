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
        Schema::create('album_user', function (Blueprint $table) {
            $table->id();

            // Connects to the users table 'id' (bigint unsigned)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Connects to your local albums table 'id' (bigint unsigned)
            // This automatically assumes the target column is named 'id' on the 'albums' table
            $table->foreignId('album_id')->constrained()->onDelete('cascade');

            $table->timestamps();

            // Prevents a user from saving the exact same database album row multiple times
            $table->unique(['user_id', 'album_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album_user');
    }
};
