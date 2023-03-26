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
        Schema::table('users', function (Blueprint $table) {
            $table->string('lastName',30);
            $table->dateTime('birthDate');
            $table->string('birthPlace', 50);
            $table->string('telephoneNumber', 20);
            $table->string('fiscalCode',50);
            $table->string('ibanCode',50);
            $table->string('bank',20);
            $table->string('contractType',20);
            $table->string('partitaIva',50);
            $table->string('profileImage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
