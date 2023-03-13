<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('document_type', 50);
            $table->string('status', 50);
            $table->text('notes');
            $table->string('type', 50);
            $table->string('document_path');
            $table->integer('to_user');
            $table->integer('last_update_from');
            $table->dateTime('docuemnt_uploaded_at');
            $table->foreignUuid('from_user');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
};
