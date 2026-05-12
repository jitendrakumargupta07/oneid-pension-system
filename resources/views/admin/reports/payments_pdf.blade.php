<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a2e; margin: 20px; }
        .header { background: #1a237e; color: white; padding: 15px; text-align: center; margin-bottom: 20px; border-radius: 4px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 4px 0 0; font-size: 10px; opacity: .85; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 9px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #1a237e; color: white; padding: 7px 8px; text-align: left; font-size: 9px; letter-spacing: .5px; }
        td { padding: 6px 8px; border-bottom: 1px solid #e9ecef; font-size: 9px; }
        tr:nth-child(even) td { background: #f8f9fa; }
        .total-row td { background: #e8eaf6; font-weight: bold; font-size: 10px; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; }
        .badge-paid { background: #d4edda; color: #155724; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .footer { text-align: center; font-size: 8px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏛️ OneID Pension System — Government of India</h1>
        <p>Monthly Payment Disbursement Report — {{ $monthName }} {{ $request->year }}</p>
    </div>
    <div class="meta">
        <span>Generated: {{ now()->format('d M Y, h:i A') }}</span>
        <span>Total Payments: {{ $payments->count() }}</span>
        <span>Total Amount: ₹{{ number_format($total, 2) }}</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Receipt No.</th>
                <th>Citizen Name</th>
                <th>OneID</th>
                <th>Scheme</th>
                <th>Bank Account</th>
                <th>Amount (₹)</th>
                <th>Payment Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $i => $pay)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $pay->receipt_number }}</td>
                <td>{{ $pay->application?->elderlyProfile?->full_name }}</td>
                <td>{{ $pay->application?->elderlyProfile?->one_id }}</td>
                <td>{{ $pay->application?->scheme?->name }}</td>
                <td>{{ $pay->application?->elderlyProfile?->bank_account_number }}</td>
                <td>{{ number_format($pay->amount, 2) }}</td>
                <td>{{ $pay->payment_date?->format('d M Y') }}</td>
                <td><span class="badge badge-{{ $pay->status }}">{{ strtoupper($pay->status) }}</span></td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" style="text-align:right">TOTAL DISBURSED</td>
                <td colspan="3">₹{{ number_format($total, 2) }}</td>
            </tr>
        </tbody>
    </table>
    <div class="footer">
        This is a computer-generated report. No signature required. | OneID Pension System — Ministry of Social Justice & Empowerment
    </div>
</body>
</html>
