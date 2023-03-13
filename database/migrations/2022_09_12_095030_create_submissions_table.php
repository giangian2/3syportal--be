<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('document_type', 50);
            $table->string('status', 50);
            $table->text('notes');
            $table->string('type', 50);
            $table->string('document_path')->nullable();
            $table->bigInteger('to_user')->unsigned();
            $table->integer('last_update_from')->nullable();
            $table->dateTime('docuemnt_uploaded_at')->nullable();
            $table->bigInteger('from_user')->unsigned();
            $table->timestamps();
            $table->foreign('from_user')->references('id')->on('users');
            $table->foreign('to_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissions');
    }
};
