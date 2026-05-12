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
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; }
        .badge-approved { background: #d4edda; color: #155724; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-rejected { background: #f8d7da; color: #721c24; }
        .footer { text-align: center; font-size: 8px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏛️ OneID Pension System — Government of India</h1>
        <p>Pension Applications Report — Generated {{ now()->format('d M Y') }}</p>
    </div>
    <div class="meta">
        <span>Total Applications: {{ $applications->count() }}</span>
        <span>Approved: {{ $applications->where('status','approved')->count() }}</span>
        <span>Pending: {{ $applications->where('status','pending')->count() }}</span>
        <span>Rejected: {{ $applications->where('status','rejected')->count() }}</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>App. Number</th>
                <th>Citizen Name</th>
                <th>OneID</th>
                <th>Scheme</th>
                <th>Applied On</th>
                <th>Status</th>
                <th>Reviewed On</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $i => $app)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $app->application_number }}</td>
                <td>{{ $app->elderlyProfile?->full_name }}</td>
                <td>{{ $app->elderlyProfile?->one_id }}</td>
                <td>{{ $app->scheme?->name }}</td>
                <td>{{ $app->applied_at?->format('d M Y') }}</td>
                <td><span class="badge badge-{{ $app->status }}">{{ strtoupper($app->status) }}</span></td>
                <td>{{ $app->reviewed_at?->format('d M Y') ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Confidential Government Document | OneID Pension System — Ministry of Social Justice & Empowerment
    </div>
</body>
</html>
