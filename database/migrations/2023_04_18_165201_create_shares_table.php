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
        Schema::create('shares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_sharing_id');
            $table->unsignedBigInteger('offer_id');
            $table->boolean('budget_shared');
            $table->boolean('mediaKit_shared');
            $table->string('expiration_date')->nullable();
            $table->string('token');
            $table->string('password')->nullable();
            $table->foreign('user_sharing_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shares');
    }
};
