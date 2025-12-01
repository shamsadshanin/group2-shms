<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lab_technicians', function (Blueprint $table) {
            $table->id('StaffID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('Name', 100);
            $table->string('Department', 100);
            $table->string('Qualification', 100)->nullable();
            $table->string('LicenseNumber', 50)->nullable();
            $table->string('ContactNumber', 20);
            $table->string('Email')->unique();
            $table->boolean('IsActive')->default(true);
            $table->timestamps();

            // Add indexes for better performance
            $table->index('user_id');
            $table->index('Department');
            $table->index('IsActive');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lab_technicians');
    }
};
