<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pension_schemes', function (Blueprint $table) {
            $table->enum('type', [
                'old_age', 'govt_employee', 'widow', 'disability', 'farmer', 'family',
            ])->default('old_age')->after('scheme_code');
            $table->unsignedTinyInteger('min_age')->default(60)->after('type');
            $table->decimal('max_income', 12, 2)->nullable()->after('min_age');
            $table->boolean('requires_disability')->default(false)->after('max_income');
            $table->boolean('requires_widow_status')->default(false)->after('requires_disability');
            $table->boolean('requires_govt_employment')->default(false)->after('requires_widow_status');
            $table->boolean('requires_farmer_status')->default(false)->after('requires_govt_employment');
            $table->json('required_documents')->nullable()->after('requires_farmer_status');
            $table->text('eligibility_description')->nullable()->after('required_documents');
        });
    }

    public function down(): void
    {
        Schema::table('pension_schemes', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'min_age', 'max_income', 'requires_disability',
                'requires_widow_status', 'requires_govt_employment',
                'requires_farmer_status', 'required_documents', 'eligibility_description',
            ]);
        });
    }
};
