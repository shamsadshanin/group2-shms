<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('investigations', function (Blueprint $table) {
            $table->id('InvestigationID');
            $table->foreignId('PatientID')->constrained('patients', 'PatientID')->onDelete('cascade');
            $table->foreignId('StaffID')->nullable()->constrained('lab_technicians', 'StaffID')->onDelete('set null');
            $table->foreignId('TestTypeID')->constrained('test_types', 'TestTypeID');
            $table->foreignId('DoctorID')->nullable()->constrained('doctors', 'DoctorID');
            $table->text('TestNotes')->nullable();
            $table->text('ResultSummary')->nullable();
            $table->text('DetailedResults')->nullable();
            $table->json('TestParameters')->nullable();
            $table->string('DigitalReport')->nullable();
            $table->enum('Priority', ['Low', 'Normal', 'High', 'Critical'])->default('Normal');
            $table->enum('Status', ['Pending', 'Assigned', 'Processing', 'Completed', 'Cancelled'])->default('Pending');
            $table->timestamp('CollectionDate')->nullable();
            $table->timestamp('ProcessingDate')->nullable();
            $table->timestamp('CompletedDate')->nullable();
            $table->timestamps();

            // Add indexes
            $table->index('PatientID');
            $table->index('StaffID');
            $table->index('TestTypeID');
            $table->index('Status');
            $table->index('Priority');
            $table->index('CollectionDate');
        });
    }

    public function down()
    {
        Schema::dropIfExists('investigations');
    }
};
