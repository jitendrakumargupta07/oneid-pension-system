<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pension_application_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', [
                'aadhaar_card',
                'passport_photo',
                'income_certificate',
                'retirement_certificate',
                'disability_certificate',
                'death_certificate',
                'bank_passbook',
                'age_proof',
                'residence_proof',
                'farmer_certificate',
            ]);
            $table->string('file_path');
            $table->string('original_name');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};
