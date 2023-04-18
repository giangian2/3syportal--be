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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('offer_photo')->nullable();
            $table->string('offer_name')->nullable();
            $table->string('budget')->nullable();
            $table->unsignedInteger('requested_talents')->nullable();
            $table->string('status')->nullable();
            $table->text('long_description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('closed_date')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
