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
        Schema::create('tblsymptomresponse', function (Blueprint $table) {
            $table->string('cResponseID', 10)->primary();
            $table->string('cSymptomID', 10);
            $table->foreign('cSymptomID')->references('cSymptomID')->on('tblsymptominput')->onDelete('cascade');
            $table->text('cResponse');
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
        Schema::dropIfExists('tblsymptomresponse');
    }
};
