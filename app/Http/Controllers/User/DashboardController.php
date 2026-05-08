<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PensionApplication;
use App\Models\PensionPayment;

class DashboardController extends Controller
{
    /**
     * User dashboard — profile status, application, recent payments.
     */
    public function index()
    {
        $user    = auth()->user();
        $profile = $user->elderlyProfile;

        $activeApplication = null;
        $recentPayments    = collect();
        $totalReceived     = 0;

        if ($profile) {
            $activeApplication = PensionApplication::with('scheme')
                ->where('elderly_profile_id', $profile->id)
                ->where('status', 'approved')
                ->first();

            if ($activeApplication) {
                $recentPayments = PensionPayment::where('pension_application_id', $activeApplication->id)
                    ->latest('payment_date')
                    ->take(5)
                    ->get();

                $totalReceived = PensionPayment::where('pension_application_id', $activeApplication->id)
                    ->where('status', 'paid')
                    ->sum('amount');
            }
        }

        $notifications = $user->notifications()->take(5)->get();

        return view('user.dashboard', compact(
            'user', 'profile', 'activeApplication',
            'recentPayments', 'totalReceived', 'notifications'
        ));
    }
}
