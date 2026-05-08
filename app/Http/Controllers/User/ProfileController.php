<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ElderlyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the profile (create or view).
     */
    public function index()
    {
        $user    = auth()->user();
        $profile = $user->elderlyProfile;
        return view('user.profile.index', compact('user', 'profile'));
    }

    /**
     * Show the profile creation form.
     */
    public function create()
    {
        $user    = auth()->user();
        if ($user->elderlyProfile) {
            return redirect()->route('user.profile.index');
        }
        return view('user.profile.create');
    }

    /**
     * Store a new elderly profile with auto-generated OneID.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name'          => 'required|string|max:255',
            'age'                => 'required|integer|min:55|max:120',
            'gender'             => 'required|in:male,female,other',
            'address'            => 'required|string|max:1000',
            'phone'              => 'required|string|max:15|regex:/^[0-9+\-\s]+$/',
            'aadhaar_number'     => 'required|string|size:12|unique:elderly_profiles,aadhaar_number|regex:/^[0-9]{12}$/',
            'bank_account_number'=> 'required|string|max:20',
            'bank_name'          => 'required|string|max:100',
            'ifsc_code'          => 'required|string|max:11|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'profile_photo'      => 'nullable|image|max:2048',
            'government_id_photo'=> 'nullable|image|max:2048',
        ]);

        $data = $request->except(['profile_photo', 'government_id_photo']);
        $data['user_id'] = auth()->id();
        $data['one_id']  = ElderlyProfile::generateOneId();

        // Handle file uploads
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')
                ->store('profile_photos', 'public');
        }

        if ($request->hasFile('government_id_photo')) {
            $data['government_id_photo'] = $request->file('government_id_photo')
                ->store('government_ids', 'public');
        }

        ElderlyProfile::create($data);

        return redirect()->route('user.profile.index')
            ->with('success', '🎉 Profile created! Your OneID has been generated. Awaiting admin verification.');
    }

    /**
     * Update an existing profile.
     */
    public function update(Request $request)
    {
        $profile = auth()->user()->elderlyProfile;

        $request->validate([
            'address'       => 'required|string|max:1000',
            'phone'         => 'required|string|max:15',
            'bank_name'     => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:20',
            'ifsc_code'     => 'required|string|max:11',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['address', 'phone', 'bank_name', 'bank_account_number', 'ifsc_code']);

        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($profile->profile_photo) {
                Storage::disk('public')->delete($profile->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $profile->update($data);

        return redirect()->route('user.profile.index')
            ->with('success', 'Profile updated successfully.');
    }
}
