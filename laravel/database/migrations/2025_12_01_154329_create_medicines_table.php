<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id('MedicineID');
            $table->foreignId('CategoryID')->constrained('medicine_categories', 'CategoryID');
            $table->foreignId('SupplierID')->nullable()->constrained('suppliers', 'SupplierID');
            $table->string('Name', 100);
            $table->string('GenericName', 100)->nullable();
            $table->string('BrandName', 100)->nullable();
            $table->string('SKU')->unique();
            $table->text('Description')->nullable();
            $table->string('DosageForm', 50); // Tablet, Capsule, Syrup, Injection, etc.
            $table->string('Strength', 50)->nullable();
            $table->integer('StockQuantity')->default(0);
            $table->integer('ReorderLevel')->default(10);
            $table->decimal('UnitPrice', 10, 2);
            $table->decimal('CostPrice', 10, 2);
            $table->date('ExpiryDate');
            $table->string('StorageConditions', 100)->nullable();
            $table->string('Manufacturer', 100)->nullable();
            $table->boolean('RequiresPrescription')->default(false);
            $table->boolean('IsActive')->default(true);
            $table->timestamps();

            // Add indexes for better performance
            $table->index('CategoryID');
            $table->index('SupplierID');
            $table->index('SKU');
            $table->index('Name');
            $table->index('GenericName');
            $table->index('ExpiryDate');
            $table->index('IsActive');
            $table->index(['IsActive', 'StockQuantity']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicines');
    }
};
