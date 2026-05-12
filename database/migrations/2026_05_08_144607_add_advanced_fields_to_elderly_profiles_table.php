<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elderly_profiles', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('age');
            $table->enum('employment_status', [
                'employed', 'self_employed', 'retired_govt',
                'retired_private', 'unemployed', 'farmer',
            ])->default('unemployed')->after('date_of_birth');
            $table->unsignedTinyInteger('disability_percentage')->default(0)->after('employment_status');
            $table->boolean('is_widow')->default(false)->after('disability_percentage');
            $table->decimal('income_level', 12, 2)->nullable()->after('is_widow');
            $table->enum('marital_status', ['single', 'married', 'widowed', 'divorced'])
                  ->default('single')->after('income_level');
            $table->enum('caste_category', ['general', 'obc', 'sc', 'st'])
                  ->default('general')->after('marital_status');
        });
    }

    public function down(): void
    {
        Schema::table('elderly_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth', 'employment_status', 'disability_percentage',
                'is_widow', 'income_level', 'marital_status', 'caste_category',
            ]);
        });
    }
};
