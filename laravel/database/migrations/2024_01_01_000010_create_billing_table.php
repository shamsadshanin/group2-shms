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
        Schema::create('tblbilling', function (Blueprint $table) {
            $table->string('cBillingID', 10)->primary();
            $table->string('cPatientID', 10);
            $table->foreign('cPatientID')->references('cPatientID')->on('tblpatient')->onDelete('cascade');
            $table->decimal('fAmount', 10, 2);
            $table->date('dBillingDate');
            $table->string('cStatus', 20);
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
        Schema::dropIfExists('tblbilling');
    }
};
