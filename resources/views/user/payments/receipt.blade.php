<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Pension Receipt - {{ $payment->receipt_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            background: #fff;
            color: #1a1f36;
            font-size: 12px;
        }

        .page {
            padding: 30px 40px;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: #1a237e;
            color: #fff;
            padding: 20px 28px;
            border-radius: 0;
            margin-bottom: 0;
        }

        .header-inner {
            display: table;
            width: 100%;
        }

        .header-left { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }

        .gov-title {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .gov-sub {
            font-size: 10px;
            opacity: 0.7;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .receipt-badge {
            background: #f9a825;
            color: #1a237e;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Receipt Number Banner */
        .receipt-banner {
            background: #f0f2ff;
            border-left: 5px solid #1a237e;
            padding: 12px 20px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }

        .receipt-banner-left { display: table-cell; vertical-align: middle; }
        .receipt-banner-right { display: table-cell; text-align: right; vertical-align: middle; }

        .receipt-no-label { font-size: 9px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; }
        .receipt-no-val { font-size: 16px; font-weight: bold; color: #1a237e; font-family: Courier New, monospace; letter-spacing: 2px; }

        .receipt-date { font-size: 11px; color: #374151; }
        .receipt-status-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }

        /* Section cards */
        .section-card {
            border: 1px solid #e8eaf6;
            border-radius: 8px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .section-header {
            background: #f5f6fe;
            padding: 8px 16px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1a237e;
            border-bottom: 1px solid #e8eaf6;
        }

        .section-body { padding: 14px 16px; }

        /* Info rows inside cards */
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 5px 0; font-size: 12px; }
        .info-table .label { color: #6b7280; width: 45%; }
        .info-table .value { color: #1a1f36; font-weight: 600; }

        /* Amount highlight */
        .amount-box {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: #fff;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .amount-label { font-size: 10px; opacity: 0.7; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .amount-val { font-size: 32px; font-weight: bold; }
        .amount-scheme { font-size: 12px; opacity: 0.75; margin-top: 4px; }

        /* Footer */
        .footer {
            margin-top: 24px;
            border-top: 2px dashed #e8eaf6;
            padding-top: 16px;
            display: table;
            width: 100%;
        }

        .footer-left { display: table-cell; vertical-align: bottom; }
        .footer-right { display: table-cell; text-align: right; vertical-align: bottom; }

        .signature-line { border-top: 1px solid #1a237e; width: 120px; margin-top: 28px; }
        .signature-label { font-size: 9px; color: #6b7280; margin-top: 4px; }

        .notice {
            font-size: 9px;
            color: #9ca3af;
            line-height: 1.5;
            margin-top: 12px;
            text-align: center;
            border-top: 1px solid #f0f0f7;
            padding-top: 10px;
        }

        .watermark {
            font-size: 10px;
            color: #9ca3af;
            text-align: center;
            margin-top: 8px;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Government Header --}}
    <div class="header">
        <div class="header-inner">
            <div class="header-left">
                <div class="gov-title">🏛 OneID Pension System</div>
                <div class="gov-sub">Ministry of Social Justice & Empowerment | Government of India</div>
            </div>
            <div class="header-right">
                <span class="receipt-badge">Official Receipt</span>
            </div>
        </div>
    </div>

    {{-- Receipt Number Banner --}}
    <div class="receipt-banner">
        <div class="receipt-banner-left">
            <div class="receipt-no-label">Receipt Number</div>
            <div class="receipt-no-val">{{ $payment->receipt_number }}</div>
            <div class="receipt-date">Payment Date: {{ $payment->payment_date?->format('d F Y') }}</div>
        </div>
        <div class="receipt-banner-right">
            <span class="receipt-status-badge">✓ PAID</span>
        </div>
    </div>

    {{-- Amount Box --}}
    <div class="amount-box">
        <div class="amount-label">Pension Amount Disbursed</div>
        <div class="amount-val">₹{{ number_format($payment->amount, 2) }}</div>
        <div class="amount-scheme">
            {{ $payment->application?->scheme?->name }} &bull;
            {{ date('F', mktime(0,0,0,$payment->month,1)) }} {{ $payment->year }}
        </div>
    </div>

    {{-- Citizen Info --}}
    <div class="section-card">
        <div class="section-header">Beneficiary Information</div>
        <div class="section-body">
            <table class="info-table">
                <tr>
                    <td class="label">Full Name</td>
                    <td class="value">{{ $payment->application?->elderlyProfile?->full_name }}</td>
                    <td class="label">OneID</td>
                    <td class="value" style="font-family:Courier New,monospace;">{{ $payment->application?->elderlyProfile?->one_id }}</td>
                </tr>
                <tr>
                    <td class="label">Age</td>
                    <td class="value">{{ $payment->application?->elderlyProfile?->age }} years</td>
                    <td class="label">Gender</td>
                    <td class="value">{{ ucfirst($payment->application?->elderlyProfile?->gender) }}</td>
                </tr>
                <tr>
                    <td class="label">Phone</td>
                    <td class="value">{{ $payment->application?->elderlyProfile?->phone }}</td>
                    <td class="label">Registered</td>
                    <td class="value">{{ $payment->application?->elderlyProfile?->created_at?->format('d M Y') }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Bank & Payment Info --}}
    <div style="display:table;width:100%;margin-bottom:16px;">
        <div style="display:table-cell;width:50%;padding-right:8px;">
            <div class="section-card" style="margin-bottom:0;">
                <div class="section-header">Bank Account Details</div>
                <div class="section-body">
                    <table class="info-table">
                        <tr>
                            <td class="label">Bank Name</td>
                            <td class="value">{{ $payment->application?->elderlyProfile?->bank_name }}</td>
                        </tr>
                        <tr>
                            <td class="label">Account No.</td>
                            <td class="value" style="font-family:Courier New,monospace;">
                                XXXX{{ substr($payment->application?->elderlyProfile?->bank_account_number, -4) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">IFSC Code</td>
                            <td class="value" style="font-family:Courier New,monospace;">{{ $payment->application?->elderlyProfile?->ifsc_code }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div style="display:table-cell;width:50%;padding-left:8px;">
            <div class="section-card" style="margin-bottom:0;">
                <div class="section-header">Payment Details</div>
                <div class="section-body">
                    <table class="info-table">
                        <tr>
                            <td class="label">Application No.</td>
                            <td class="value" style="font-size:11px;font-family:Courier New,monospace;">{{ $payment->application?->application_number }}</td>
                        </tr>
                        <tr>
                            <td class="label">Payment For</td>
                            <td class="value">{{ date('F', mktime(0,0,0,$payment->month,1)) }} {{ $payment->year }}</td>
                        </tr>
                        <tr>
                            <td class="label">Transaction Ref.</td>
                            <td class="value" style="font-family:Courier New,monospace;font-size:11px;">{{ $payment->transaction_ref ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer with signature --}}
    <div class="footer">
        <div class="footer-left">
            <div style="font-size:10px;color:#6b7280;line-height:1.6;">
                Generated on: {{ now()->format('d M Y, H:i:s') }}<br>
                System: OneID Pension Management System
            </div>
        </div>
        <div class="footer-right">
            <div class="signature-line" style="float:right;"></div>
            <div class="signature-label" style="text-align:center;">Authorised Signatory</div>
            <div class="signature-label" style="text-align:center;">Pension Disbursement Officer</div>
        </div>
    </div>

    <div class="notice">
        This is a computer-generated receipt and does not require a physical signature. This document is valid as official proof of pension payment.
        For queries, contact: support@oneid.gov.in | Helpline: 1800-XXX-XXXX
    </div>
    <div class="watermark">OneID Pension System &copy; {{ date('Y') }} | Government of India</div>
</div>
</body>
</html>
