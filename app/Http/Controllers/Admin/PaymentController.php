<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\PensionApplication;
use App\Models\PensionPayment;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /** List all payments. */
    public function index(Request $request)
    {
        $query = PensionPayment::with(['application.elderlyProfile', 'application.scheme'])
            ->latest('payment_date');

        if ($request->filled('month') && $request->filled('year')) {
            $query->where('month', $request->month)->where('year', $request->year);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('application.elderlyProfile', fn($q) =>
                $q->where('full_name', 'like', "%$search%")
                  ->orWhere('one_id', 'like', "%$search%")
            );
        }

        $payments  = $query->paginate(15);
        $totalPaid = PensionPayment::where('status', 'paid')->sum('amount');
        $thisMonth = PensionPayment::where('status', 'paid')
            ->where('month', now()->month)->where('year', now()->year)->sum('amount');

        return view('admin.payments.index', compact('payments', 'totalPaid', 'thisMonth'));
    }

    /**
     * Show form to add payment.
     * Uses GET param ?application_id=X to pre-select an application.
     */
    public function create(Request $request)
    {
        $applications = PensionApplication::with(['elderlyProfile', 'scheme'])
            ->where('status', 'approved')
            ->get();

        $selectedApplication = null;
        if ($request->filled('application_id')) {
            $selectedApplication = $applications->firstWhere('id', $request->application_id);
        }

        return view('admin.payments.create', compact('applications', 'selectedApplication'));
    }

    /** Store a new payment. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pension_application_id' => 'required|exists:pension_applications,id',
            'amount'                 => 'required|numeric|min:1',
            'payment_date'           => 'required|date',
            'month'                  => 'required|integer|between:1,12',
            'year'                   => 'required|integer|between:2000,2100',
            'transaction_ref'        => 'nullable|string|max:100',
            'remarks'                => 'nullable|string|max:500',
            'status'                 => 'required|in:paid,pending,failed',
        ]);

        // Check for duplicate payment (same app + month + year)
        $exists = PensionPayment::where('pension_application_id', $validated['pension_application_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['month' => 'A payment for this application already exists for ' . date('F', mktime(0,0,0,$validated['month'],1)) . ' ' . $validated['year'] . '.']);
        }

        $payment = PensionPayment::create([
            ...$validated,
            'receipt_number' => PensionPayment::generateReceiptNumber(),
        ]);

        $application = PensionApplication::with('elderlyProfile', 'scheme')->find($validated['pension_application_id']);
        $monthName   = date('F', mktime(0, 0, 0, $validated['month'], 1));

        Notification::create([
            'user_id' => $application->elderlyProfile->user_id,
            'title'   => '💰 Pension Payment Credited',
            'message' => "Your pension of ₹" . number_format($validated['amount'], 2) . " for {$monthName} {$validated['year']} has been processed. Receipt: {$payment->receipt_number}",
            'type'    => 'success',
            'link'    => route('user.payments.index'),
        ]);

        ActivityLogger::log(
            'payment.recorded',
            "Payment of ₹{$validated['amount']} recorded for {$application->elderlyProfile->full_name} ({$monthName} {$validated['year']})",
            $payment
        );

        return redirect()->route('admin.payments.index')
            ->with('success', "✅ Payment of ₹" . number_format($validated['amount'], 2) . " recorded. Receipt: {$payment->receipt_number}");
    }
}
