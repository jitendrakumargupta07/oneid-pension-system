<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elderly_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('one_id')->unique(); // e.g. OID-2024-483921
            $table->string('full_name');
            $table->unsignedTinyInteger('age');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->text('address');
            $table->string('phone', 15);
            $table->string('aadhaar_number', 12)->unique();
            $table->string('bank_account_number');
            $table->string('bank_name');
            $table->string('ifsc_code', 11);
            $table->string('profile_photo')->nullable();
            $table->string('government_id_photo')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('verification_remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elderly_profiles');
    }
};
