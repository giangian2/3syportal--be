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
        Schema::create('talent_verticalities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('talent_id');
            $table->unsignedBigInteger('verticality_id');
            $table->foreign('talent_id')->references('id')->on('talents')->onDelete('cascade');
            $table->foreign('verticality_id')->references('id')->on('verticalities')->onDelete('cascade');
            $table->unique(['talent_id', 'verticality_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talent_verticalities');
    }
};
