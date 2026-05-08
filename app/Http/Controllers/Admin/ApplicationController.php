<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\PensionApplication;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * List all pension applications with filters.
     */
    public function index(Request $request)
    {
        $query = PensionApplication::with(['elderlyProfile.user', 'scheme'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%$search%")
                  ->orWhereHas('elderlyProfile', function ($q) use ($search) {
                      $q->where('one_id', 'like', "%$search%")
                        ->orWhere('full_name', 'like', "%$search%");
                  });
            });
        }

        $applications = $query->paginate(15);

        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Show a single application.
     */
    public function show(PensionApplication $application)
    {
        $application->load(['elderlyProfile.user', 'scheme', 'reviewedBy', 'payments']);
        return view('admin.applications.show', compact('application'));
    }

    /**
     * Approve or reject a pension application.
     */
    public function review(Request $request, PensionApplication $application)
    {
        $request->validate([
            'action'  => 'required|in:approve,reject',
            'remarks' => 'nullable|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $application->update([
                'status'      => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
                'remarks'     => $request->remarks,
            ]);
            $title   = '🎉 Pension Application Approved!';
            $message = "Your pension application ({$application->application_number}) for the '{$application->scheme->name}' scheme has been APPROVED. Monthly amount: ₹" . number_format($application->scheme->monthly_amount, 2);
            $type    = 'success';
        } else {
            $application->update([
                'status'      => 'rejected',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
                'remarks'     => $request->remarks,
            ]);
            $title   = '❌ Pension Application Rejected';
            $message = "Your pension application ({$application->application_number}) has been rejected. Reason: " . ($request->remarks ?? 'Not specified');
            $type    = 'danger';
        }

        // Send notification to user
        Notification::create([
            'user_id' => $application->elderlyProfile->user_id,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'link'    => route('user.applications.index'),
        ]);

        return redirect()->back()->with('success', 'Application reviewed successfully.');
    }
}
