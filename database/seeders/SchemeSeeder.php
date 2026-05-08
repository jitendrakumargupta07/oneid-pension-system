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
                'name'            => 'Senior Citizen Basic Pension',
                'description'     => 'A basic monthly pension for senior citizens aged 60 and above to ensure financial security and dignified living.',
                'monthly_amount'  => 3000.00,
                'eligibility_age' => 60,
                'status'          => 'active',
                'scheme_code'     => 'SCBP-001',
            ],
            [
                'name'            => 'Widow Pension Scheme',
                'description'     => 'Monthly financial assistance for widows aged 55 and above who have no other means of income or support.',
                'monthly_amount'  => 2500.00,
                'eligibility_age' => 55,
                'status'          => 'active',
                'scheme_code'     => 'WPS-002',
            ],
            [
                'name'            => 'Disability Pension Scheme',
                'description'     => 'Financial support for elderly citizens with 40% or more disability, enabling them to live with dignity.',
                'monthly_amount'  => 4000.00,
                'eligibility_age' => 55,
                'status'          => 'active',
                'scheme_code'     => 'DPS-003',
            ],
            [
                'name'            => 'Rural Elderly Support Scheme',
                'description'     => 'Targeted pension support for elderly citizens from rural areas and economically weaker sections.',
                'monthly_amount'  => 2000.00,
                'eligibility_age' => 60,
                'status'          => 'active',
                'scheme_code'     => 'RESS-004',
            ],
        ];

        foreach ($schemes as $scheme) {
            PensionScheme::firstOrCreate(['scheme_code' => $scheme['scheme_code']], $scheme);
        }

        $this->command->info('✅ Pension schemes seeded: 4 schemes.');
    }
}
