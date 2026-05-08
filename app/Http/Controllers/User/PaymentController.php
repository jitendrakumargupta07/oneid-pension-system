<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PensionApplication;
use App\Models\PensionPayment;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    /**
     * Show payment history for user.
     */
    public function index()
    {
        $profile     = auth()->user()->elderlyProfile;
        $payments    = collect();
        $application = null;

        if ($profile) {
            $application = PensionApplication::with('scheme')
                ->where('elderly_profile_id', $profile->id)
                ->where('status', 'approved')
                ->first();

            if ($application) {
                $payments = PensionPayment::where('pension_application_id', $application->id)
                    ->latest('payment_date')
                    ->paginate(12);
            }
        }

        return view('user.payments.index', compact('profile', 'application', 'payments'));
    }

    /**
     * Download a PDF receipt for a specific payment.
     */
    public function receipt(PensionPayment $payment)
    {
        // Security: ensure this payment belongs to the logged-in user
        $profile = auth()->user()->elderlyProfile;

        if (!$profile || $payment->application->elderly_profile_id !== $profile->id) {
            abort(403, 'You are not authorized to view this receipt.');
        }

        $payment->load('application.elderlyProfile.user', 'application.scheme');

        $pdf = Pdf::loadView('user.payments.receipt', compact('payment'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("pension_receipt_{$payment->receipt_number}.pdf");
    }
}
