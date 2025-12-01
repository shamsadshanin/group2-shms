<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
                    $table->id('SupplierID');
                    $table->string('CompanyName', 100);
                    $table->string('ContactPerson', 100)->nullable();
                    $table->string('Email')->nullable();
                    $table->string('Phone', 20);
                    $table->text('Address')->nullable();
                    $table->string('City', 50)->nullable();
                    $table->string('Country', 50)->nullable();
                    $table->boolean('IsActive')->default(true);
                    $table->timestamps();

                    $table->index('CompanyName');
                    $table->index('IsActive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
