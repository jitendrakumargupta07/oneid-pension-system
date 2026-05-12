<?php

namespace Database\Seeders;

use App\Models\PensionScheme;
use Illuminate\Database\Seeder;

class SchemeSeeder extends Seeder
{
    public function run(): void
    {
        $schemes = [
            [
                'name'                    => 'Indira Gandhi Old Age Pension',
                'scheme_code'             => 'SCBP-001',
                'type'                    => 'old_age',
                'description'             => 'Monthly pension for elderly citizens aged 60 and above who are below the poverty line and have no regular income source.',
                'monthly_amount'          => 3000.00,
                'eligibility_age'         => 60,
                'min_age'                 => 60,
                'max_income'              => 200000.00,
                'status'                  => 'active',
                'requires_disability'     => false,
                'requires_widow_status'   => false,
                'requires_govt_employment'=> false,
                'requires_farmer_status'  => false,
                'required_documents'      => ['aadhaar_card', 'passport_photo', 'income_certificate', 'age_proof', 'bank_passbook'],
                'eligibility_description' => 'Age ≥ 60 years, Annual income ≤ ₹2,00,000, No active pension',
            ],
            [
                'name'                    => 'Government Employee Pension Scheme',
                'scheme_code'             => 'GEPS-002',
                'type'                    => 'govt_employee',
                'description'             => 'Pension scheme for retired government employees who served for minimum 10 years in central or state government.',
                'monthly_amount'          => 8000.00,
                'eligibility_age'         => 58,
                'min_age'                 => 58,
                'max_income'              => null,
                'status'                  => 'active',
                'requires_disability'     => false,
                'requires_widow_status'   => false,
                'requires_govt_employment'=> true,
                'requires_farmer_status'  => false,
                'required_documents'      => ['aadhaar_card', 'passport_photo', 'retirement_certificate', 'bank_passbook'],
                'eligibility_description' => 'Must be a retired government employee, Age ≥ 58',
            ],
            [
                'name'                    => 'Widow Pension Scheme',
                'scheme_code'             => 'WPS-003',
                'type'                    => 'widow',
                'description'             => 'Monthly financial support for widowed women who have lost their spouse and have no other source of income.',
                'monthly_amount'          => 2500.00,
                'eligibility_age'         => 40,
                'min_age'                 => 40,
                'max_income'              => 150000.00,
                'status'                  => 'active',
                'requires_disability'     => false,
                'requires_widow_status'   => true,
                'requires_govt_employment'=> false,
                'requires_farmer_status'  => false,
                'required_documents'      => ['aadhaar_card', 'passport_photo', 'income_certificate', 'death_certificate', 'bank_passbook'],
                'eligibility_description' => 'Widowed status required, Age ≥ 40, Annual income ≤ ₹1,50,000',
            ],
            [
                'name'                    => 'Disability Pension Scheme',
                'scheme_code'             => 'DPS-004',
                'type'                    => 'disability',
                'description'             => 'Financial assistance for persons with physical or mental disability of 40% or more who are unable to earn livelihood.',
                'monthly_amount'          => 4000.00,
                'eligibility_age'         => 18,
                'min_age'                 => 18,
                'max_income'              => 250000.00,
                'status'                  => 'active',
                'requires_disability'     => true,
                'requires_widow_status'   => false,
                'requires_govt_employment'=> false,
                'requires_farmer_status'  => false,
                'required_documents'      => ['aadhaar_card', 'passport_photo', 'disability_certificate', 'income_certificate', 'bank_passbook'],
                'eligibility_description' => 'Disability ≥ 40%, Age ≥ 18, Annual income ≤ ₹2,50,000',
            ],
            [
                'name'                    => 'Pradhan Mantri Farmer Pension Yojana',
                'scheme_code'             => 'PMKPY-005',
                'type'                    => 'farmer',
                'description'             => 'Old age income security to small and marginal farmers aged 60 years and above who own up to 2 hectares of land.',
                'monthly_amount'          => 3500.00,
                'eligibility_age'         => 60,
                'min_age'                 => 60,
                'max_income'              => 180000.00,
                'status'                  => 'active',
                'requires_disability'     => false,
                'requires_widow_status'   => false,
                'requires_govt_employment'=> false,
                'requires_farmer_status'  => true,
                'required_documents'      => ['aadhaar_card', 'passport_photo', 'farmer_certificate', 'income_certificate', 'bank_passbook'],
                'eligibility_description' => 'Farmer/Agricultural worker, Age ≥ 60, Annual income ≤ ₹1,80,000',
            ],
            [
                'name'                    => 'Family Pension Scheme',
                'scheme_code'             => 'FPS-006',
                'type'                    => 'family',
                'description'             => 'Pension for eligible family members of a deceased pensioner. Applicable to spouse or dependent children.',
                'monthly_amount'          => 2000.00,
                'eligibility_age'         => 45,
                'min_age'                 => 45,
                'max_income'              => 120000.00,
                'status'                  => 'active',
                'requires_disability'     => false,
                'requires_widow_status'   => false,
                'requires_govt_employment'=> false,
                'requires_farmer_status'  => false,
                'required_documents'      => ['aadhaar_card', 'passport_photo', 'death_certificate', 'income_certificate', 'bank_passbook', 'age_proof'],
                'eligibility_description' => 'Family member of deceased pensioner, Age ≥ 45, Annual income ≤ ₹1,20,000',
            ],
        ];

        foreach ($schemes as $scheme) {
            PensionScheme::updateOrCreate(
                ['scheme_code' => $scheme['scheme_code']],
                $scheme
            );
        }

        $this->command->info('✅ Pension schemes seeded: ' . count($schemes) . ' schemes.');
    }
}
