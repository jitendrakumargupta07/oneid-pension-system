<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PensionApplication;
use App\Models\PensionScheme;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Show user's pension applications.
     */
    public function index()
    {
        $profile      = auth()->user()->elderlyProfile;
        $applications = collect();

        if ($profile) {
            $applications = PensionApplication::with('scheme')
                ->where('elderly_profile_id', $profile->id)
                ->latest()
                ->get();
        }

        return view('user.applications.index', compact('profile', 'applications'));
    }

    /**
     * Show available schemes and application form.
     */
    public function create()
    {
        $profile = auth()->user()->elderlyProfile;

        if (!$profile || !$profile->is_verified) {
            return redirect()->route('user.applications.index')
                ->with('error', 'Your profile must be verified before applying for a pension scheme.');
        }

        // Check if user already has an active/pending application
        $existingApplication = PensionApplication::where('elderly_profile_id', $profile->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingApplication) {
            return redirect()->route('user.applications.index')
                ->with('info', 'You already have an active or pending pension application.');
        }

        // Show only schemes the user is eligible for based on age
        $schemes = PensionScheme::active()
            ->where('eligibility_age', '<=', $profile->age)
            ->get();

        return view('user.applications.create', compact('profile', 'schemes'));
    }

    /**
     * Submit a pension application.
     */
    public function store(Request $request)
    {
        $request->validate([
            'scheme_id' => 'required|exists:pension_schemes,id',
        ]);

        $profile = auth()->user()->elderlyProfile;

        if (!$profile || !$profile->is_verified) {
            return redirect()->route('user.applications.index')
                ->with('error', 'Profile not verified.');
        }

        // Prevent duplicate active applications
        $existing = PensionApplication::where('elderly_profile_id', $profile->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existing) {
            return redirect()->route('user.applications.index')
                ->with('error', 'You already have an active application.');
        }

        PensionApplication::create([
            'elderly_profile_id' => $profile->id,
            'scheme_id'          => $request->scheme_id,
            'status'             => 'pending',
            'applied_at'         => now(),
            'application_number' => PensionApplication::generateApplicationNumber(),
        ]);

        return redirect()->route('user.applications.index')
            ->with('success', '🎉 Application submitted! Your application is under review.');
    }
}
