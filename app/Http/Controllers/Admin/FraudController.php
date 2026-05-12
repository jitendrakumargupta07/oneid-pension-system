<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FraudAlert;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class FraudController extends Controller
{
    /**
     * List all fraud alerts with filters.
     */
    public function index(Request $request)
    {
        $query = FraudAlert::with(['elderlyProfile.user'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('type')) {
            $query->where('alert_type', $request->type);
        }

        $alerts = $query->paginate(20);

        $stats = [
            'open'     => FraudAlert::where('status', 'open')->count(),
            'high'     => FraudAlert::where('status', 'open')->where('severity', 'high')->count(),
            'medium'   => FraudAlert::where('status', 'open')->where('severity', 'medium')->count(),
            'resolved' => FraudAlert::where('status', 'resolved')->count(),
        ];

        return view('admin.fraud.index', compact('alerts', 'stats'));
    }

    /**
     * Resolve or dismiss a fraud alert.
     */
    public function resolve(Request $request, FraudAlert $alert)
    {
        $request->validate([
            'action'           => 'required|in:resolved,dismissed',
            'resolution_notes' => 'nullable|string|max:500',
        ]);

        $alert->update([
            'status'           => $request->action,
            'resolved_by'      => auth()->id(),
            'resolved_at'      => now(),
            'resolution_notes' => $request->resolution_notes,
        ]);

        $label = $request->action === 'resolved' ? 'Resolved' : 'Dismissed';

        ActivityLogger::log(
            'fraud.resolved',
            "{$label} fraud alert #{$alert->id} ({$alert->alert_type}) for citizen {$alert->elderlyProfile?->one_id}",
            $alert
        );

        return redirect()->back()->with('success', "Fraud alert {$label} successfully.");
    }
}
