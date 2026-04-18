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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('address');
            $table->string('state', 50); // use this to filter hotels by state in the search function
            $table->integer('click_count')->default(0);
            $table->timestamps();
            $table->text('policies')->nullable(); // For example: "No reduns”，“NO Smoking", "Has swimming pool"
            $table->string('cover_image'); // cover image of hotel listing in home page
            $table->json('images')->nullable(); // for multiple images and will be retrieved in hotel detail page
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
