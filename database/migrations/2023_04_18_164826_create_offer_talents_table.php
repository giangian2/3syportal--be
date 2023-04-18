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
        Schema::create('offer_talents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('talent_id');
            $table->unsignedBigInteger('offer_id');
            $table->string('status');
            $table->foreign('talent_id')->references('id')->on('talents')->onDelete('cascade');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->unique(['talent_id', 'offer_id']);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_talents');
    }
};
