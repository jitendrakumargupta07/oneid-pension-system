<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PensionApplication;
use App\Models\PensionPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Reports index page.
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Generate PDF report of all payments in a given month/year.
     */
    public function paymentsPdf(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year'  => 'required|integer|between:2000,2100',
        ]);

        $payments = PensionPayment::with(['application.elderlyProfile', 'application.scheme'])
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->orderBy('payment_date')
            ->get();

        $monthName = date('F', mktime(0, 0, 0, $request->month, 1));
        $total     = $payments->sum('amount');

        $pdf = Pdf::loadView('admin.reports.payments_pdf', compact('payments', 'monthName', 'total', 'request'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("payments_{$monthName}_{$request->year}.pdf");
    }

    /**
     * Generate PDF summary of all pension applications.
     */
    public function applicationsPdf(Request $request)
    {
        $applications = PensionApplication::with(['elderlyProfile', 'scheme'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->get();

        $pdf = Pdf::loadView('admin.reports.applications_pdf', compact('applications'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('pension_applications_report.pdf');
    }
}
