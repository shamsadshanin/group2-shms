<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_types', function (Blueprint $table) {
            $table->id('TestTypeID');
            $table->string('TestName', 100);
            $table->string('Category', 50); // Hematology, Biochemistry, Microbiology, etc.
            $table->text('Description')->nullable();
            $table->decimal('Price', 8, 2)->default(0);
            $table->integer('ProcessingTime')->default(24); // in hours
            $table->json('NormalRanges')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->timestamps();

            $table->index('Category');
            $table->index('IsActive');
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_types');
    }
};
