<?php

namespace App\Services;

use App\Models\ElderlyProfile;
use App\Models\PensionApplication;
use App\Models\PensionScheme;

/**
 * EligibilityChecker — evaluates whether an elderly citizen qualifies
 * for a given pension scheme based on profile attributes.
 */
class EligibilityChecker
{
    /**
     * Run all eligibility checks for a scheme against a profile.
     *
     * @return array{eligible: bool, checks: array, blockers: array}
     */
    public function check(ElderlyProfile $profile, PensionScheme $scheme): array
    {
        $checks   = [];
        $blockers = [];

        // ── 1. Age check ───────────────────────────────────────────────
        $minAge = $scheme->min_age ?? $scheme->eligibility_age;
        $agePassed = $profile->age >= $minAge;
        $checks[] = [
            'label'  => "Age ≥ {$minAge} years",
            'passed' => $agePassed,
            'value'  => "{$profile->age} years",
        ];
        if (!$agePassed) {
            $blockers[] = "Minimum age requirement is {$minAge} years. Your age: {$profile->age}";
        }

        // ── 2. Income check ────────────────────────────────────────────
        if ($scheme->max_income) {
            $incomePassed = $profile->income_level === null || $profile->income_level <= $scheme->max_income;
            $checks[] = [
                'label'  => 'Annual Income ≤ ₹' . number_format($scheme->max_income),
                'passed' => $incomePassed,
                'value'  => $profile->income_level ? '₹' . number_format($profile->income_level) : 'Not declared',
            ];
            if (!$incomePassed) {
                $blockers[] = 'Annual income exceeds the maximum limit of ₹' . number_format($scheme->max_income);
            }
        }

        // ── 3. Scheme-type specific checks ────────────────────────────
        if ($scheme->requires_govt_employment) {
            $govtPassed = $profile->employment_status === 'retired_govt';
            $checks[] = [
                'label'  => 'Retired Government Employee',
                'passed' => $govtPassed,
                'value'  => ucwords(str_replace('_', ' ', $profile->employment_status ?? 'unemployed')),
            ];
            if (!$govtPassed) {
                $blockers[] = 'This scheme is only for retired government employees.';
            }
        }

        if ($scheme->requires_widow_status) {
            $widowPassed = (bool) $profile->is_widow;
            $checks[] = [
                'label'  => 'Widow Status',
                'passed' => $widowPassed,
                'value'  => $profile->is_widow ? 'Yes (Widow)' : 'Not a widow',
            ];
            if (!$widowPassed) {
                $blockers[] = 'This scheme is only for widowed applicants.';
            }
        }

        if ($scheme->requires_disability) {
            $disabilityPassed = ($profile->disability_percentage ?? 0) >= 40;
            $checks[] = [
                'label'  => 'Disability ≥ 40%',
                'passed' => $disabilityPassed,
                'value'  => ($profile->disability_percentage ?? 0) . '%',
            ];
            if (!$disabilityPassed) {
                $blockers[] = 'Disability percentage must be ≥ 40%. Your declared: ' . ($profile->disability_percentage ?? 0) . '%';
            }
        }

        if ($scheme->requires_farmer_status) {
            $farmerPassed = $profile->employment_status === 'farmer';
            $checks[] = [
                'label'  => 'Farmer / Agricultural Worker',
                'passed' => $farmerPassed,
                'value'  => ucwords(str_replace('_', ' ', $profile->employment_status ?? 'unemployed')),
            ];
            if (!$farmerPassed) {
                $blockers[] = 'This scheme is only for farmers/agricultural workers.';
            }
        }

        // ── 4. No active pension check ─────────────────────────────────
        $hasActivePension = PensionApplication::where('elderly_profile_id', $profile->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        $noDuplicate = !$hasActivePension;
        $checks[] = [
            'label'  => 'No existing active pension',
            'passed' => $noDuplicate,
            'value'  => $hasActivePension ? 'Already has active/pending pension' : 'None',
        ];
        if (!$noDuplicate) {
            $blockers[] = 'You already have an active or pending pension application.';
        }

        // ── 5. Profile verified ────────────────────────────────────────
        $verifiedPassed = (bool) $profile->is_verified;
        $checks[] = [
            'label'  => 'Profile verified by admin',
            'passed' => $verifiedPassed,
            'value'  => $profile->is_verified ? 'Verified' : 'Pending verification',
        ];
        if (!$verifiedPassed) {
            $blockers[] = 'Your profile must be verified by an administrator before applying.';
        }

        $eligible = empty($blockers);

        return compact('eligible', 'checks', 'blockers');
    }

    /**
     * Quick check — just returns true/false (for scheme listing).
     */
    public function isEligible(ElderlyProfile $profile, PensionScheme $scheme): bool
    {
        return $this->check($profile, $scheme)['eligible'];
    }
}
