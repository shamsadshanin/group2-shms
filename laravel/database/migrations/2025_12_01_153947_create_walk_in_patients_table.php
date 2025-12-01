<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('walk_in_patients', function (Blueprint $table) {
            $table->id('WalkInID');
            $table->string('ReferenceNumber')->unique();
            $table->string('Name');
            $table->integer('Age');
            $table->enum('Gender', ['Male', 'Female', 'Other']);
            $table->string('ContactNumber', 20);
            $table->text('Address')->nullable();
            $table->string('EmergencyContact')->nullable();
            $table->text('ReasonForVisit');
            $table->enum('Priority', ['Low', 'Normal', 'High', 'Emergency'])->default('Normal');
            $table->enum('Status', ['Waiting', 'In Progress', 'Completed', 'Referred'])->default('Waiting');
            $table->foreignId('AssignedDoctorID')->nullable()->constrained('doctors', 'DoctorID');
            $table->foreignId('RegisteredBy')->constrained('users', 'id');
            $table->timestamp('RegisteredAt');
            $table->timestamp('SeenAt')->nullable();
            $table->text('Notes')->nullable();
            $table->timestamps();

            // Add indexes for better performance
            $table->index('ReferenceNumber');
            $table->index('Status');
            $table->index('Priority');
            $table->index('RegisteredAt');
            $table->index(['Status', 'Priority']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('walk_in_patients');
    }
};
