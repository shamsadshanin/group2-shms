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
        Schema::create('tbllabtechnician', function (Blueprint $table) {
            $table->string('cLabTechnicianID', 10)->primary();
            $table->string('cName', 255);
            $table->string('cEmail', 255)->unique();
            $table->string('cContactNumber', 20);
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
        Schema::dropIfExists('tbllabtechnician');
    }
};
