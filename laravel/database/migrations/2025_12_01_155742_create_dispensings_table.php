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
         Schema::create('dispensings', function (Blueprint $table) {
                    $table->id('DispensingID');
                    $table->foreignId('PrescriptionID')->constrained('prescriptions', 'PrescriptionID');
                    $table->foreignId('MedicineID')->constrained('medicines', 'MedicineID');
                    $table->integer('QuantityDispensed');
                    $table->decimal('UnitPrice', 10, 2);
                    $table->decimal('TotalAmount', 10, 2);
                    $table->foreignId('DispensedBy')->constrained('users', 'id');
                    $table->timestamp('DispensedAt');
                    $table->text('Notes')->nullable();
                    $table->timestamps();

                    $table->index('PrescriptionID');
                    $table->index('MedicineID');
                    $table->index('DispensedBy');
                    $table->index('DispensedAt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispensings');
    }
};
