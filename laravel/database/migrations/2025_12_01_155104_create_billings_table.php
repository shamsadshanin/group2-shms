<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id('InvoiceID');
            $table->foreignId('PatientID')->constrained('patients', 'PatientID')->onDelete('cascade');
            $table->foreignId('AppointmentID')->nullable()->constrained('appointments', 'AppointmentID')->onDelete('set null');
            $table->string('InvoiceNumber')->unique();
            $table->decimal('ConsultationFee', 10, 2)->default(0);
            $table->decimal('TestFees', 10, 2)->default(0);
            $table->decimal('MedicineFees', 10, 2)->default(0);
            $table->decimal('TotalAmount', 10, 2);
            $table->decimal('Discount', 10, 2)->default(0);
            $table->decimal('TaxAmount', 10, 2)->default(0);
            $table->decimal('FinalAmount', 10, 2);
            $table->date('IssueDate');
            $table->date('DueDate');
            $table->enum('PaymentStatus', ['Pending', 'Paid', 'Partial', 'Overdue', 'Cancelled'])->default('Pending');
            $table->enum('PaymentMode', ['Cash', 'Card', 'Bank Transfer', 'Insurance', 'Online'])->nullable();
            $table->string('TransactionID')->nullable();
            $table->text('Notes')->nullable();
            $table->timestamps();

            // Add indexes for better performance
            $table->index('PatientID');
            $table->index('AppointmentID');
            $table->index('InvoiceNumber');
            $table->index('PaymentStatus');
            $table->index('IssueDate');
            $table->index('DueDate');
        });
    }

    public function down()
    {
        Schema::dropIfExists('billings');
    }
};
