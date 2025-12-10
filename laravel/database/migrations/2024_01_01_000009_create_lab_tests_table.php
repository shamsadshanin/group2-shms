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
        Schema::create('tbllabtest', function (Blueprint $table) {
            $table->string('cLabTestID', 20)->primary();
            $table->string('cPatientID', 10);
            $table->string('cLabTechnicianID', 10)->nullable();
            $table->foreign('cPatientID')->references('cPatientID')->on('tblpatient')->onDelete('cascade');
            $table->foreign('cLabTechnicianID')->references('cLabTechnicianID')->on('tbllabtechnician')->onDelete('set null');
            $table->string('cTestName', 255);
            $table->text('cResult')->nullable();
            $table->dateTime('dTestDate');
            $table->string('cStatus', 50)->default('Pending');
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
        Schema::dropIfExists('tbllabtest');
    }
};
