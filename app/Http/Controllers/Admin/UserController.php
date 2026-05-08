<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElderlyProfile;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * List all elderly users with search/filter.
     */
    public function index(Request $request)
    {
        $query = User::with('elderlyProfile')
            ->where('role', 'user');

        // Search by name, email, or OneID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhereHas('elderlyProfile', function ($q) use ($search) {
                      $q->where('one_id', 'like', "%$search%")
                        ->orWhere('aadhaar_number', 'like', "%$search%");
                  });
            });
        }

        // Filter by verification status
        if ($request->filled('verified')) {
            $query->whereHas('elderlyProfile', function ($q) use ($request) {
                $q->where('is_verified', $request->verified);
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show a single user's full profile.
     */
    public function show(User $user)
    {
        $user->load(['elderlyProfile.pensionApplications.scheme', 'elderlyProfile.pensionApplications.payments']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Verify (approve) an elderly citizen's profile.
     */
    public function verify(Request $request, ElderlyProfile $profile)
    {
        $request->validate([
            'action'  => 'required|in:approve,reject',
            'remarks' => 'nullable|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $profile->update([
                'is_verified'            => true,
                'verified_at'            => now(),
                'verified_by'            => auth()->id(),
                'verification_remarks'   => $request->remarks,
            ]);

            $title   = '✅ Profile Verified';
            $message = 'Your profile has been verified successfully. You can now apply for pension schemes.';
            $type    = 'success';
        } else {
            $profile->update([
                'is_verified'          => false,
                'verification_remarks' => $request->remarks,
            ]);

            $title   = '❌ Profile Verification Rejected';
            $message = 'Your profile verification was rejected. Reason: ' . ($request->remarks ?? 'Not specified');
            $type    = 'danger';
        }

        // Send notification to user
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
     * Search by OneID.
     */
    public function search(Request $request)
    {
        $request->validate(['one_id' => 'required|string']);

        $profile = ElderlyProfile::with(['user', 'pensionApplications.scheme'])
            ->where('one_id', $request->one_id)
            ->first();

        return view('admin.users.search', compact('profile'));
    }
}
