<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id('DoctorID');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('Name', 100);
            $table->string('Specialization', 100);
            $table->string('Email')->unique();
            $table->string('ContactNumber', 20);
            $table->text('Qualifications')->nullable();
            $table->integer('ExperienceYears')->default(0);
            $table->json('Availability')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->timestamps();

            // Add indexes for better performance
            $table->index('user_id');
            $table->index('Email');
            $table->index('Specialization');
            $table->index('IsActive');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
};
