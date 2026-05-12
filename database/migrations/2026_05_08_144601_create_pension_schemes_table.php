<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pension_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('monthly_amount', 10, 2);
            $table->unsignedTinyInteger('eligibility_age')->default(60);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('scheme_code')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pension_schemes');
    }
};
