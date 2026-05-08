<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\PensionApplication;
use App\Models\PensionPayment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * List all payments.
     */
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

        $payments   = $query->paginate(15);
        $totalPaid  = PensionPayment::where('status', 'paid')->sum('amount');

        return view('admin.payments.index', compact('payments', 'totalPaid'));
    }

    /**
     * Record a new payment for an approved application.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pension_application_id' => 'required|exists:pension_applications,id',
            'amount'                 => 'required|numeric|min:1',
            'payment_date'           => 'required|date',
            'month'                  => 'required|integer|min:1|max:12',
            'year'                   => 'required|integer|min:2000|max:2100',
            'transaction_ref'        => 'nullable|string|max:100',
            'remarks'                => 'nullable|string|max:500',
            'status'                 => 'required|in:paid,pending,failed',
        ]);

        $payment = PensionPayment::create([
            ...$request->all(),
            'receipt_number' => PensionPayment::generateReceiptNumber(),
        ]);

        // Notify user
        $application = PensionApplication::with('elderlyProfile', 'scheme')->find($request->pension_application_id);
        $monthName   = date('F', mktime(0, 0, 0, $request->month, 1));

        Notification::create([
            'user_id' => $application->elderlyProfile->user_id,
            'title'   => '💰 Pension Payment Credited',
            'message' => "Your pension payment of ₹" . number_format($request->amount, 2) . " for $monthName {$request->year} has been processed. Receipt: {$payment->receipt_number}",
            'type'    => 'success',
            'link'    => route('user.payments.index'),
        ]);

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }

    /**
     * Show form to add payment for an application.
     */
    public function create(Request $request)
    {
        $applications = PensionApplication::with(['elderlyProfile', 'scheme'])
            ->where('status', 'approved')
            ->get();

        $selectedApplication = null;
        if ($request->filled('application_id')) {
            $selectedApplication = PensionApplication::with(['elderlyProfile', 'scheme'])
                ->find($request->application_id);
        }

        return view('admin.payments.create', compact('applications', 'selectedApplication'));
    }
}
