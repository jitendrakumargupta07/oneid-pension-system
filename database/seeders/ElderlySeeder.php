<?php

namespace Database\Seeders;

use App\Models\ElderlyProfile;
use App\Models\Notification;
use App\Models\PensionApplication;
use App\Models\PensionPayment;
use App\Models\PensionScheme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ElderlySeeder extends Seeder
{
    public function run(): void
    {
        $admin   = User::where('role', 'admin')->first();
        $oldAge  = PensionScheme::where('scheme_code', 'SCBP-001')->first();
        $widow   = PensionScheme::where('scheme_code', 'WPS-003')->first();
        $disability = PensionScheme::where('scheme_code', 'DPS-004')->first();
        $farmer  = PensionScheme::where('scheme_code', 'PMKPY-005')->first();

        $citizens = [
            [
                'user'    => ['name' => 'Ramesh Kumar Sharma', 'email' => 'ramesh@example.com'],
                'profile' => [
                    'full_name'              => 'Ramesh Kumar Sharma',
                    'age'                    => 68,
                    'date_of_birth'          => '1957-04-15',
                    'gender'                 => 'male',
                    'address'                => '12, Gandhi Nagar, Jaipur, Rajasthan - 302001',
                    'phone'                  => '9876543210',
                    'aadhaar_number'         => '123456789012',
                    'bank_account_number'    => '1234567890123456',
                    'bank_name'              => 'State Bank of India',
                    'ifsc_code'              => 'SBIN0001234',
                    'employment_status'      => 'unemployed',
                    'disability_percentage'  => 0,
                    'is_widow'               => false,
                    'income_level'           => 85000.00,
                    'marital_status'         => 'married',
                    'caste_category'         => 'general',
                    'is_verified'            => true,
                ],
                'scheme'  => $oldAge,
                'app_status' => 'approved',
                'payments'   => 3,
            ],
            [
                'user'    => ['name' => 'Savitri Devi Patel', 'email' => 'savitri@example.com'],
                'profile' => [
                    'full_name'              => 'Savitri Devi Patel',
                    'age'                    => 62,
                    'date_of_birth'          => '1963-08-22',
                    'gender'                 => 'female',
                    'address'                => '45, Shiv Colony, Ahmedabad, Gujarat - 380001',
                    'phone'                  => '9123456789',
                    'aadhaar_number'         => '234567890123',
                    'bank_account_number'    => '2345678901234567',
                    'bank_name'              => 'Punjab National Bank',
                    'ifsc_code'              => 'PUNB0001234',
                    'employment_status'      => 'unemployed',
                    'disability_percentage'  => 0,
                    'is_widow'               => true,
                    'income_level'           => 60000.00,
                    'marital_status'         => 'widowed',
                    'caste_category'         => 'obc',
                    'is_verified'            => true,
                ],
                'scheme'  => $widow,
                'app_status' => 'approved',
                'payments'   => 2,
            ],
            [
                'user'    => ['name' => 'Mohan Lal Verma', 'email' => 'mohan@example.com'],
                'profile' => [
                    'full_name'              => 'Mohan Lal Verma',
                    'age'                    => 72,
                    'date_of_birth'          => '1953-01-10',
                    'gender'                 => 'male',
                    'address'                => '78, Civil Lines, Lucknow, Uttar Pradesh - 226001',
                    'phone'                  => '9234567890',
                    'aadhaar_number'         => '345678901234',
                    'bank_account_number'    => '3456789012345678',
                    'bank_name'              => 'Bank of Baroda',
                    'ifsc_code'              => 'BARB0001234',
                    'employment_status'      => 'farmer',
                    'disability_percentage'  => 0,
                    'is_widow'               => false,
                    'income_level'           => 110000.00,
                    'marital_status'         => 'married',
                    'caste_category'         => 'sc',
                    'is_verified'            => true,
                ],
                'scheme'  => $farmer,
                'app_status' => 'pending',
                'payments'   => 0,
            ],
            [
                'user'    => ['name' => 'Lakshmi Bai Reddy', 'email' => 'lakshmi@example.com'],
                'profile' => [
                    'full_name'              => 'Lakshmi Bai Reddy',
                    'age'                    => 55,
                    'date_of_birth'          => '1970-11-05',
                    'gender'                 => 'female',
                    'address'                => '23, Jubilee Hills, Hyderabad, Telangana - 500033',
                    'phone'                  => '9345678901',
                    'aadhaar_number'         => '456789012345',
                    'bank_account_number'    => '4567890123456789',
                    'bank_name'              => 'Canara Bank',
                    'ifsc_code'              => 'CNRB0001234',
                    'employment_status'      => 'unemployed',
                    'disability_percentage'  => 60,
                    'is_widow'               => false,
                    'income_level'           => 70000.00,
                    'marital_status'         => 'married',
                    'caste_category'         => 'general',
                    'is_verified'            => false,
                ],
                'scheme'     => null,
                'app_status' => null,
                'payments'   => 0,
            ],
            [
                'user'    => ['name' => 'Suresh Chandra Mishra', 'email' => 'suresh@example.com'],
                'profile' => [
                    'full_name'              => 'Suresh Chandra Mishra',
                    'age'                    => 70,
                    'date_of_birth'          => '1955-07-20',
                    'gender'                 => 'male',
                    'address'                => '56, M.G. Road, Bhopal, Madhya Pradesh - 462001',
                    'phone'                  => '9456789012',
                    'aadhaar_number'         => '567890123456',
                    'bank_account_number'    => '5678901234567890',
                    'bank_name'              => 'Union Bank of India',
                    'ifsc_code'              => 'UBIN0001234',
                    'employment_status'      => 'retired_govt',
                    'disability_percentage'  => 0,
                    'is_widow'               => false,
                    'income_level'           => 180000.00,
                    'marital_status'         => 'married',
                    'caste_category'         => 'general',
                    'is_verified'            => true,
                ],
                'scheme'     => $oldAge,
                'app_status' => 'rejected',
                'payments'   => 0,
            ],
        ];

        foreach ($citizens as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['user']['email']],
                [
                    'name'      => $data['user']['name'],
                    'password'  => Hash::make('User@1234'),
                    'role'      => 'user',
                    'is_active' => true,
                ]
            );

            $profileData = array_merge($data['profile'], [
                'user_id' => $user->id,
                'one_id'  => ElderlyProfile::generateOneId(),
            ]);

            if ($data['profile']['is_verified'] && $admin) {
                $profileData['verified_at']             = now()->subDays(rand(10, 30));
                $profileData['verified_by']             = $admin->id;
                $profileData['verification_remarks']    = 'All documents verified. Profile approved.';
            }

            $profile = ElderlyProfile::firstOrCreate(
                ['aadhaar_number' => $data['profile']['aadhaar_number']],
                $profileData
            );

            if ($data['scheme'] && $data['app_status']) {
                $application = PensionApplication::firstOrCreate(
                    ['elderly_profile_id' => $profile->id],
                    [
                        'scheme_id'          => $data['scheme']->id,
                        'status'             => $data['app_status'],
                        'applied_at'         => now()->subDays(rand(30, 60)),
                        'application_number' => PensionApplication::generateApplicationNumber(),
                        'reviewed_at'        => in_array($data['app_status'], ['approved', 'rejected']) ? now()->subDays(rand(5, 20)) : null,
                        'reviewed_by'        => in_array($data['app_status'], ['approved', 'rejected']) ? $admin?->id : null,
                        'remarks'            => $data['app_status'] === 'rejected' ? 'Income exceeds eligibility limit.' : null,
                        'fraud_flagged'      => false,
                    ]
                );

                if ($data['app_status'] === 'approved' && $data['payments'] > 0) {
                    for ($i = $data['payments']; $i >= 1; $i--) {
                        $payMonth = now()->subMonths($i);
                        PensionPayment::firstOrCreate(
                            ['pension_application_id' => $application->id, 'month' => $payMonth->month, 'year' => $payMonth->year],
                            [
                                'amount'          => $data['scheme']->monthly_amount,
                                'payment_date'    => $payMonth->endOfMonth()->toDateString(),
                                'status'          => 'paid',
                                'transaction_ref' => 'TXN' . strtoupper(substr(md5(rand()), 0, 12)),
                                'receipt_number'  => PensionPayment::generateReceiptNumber(),
                            ]
                        );
                    }
                }

                if ($data['app_status'] === 'approved') {
                    Notification::firstOrCreate(
                        ['user_id' => $user->id, 'title' => '🎉 Pension Application Approved!'],
                        [
                            'message' => "Your pension application has been approved. Monthly: ₹" . number_format($data['scheme']->monthly_amount, 2),
                            'type'    => 'success',
                            'is_read' => false,
                        ]
                    );
                }
            }
        }

        $this->command->info('✅ 5 elderly citizens seeded with profiles, applications, and payments.');
        $this->command->info('   Default user password: User@1234');
    }
}
