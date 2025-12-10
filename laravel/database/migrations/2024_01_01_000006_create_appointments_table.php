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
        Schema::create('tblappointment', function (Blueprint $table) {
            $table->string('cAppointmentID', 10)->primary();
            $table->string('cPatientID', 10);
            $table->foreign('cPatientID')->references('cPatientID')->on('tblpatient')->onDelete('cascade');
            $table->string('cDoctorID', 10);
            $table->foreign('cDoctorID')->references('cDoctorID')->on('tbldoctor')->onDelete('cascade');
            $table->dateTime('dAppointmentDateTime');
            $table->text('cPurpose');
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
        Schema::dropIfExists('tblappointment');
    }
};
