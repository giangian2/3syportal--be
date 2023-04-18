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
        Schema::create('social_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('social_id');
            $table->unsignedBigInteger('talent_id');
            $table->string('link')->nullable();
            $table->unsignedInteger('followers')->nullable();
            $table->unsignedInteger('last_8_posts_likes')->nullable();
            $table->unsignedInteger('last_8_posts_comments')->nullable();
            $table->foreign('social_id')->references('id')->on('socials')->onDelete('cascade');
            $table->foreign('talent_id')->references('id')->on('talents')->onDelete('cascade');
            $table->unique('social_id', 'talent_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_infos');
    }
};
