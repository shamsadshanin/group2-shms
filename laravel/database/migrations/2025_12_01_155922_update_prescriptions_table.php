<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::table('prescriptions', function (Blueprint $table) {
                  $table->enum('Status', ['Pending', 'Dispensed', 'Partially_Dispensed', 'Cancelled'])->default('Pending');
                  $table->foreignId('MedicineID')->nullable()->constrained('medicines', 'MedicineID');
                  $table->integer('QuantityPrescribed');
                  $table->integer('QuantityDispensed')->default(0);
                  $table->timestamp('DispensedAt')->nullable();
                  $table->foreignId('DispensedBy')->nullable()->constrained('users', 'id');
                  $table->text('PharmacyNotes')->nullable();

                  $table->index('Status');
                  $table->index('MedicineID');
                  $table->index('DispensedAt');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
