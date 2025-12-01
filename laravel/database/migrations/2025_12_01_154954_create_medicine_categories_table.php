<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicine_categories', function (Blueprint $table) {
            $table->id('CategoryID');
            $table->string('CategoryName', 100);
            $table->text('Description')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->timestamps();

            $table->index('CategoryName');
            $table->index('IsActive');
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicine_categories');
    }
};
