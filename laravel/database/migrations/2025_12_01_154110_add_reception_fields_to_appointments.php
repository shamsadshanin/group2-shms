<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('CheckedInAt')->nullable();
            $table->foreignId('CheckedInBy')->nullable()->constrained('users', 'id');
            $table->integer('WaitTime')->nullable()->comment('Wait time in minutes');
            $table->text('ReceptionNotes')->nullable();
            $table->enum('VisitType', ['Scheduled', 'Walk-In', 'Follow-Up', 'Emergency'])->default('Scheduled');

            $table->index('CheckedInAt');
            $table->index('VisitType');
            $table->index(['Date', 'Status']);
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'CheckedInAt', 'CheckedInBy', 'WaitTime',
                'ReceptionNotes', 'VisitType'
            ]);
        });
    }
};
