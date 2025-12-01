<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
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
        });
    }

    public function down()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn([
                'Status', 'MedicineID', 'QuantityPrescribed',
                'QuantityDispensed', 'DispensedAt', 'DispensedBy', 'PharmacyNotes'
            ]);
        });
    }
};
