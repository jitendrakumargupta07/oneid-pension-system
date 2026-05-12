<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pension_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pension_application_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->unsignedTinyInteger('month'); // 1-12
            $table->unsignedSmallInteger('year');
            $table->enum('status', ['paid', 'pending', 'failed'])->default('paid');
            $table->string('transaction_ref')->nullable();
            $table->string('receipt_number')->unique();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pension_payments');
    }
};
