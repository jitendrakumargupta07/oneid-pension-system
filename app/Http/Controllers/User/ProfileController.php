<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ElderlyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /** Show the profile (view page). */
    public function index()
    {
        $user    = auth()->user();
        $profile = $user->elderlyProfile;
        return view('user.profile.index', compact('user', 'profile'));
    }

    /** Show the profile creation form. */
    public function create()
    {
        $user = auth()->user();
        if ($user->elderlyProfile) {
            return redirect()->route('user.profile.index');
        }
        return view('user.profile.create');
    }

    /** Store a new elderly profile. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Personal
            'full_name'            => 'required|string|max:255',
            'date_of_birth'        => 'required|date|before:today',
            'age'                  => 'required|integer|min:18|max:120',
            'gender'               => 'required|in:male,female,other',
            'phone'                => 'required|string|max:15|regex:/^[0-9+\-\s]{7,15}$/',
            'aadhaar_number'       => 'required|string|size:12|unique:elderly_profiles,aadhaar_number|regex:/^[0-9]{12}$/',
            'address'              => 'required|string|max:1000',
            'marital_status'       => 'required|in:unmarried,married,widowed,divorced',
            'caste_category'       => 'required|in:general,obc,sc,st',

            // Eligibility factors
            'employment_status'    => 'required|in:unemployed,retired,retired_govt,farmer,self_employed',
            'income_level'         => 'required|numeric|min:0|max:10000000',
            'disability_percentage'=> 'nullable|integer|min:0|max:100',
            'is_widow'             => 'sometimes|boolean',

            // Bank
            'bank_account_number'  => 'required|string|max:20',
            'bank_name'            => 'required|string|max:100',
            'ifsc_code'            => 'required|string|max:11|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',

            // Documents
            'profile_photo'        => 'nullable|image|max:2048',
            'government_id_photo'  => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'aadhaar_number.size'   => 'Aadhaar number must be exactly 12 digits.',
            'aadhaar_number.unique' => 'This Aadhaar number is already registered in the system.',
            'ifsc_code.regex'       => 'IFSC code format is invalid. Example: SBIN0001234',
            'phone.regex'           => 'Enter a valid phone number (7–15 digits).',
        ]);

        // Derive is_widow from marital status
        $validated['is_widow'] = ($validated['marital_status'] === 'widowed');

        // disability_percentage defaults to 0 if checkbox not ticked
        if (! $request->boolean('has_disability')) {
            $validated['disability_percentage'] = 0;
        }

        $validated['user_id'] = auth()->id();
        $validated['one_id']  = ElderlyProfile::generateOneId();

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        if ($request->hasFile('government_id_photo')) {
            $validated['government_id_photo'] = $request->file('government_id_photo')->store('government_ids', 'public');
        }

        ElderlyProfile::create($validated);

        return redirect()->route('user.profile.index')
            ->with('success', '🎉 Profile created! Your OneID has been generated. An administrator will verify your profile soon.');
    }

    /** Update an existing profile (limited fields — immutable KYC fields stay unchanged). */
    public function update(Request $request)
    {
        $profile = auth()->user()->elderlyProfile;

        $validated = $request->validate([
            'address'              => 'required|string|max:1000',
            'phone'                => 'required|string|max:15',
            'bank_name'            => 'required|string|max:100',
            'bank_account_number'  => 'required|string|max:20',
            'ifsc_code'            => 'required|string|max:11|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'employment_status'    => 'required|in:unemployed,retired,retired_govt,farmer,self_employed',
            'income_level'         => 'required|numeric|min:0',
            'disability_percentage'=> 'nullable|integer|min:0|max:100',
            'profile_photo'        => 'nullable|image|max:2048',
        ]);

        if (! $request->boolean('has_disability')) {
            $validated['disability_percentage'] = 0;
        }

        if ($request->hasFile('profile_photo')) {
            if ($profile->profile_photo) {
                Storage::disk('public')->delete($profile->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $profile->update($validated);

        return redirect()->route('user.profile.index')
            ->with('success', 'Profile updated successfully. If eligibility data changed, re-verification may be required.');
    }
}
