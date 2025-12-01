<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id('PrescriptionID');
            $table->foreignId('AppointmentID')->constrained('appointments', 'AppointmentID')->onDelete('cascade');
            $table->foreignId('DoctorID')->constrained('doctors', 'DoctorID')->onDelete('cascade');
            $table->foreignId('PatientID')->constrained('patients', 'PatientID')->onDelete('cascade');
            $table->date('IssueDate');
            $table->string('MedicineName', 100);
            $table->string('Dosage', 50);
            $table->string('Frequency', 50);
            $table->string('Duration', 50);
            $table->text('Instructions')->nullable();
            $table->text('Notes')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->timestamps();

            // Add indexes
            $table->index('AppointmentID');
            $table->index('DoctorID');
            $table->index('PatientID');
            $table->index('IssueDate');
        });
    }

    public function down()
    {
        Schema::dropIfExists('prescriptions');
    }
};
