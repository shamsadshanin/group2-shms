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
        Schema::create('tblpatient', function (Blueprint $table) {
            $table->string('cPatientID', 10)->primary();
            $table->unsignedBigInteger('cUserID');
            $table->foreign('cUserID')->references('id')->on('users')->onDelete('cascade');
            $table->string('cName', 255);
            $table->string('nAge');
            $table->string('cGender', 10);
            $table->string('cEmail', 255)->unique();
            $table->text('cAddress');
            $table->string('cPhone', 20);
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
        Schema::dropIfExists('tblpatient');
    }
};
