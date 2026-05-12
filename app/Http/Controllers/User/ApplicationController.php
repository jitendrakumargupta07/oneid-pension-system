<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ApplicationDocument;
use App\Models\Notification;
use App\Models\PensionApplication;
use App\Models\PensionScheme;
use App\Services\EligibilityChecker;
use App\Services\FraudDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function __construct(
        private EligibilityChecker $eligibility,
        private FraudDetector $fraud,
    ) {}

    /** User's application list. */
    public function index()
    {
        $profile      = auth()->user()->elderlyProfile;
        $applications = collect();

        if ($profile) {
            $applications = PensionApplication::with(['scheme', 'documents'])
                ->where('elderly_profile_id', $profile->id)
                ->latest()
                ->get();
        }

        return view('user.applications.index', compact('profile', 'applications'));
    }

    /** Show available schemes with eligibility check results. */
    public function create()
    {
        $profile = auth()->user()->elderlyProfile;

        if (!$profile || !$profile->is_verified) {
            return redirect()->route('user.applications.index')
                ->with('error', 'Your profile must be verified before applying for a pension scheme.');
        }

        $existingApplication = PensionApplication::where('elderly_profile_id', $profile->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingApplication) {
            return redirect()->route('user.applications.index')
                ->with('info', 'You already have an active or pending pension application.');
        }

        $schemes = PensionScheme::active()->get();

        // Run eligibility check per scheme
        $eligibilityResults = [];
        foreach ($schemes as $scheme) {
            $eligibilityResults[$scheme->id] = $this->eligibility->check($profile, $scheme);
        }

        return view('user.applications.create', compact('profile', 'schemes', 'eligibilityResults'));
    }

    /** Submit pension application with documents. */
    public function store(Request $request)
    {
        $request->validate([
            'scheme_id'   => 'required|exists:pension_schemes,id',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $profile = auth()->user()->elderlyProfile;

        if (!$profile || !$profile->is_verified) {
            return redirect()->route('user.applications.index')->with('error', 'Profile not verified.');
        }

        $existing = PensionApplication::where('elderly_profile_id', $profile->id)
            ->whereIn('status', ['pending', 'approved'])->exists();

        if ($existing) {
            return redirect()->route('user.applications.index')->with('error', 'You already have an active application.');
        }

        $scheme = PensionScheme::findOrFail($request->scheme_id);

        // Run eligibility check
        $eligResult = $this->eligibility->check($profile, $scheme);

        // Run fraud detection
        $fraudAlerts = $this->fraud->scan($profile);
        $fraudFlagged = !empty($fraudAlerts);

        $application = PensionApplication::create([
            'elderly_profile_id' => $profile->id,
            'scheme_id'          => $scheme->id,
            'status'             => 'pending',
            'applied_at'         => now(),
            'application_number' => PensionApplication::generateApplicationNumber(),
            'fraud_flagged'      => $fraudFlagged,
        ]);

        // Store uploaded documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $type => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store("documents/{$application->id}", 'public');
                    ApplicationDocument::create([
                        'pension_application_id' => $application->id,
                        'document_type'          => $type,
                        'file_path'              => $path,
                        'original_name'          => $file->getClientOriginalName(),
                        'status'                 => 'pending',
                    ]);
                }
            }
        }

        // Notify user
        Notification::create([
            'user_id' => auth()->id(),
            'title'   => '📋 Application Submitted',
            'message' => "Your pension application ({$application->application_number}) for '{$scheme->name}' has been submitted and is under review.",
            'type'    => 'info',
            'link'    => route('user.applications.index'),
        ]);

        $msg = $fraudFlagged
            ? '⚠️ Application submitted. Fraud checks are pending admin review.'
            : '🎉 Application submitted successfully! It is under review.';

        return redirect()->route('user.applications.index')->with('success', $msg);
    }
}
