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
        Schema::create('tblinsuredpatient', function (Blueprint $table) {
            $table->string('cPatientID', 10)->primary();
            $table->string('cInsuranceID', 10);
            $table->string('cInsuranceProvider', 50);
            $table->string('cPolicyNumber', 20);
            $table->foreign('cPatientID')->references('cPatientID')->on('tblpatient')->onDelete('cascade');
        });

        Schema::create('tblinsuranceclaim', function (Blueprint $table) {
            $table->string('cClaimID', 10)->primary();
            $table->string('cPatientID', 10);
            $table->string('cInsuranceID', 10);
            $table->decimal('nClaimAmount', 10, 2);
            $table->date('dClaimDate');
            $table->string('cClaimStatus', 20);
            $table->foreign('cPatientID')->references('cPatientID')->on('tblpatient')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblinsuranceclaim');
        Schema::dropIfExists('tblinsuredpatient');
    }
};