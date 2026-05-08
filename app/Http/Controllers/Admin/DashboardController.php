<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElderlyProfile;
use App\Models\PensionApplication;
use App\Models\PensionPayment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin dashboard with statistics and charts.
     */
    public function index()
    {
        // Summary statistics
        $stats = [
            'total_users'           => User::where('role', 'user')->count(),
            'verified_citizens'     => ElderlyProfile::verified()->count(),
            'pending_verifications' => ElderlyProfile::pending()->count(),
            'total_applications'    => PensionApplication::count(),
            'approved_applications' => PensionApplication::where('status', 'approved')->count(),
            'pending_applications'  => PensionApplication::where('status', 'pending')->count(),
            'rejected_applications' => PensionApplication::where('status', 'rejected')->count(),
            'total_disbursed'       => PensionPayment::where('status', 'paid')->sum('amount'),
            'this_month_disbursed'  => PensionPayment::where('status', 'paid')
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
        ];

        // Chart: Applications per month (last 6 months)
        $monthlyApplications = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyApplications[] = [
                'month' => $date->format('M Y'),
                'count' => PensionApplication::whereMonth('applied_at', $date->month)
                    ->whereYear('applied_at', $date->year)
                    ->count(),
            ];
        }

        // Chart: Payment status distribution
        $paymentStats = [
            'paid'    => PensionPayment::where('status', 'paid')->count(),
            'pending' => PensionPayment::where('status', 'pending')->count(),
            'failed'  => PensionPayment::where('status', 'failed')->count(),
        ];

        // Recent applications
        $recentApplications = PensionApplication::with(['elderlyProfile', 'scheme'])
            ->latest()
            ->take(5)
            ->get();

        // Pending verifications
        $pendingVerifications = ElderlyProfile::with('user')
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'monthlyApplications', 'paymentStats',
            'recentApplications', 'pendingVerifications'
        ));
    }
}
