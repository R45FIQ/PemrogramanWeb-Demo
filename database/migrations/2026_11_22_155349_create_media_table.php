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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('type'); // Anime, Manga, Drama
            $table->string('genre');
            $table->decimal('rating', 3, 1)->nullable()->default(0.0);
            $table->integer('release_year')->nullable();
            $table->text('description')->nullable();
            $table->integer('episodes')->nullable();
            $table->integer('chapters')->nullable();
            $table->string('author')->nullable();
            $table->string('studio')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
