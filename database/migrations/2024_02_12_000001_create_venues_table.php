<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category'); // Function Venue, Ballroom, Hotel, etc.
            $table->string('suburb'); // Sydney CBD, etc.
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->unsignedInteger('capacity')->default(0);
            $table->unsignedInteger('area_sqm')->nullable(); // or number of rooms
            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->unsignedTinyInteger('price_level')->default(1); // 1-5 ($ to $$$$$)
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('video_thumbnail_url')->nullable();
            $table->boolean('has_offer')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
