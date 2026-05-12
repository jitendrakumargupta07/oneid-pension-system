<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElderlyProfile;
use App\Models\FraudAlert;
use App\Models\PensionApplication;
use App\Models\PensionPayment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Core stats ─────────────────────────────────────────────────
        $stats = [
            'total_citizens'       => ElderlyProfile::count(),
            'verified_citizens'    => ElderlyProfile::verified()->count(),
            'pending_verifications'=> ElderlyProfile::pending()->count(),
            'total_applications'   => PensionApplication::count(),
            'approved'             => PensionApplication::where('status', 'approved')->count(),
            'rejected'             => PensionApplication::where('status', 'rejected')->count(),
            'pending_apps'         => PensionApplication::where('status', 'pending')->count(),
            'fraud_open'           => FraudAlert::where('status', 'open')->count(),
            'fraud_high'           => FraudAlert::where('status', 'open')->where('severity', 'high')->count(),
            'pending_docs'         => \App\Models\ApplicationDocument::where('status', 'pending')->count(),
            'this_month_paid'      => PensionPayment::where('month', now()->month)->where('year', now()->year)->where('status', 'paid')->sum('amount'),
            'total_disbursed'      => PensionPayment::where('status', 'paid')->sum('amount'),
        ];

        // ── Applications over last 6 months (for line chart) ──────────
        $appChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month      = now()->subMonths($i);
            $appChart[] = [
                'label' => $month->format('M Y'),
                'count' => PensionApplication::whereYear('applied_at', $month->year)
                    ->whereMonth('applied_at', $month->month)
                    ->count(),
            ];
        }

        // ── Payment status chart (doughnut) ──────────────────────────
        $paymentChart = [
            'paid'    => PensionPayment::where('status', 'paid')->count(),
            'pending' => PensionPayment::where('status', 'pending')->count(),
            'failed'  => PensionPayment::where('status', 'failed')->count(),
        ];

        // ── Scheme-wise application distribution ─────────────────────
        $schemeChart = PensionApplication::select('scheme_id', DB::raw('count(*) as total'))
            ->with('scheme:id,name')
            ->groupBy('scheme_id')
            ->get()
            ->map(fn($a) => ['label' => $a->scheme?->name ?? 'N/A', 'count' => $a->total]);

        // ── Recent applications ───────────────────────────────────────
        $recentApplications = PensionApplication::with(['elderlyProfile', 'scheme'])
            ->latest()
            ->take(5)
            ->get();

        // ── Open fraud alerts ────────────────────────────────────────
        $fraudAlerts = FraudAlert::with('elderlyProfile')
            ->where('status', 'open')
            ->orderByRaw("FIELD(severity, 'high', 'medium', 'low')")
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'appChart', 'paymentChart', 'schemeChart',
            'recentApplications', 'fraudAlerts'
        ));
    }
}
