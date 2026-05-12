<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pension_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('elderly_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('scheme_id')->constrained('pension_schemes')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->string('application_number')->unique();
            $table->boolean('fraud_flagged')->default(false);
            $table->unsignedTinyInteger('eligibility_score')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pension_applications');
    }
};
