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
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->unsignedInteger('capacity')->default(0);
            $table->decimal('rating', 3, 2)->default(0)->comment('0-5');
            $table->json('images')->nullable()->comment('Array of image URLs/paths');
            $table->timestamps();

            $table->index(['lat', 'lng']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
