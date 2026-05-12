<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationDocument;
use App\Models\FraudAlert;
use App\Models\Notification;
use App\Models\PensionApplication;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /** List all applications with filters. */
    public function index(Request $request)
    {
        $query = PensionApplication::with(['elderlyProfile.user', 'scheme'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%$search%")
                  ->orWhereHas('elderlyProfile', fn($q) =>
                      $q->where('one_id', 'like', "%$search%")
                        ->orWhere('full_name', 'like', "%$search%")
                        ->orWhere('aadhaar_number', 'like', "%$search%")
                  );
            });
        }

        if ($request->filled('fraud')) {
            $query->where('fraud_flagged', true);
        }

        $applications = $query->paginate(15);

        return view('admin.applications.index', compact('applications'));
    }

    /** Show a single application with documents and fraud info. */
    public function show(PensionApplication $application)
    {
        $application->load([
            'elderlyProfile.user', 'elderlyProfile.fraudAlerts',
            'scheme', 'reviewedBy', 'payments', 'documents.reviewedBy',
        ]);
        return view('admin.applications.show', compact('application'));
    }

    /** Approve or reject a pension application. */
    public function review(Request $request, PensionApplication $application)
    {
        $request->validate([
            'action'  => 'required|in:approve,reject',
            'remarks' => 'nullable|string|max:500',
        ]);

        $status = $request->action === 'approve' ? 'approved' : 'rejected';

        $application->update([
            'status'      => $status,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'remarks'     => $request->remarks,
        ]);

        if ($status === 'approved') {
            $title   = '🎉 Pension Application Approved!';
            $message = "Your application ({$application->application_number}) for '{$application->scheme->name}' has been APPROVED. Monthly amount: ₹" . number_format($application->scheme->monthly_amount, 2);
            $type    = 'success';
        } else {
            $title   = '❌ Pension Application Rejected';
            $message = "Your application ({$application->application_number}) was rejected. Reason: " . ($request->remarks ?? 'Not specified');
            $type    = 'danger';
        }

        Notification::create([
            'user_id' => $application->elderlyProfile->user_id,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'link'    => route('user.applications.index'),
        ]);

        ActivityLogger::log(
            "application.{$status}",
            "Application {$application->application_number} was {$status} by admin",
            $application
        );

        return redirect()->back()->with('success', 'Application reviewed successfully.');
    }

    /** Approve or reject a single uploaded document. */
    public function reviewDocument(Request $request, ApplicationDocument $document)
    {
        $request->validate([
            'action'       => 'required|in:approved,rejected',
            'admin_remarks'=> 'nullable|string|max:500',
        ]);

        $document->update([
            'status'        => $request->action,
            'admin_remarks' => $request->admin_remarks,
            'reviewed_by'   => auth()->id(),
            'reviewed_at'   => now(),
        ]);

        ActivityLogger::log(
            'document.reviewed',
            "Document '{$document->document_type}' for application {$document->application->application_number} was {$request->action}",
            $document
        );

        return redirect()->back()->with('success', 'Document review saved.');
    }
}
