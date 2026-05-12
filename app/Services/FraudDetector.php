<?php

namespace App\Services;

use App\Models\ElderlyProfile;
use App\Models\FraudAlert;
use App\Models\PensionApplication;

/**
 * FraudDetector — checks for suspicious patterns and logs FraudAlerts.
 * Returns an array of detected issues (empty = clean).
 */
class FraudDetector
{
    /**
     * Run all fraud checks on a profile when applying.
     *
     * @return array  List of fraud alert models created.
     */
    public function scan(ElderlyProfile $profile): array
    {
        $alerts = [];

        // ── 1. Duplicate Aadhaar ───────────────────────────────────────
        $aadhaarDuplicate = ElderlyProfile::where('aadhaar_number', $profile->aadhaar_number)
            ->where('id', '!=', $profile->id)
            ->exists();

        if ($aadhaarDuplicate) {
            $alerts[] = $this->log($profile, 'duplicate_aadhaar',
                "Aadhaar number {$profile->aadhaar_number} is already registered under another citizen profile.",
                'high');
        }

        // ── 2. Duplicate Bank Account ──────────────────────────────────
        $bankDuplicate = ElderlyProfile::where('bank_account_number', $profile->bank_account_number)
            ->where('id', '!=', $profile->id)
            ->exists();

        if ($bankDuplicate) {
            $alerts[] = $this->log($profile, 'duplicate_bank_account',
                "Bank account {$profile->bank_account_number} is already linked to another citizen.",
                'high');
        }

        // ── 3. Multiple Approved Pensions ──────────────────────────────
        $approvedCount = PensionApplication::where('elderly_profile_id', $profile->id)
            ->where('status', 'approved')
            ->count();

        if ($approvedCount >= 1) {
            $alerts[] = $this->log($profile, 'multiple_pensions',
                "Citizen OneID {$profile->one_id} already has {$approvedCount} approved pension(s). New application is suspicious.",
                'high');
        }

        // ── 4. Suspicious Age (under 50) ───────────────────────────────
        if ($profile->age < 50) {
            $alerts[] = $this->log($profile, 'suspicious_age',
                "Applicant age {$profile->age} is unusually low for a pension application.",
                'medium');
        }

        // ── 5. Repeated Rejected Applications ─────────────────────────
        $rejectedCount = PensionApplication::where('elderly_profile_id', $profile->id)
            ->where('status', 'rejected')
            ->count();

        if ($rejectedCount >= 2) {
            $alerts[] = $this->log($profile, 'repeated_application',
                "Citizen has had {$rejectedCount} rejected applications and is applying again.",
                'medium');
        }

        return $alerts;
    }

    /**
     * Create and persist a FraudAlert (skip if identical open alert already exists).
     */
    private function log(ElderlyProfile $profile, string $type, string $description, string $severity): FraudAlert
    {
        return FraudAlert::firstOrCreate(
            [
                'elderly_profile_id' => $profile->id,
                'alert_type'         => $type,
                'status'             => 'open',
            ],
            [
                'description' => $description,
                'severity'    => $severity,
            ]
        );
    }

    /**
     * Check whether any HIGH severity open alerts exist for this profile.
     */
    public function hasHighSeverityAlerts(ElderlyProfile $profile): bool
    {
        return FraudAlert::where('elderly_profile_id', $profile->id)
            ->where('status', 'open')
            ->where('severity', 'high')
            ->exists();
    }
}
