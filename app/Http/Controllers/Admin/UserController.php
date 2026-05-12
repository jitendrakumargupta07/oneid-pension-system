<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElderlyProfile;
use App\Models\Notification;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /** List all elderly users with search/filter. */
    public function index(Request $request)
    {
        $query = User::with('elderlyProfile')->where('role', 'user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhereHas('elderlyProfile', fn($q) =>
                      $q->where('one_id', 'like', "%$search%")
                        ->orWhere('aadhaar_number', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                  );
            });
        }

        if ($request->filled('verified')) {
            $query->whereHas('elderlyProfile', fn($q) =>
                $q->where('is_verified', $request->verified)
            );
        }

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /** Show a single user's full profile. */
    public function show(User $user)
    {
        $user->load([
            'elderlyProfile.pensionApplications.scheme',
            'elderlyProfile.pensionApplications.payments',
            'elderlyProfile.fraudAlerts',
        ]);
        return view('admin.users.show', compact('user'));
    }

    /** Verify (approve/reject) an elderly citizen's profile. */
    public function verify(Request $request, ElderlyProfile $profile)
    {
        $request->validate([
            'action'  => 'required|in:approve,reject',
            'remarks' => 'nullable|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $profile->update([
                'is_verified'          => true,
                'verified_at'          => now(),
                'verified_by'          => auth()->id(),
                'verification_remarks' => $request->remarks,
            ]);
            $title   = '✅ Profile Verified';
            $message = 'Your profile has been verified. You can now apply for pension schemes.';
            $type    = 'success';
            ActivityLogger::log('profile.verified', "Profile of {$profile->full_name} ({$profile->one_id}) verified", $profile);
        } else {
            $profile->update([
                'is_verified'          => false,
                'verification_remarks' => $request->remarks,
            ]);
            $title   = '❌ Profile Verification Rejected';
            $message = 'Profile verification rejected. Reason: ' . ($request->remarks ?? 'Not specified');
            $type    = 'danger';
            ActivityLogger::log('profile.rejected', "Profile of {$profile->full_name} ({$profile->one_id}) rejected", $profile);
        }

        Notification::create([
            'user_id' => $profile->user_id,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'link'    => route('user.profile.index'),
        ]);

        return redirect()->back()->with('success', 'Profile verification updated successfully.');
    }

    /**
     * Universal search — by OneID, Aadhaar, phone, or name.
     * Supports GET with no query (shows empty form) and with query.
     */
    public function search(Request $request)
    {
        $profile = null;
        $searched = false;

        if ($request->filled('q')) {
            $searched = true;
            $term = trim($request->q);

            $profile = ElderlyProfile::with(['user', 'pensionApplications.scheme', 'pensionApplications.payments', 'fraudAlerts'])
                ->where('one_id', $term)
                ->orWhere('aadhaar_number', $term)
                ->orWhere('phone', $term)
                ->orWhere('full_name', 'like', "%$term%")
                ->first();
        }

        return view('admin.users.search', compact('profile', 'searched'));
    }
}
