<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OneID Pension System') | Government of India</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#1a237e; --accent:#f9a825; }
        body { font-family:'Inter',sans-serif; margin:0; min-height:100vh;
               background: linear-gradient(135deg, #1a237e 0%, #0d1642 50%, #1a237e 100%); }
        .auth-wrapper { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
        .auth-card { background:#fff; border-radius:20px; padding:40px; width:100%; max-width:480px; box-shadow:0 20px 60px rgba(0,0,0,.3); }
        .brand-logo { width:56px; height:56px; background:var(--primary); border-radius:14px;
            display:flex; align-items:center; justify-content:center; margin:0 auto 14px; font-size:24px; color:var(--accent); }
        .auth-title { font-size:22px; font-weight:800; color:var(--primary); text-align:center; margin-bottom:4px; }
        .auth-subtitle { font-size:12px; color:#6b7280; text-align:center; margin-bottom:22px; }
        .gov-badge { background:linear-gradient(135deg,var(--primary),#283593); color:#fff; font-size:10px;
            font-weight:700; text-align:center; padding:9px; letter-spacing:.5px; text-transform:uppercase;
            border-radius:10px; margin-bottom:22px; }
        .gov-badge span { color:var(--accent); }
        .form-control,.form-select { border-radius:8px; border:1.5px solid #e0e4ef; font-size:13.5px; padding:10px 14px; }
        .form-control:focus,.form-select:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,35,126,.1); }
        .form-label { font-size:13px; font-weight:600; color:#374151; }
        .btn-login { background:linear-gradient(135deg,var(--primary),#283593); color:#fff; border:none;
            width:100%; padding:13px; border-radius:10px; font-size:15px; font-weight:700; transition:all .2s; cursor:pointer; }
        .btn-login:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(26,35,126,.4); color:#fff; }
        .divider { border:none; border-top:1px solid #f0f2f7; margin:18px 0; }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="brand-logo"><i class="fas fa-landmark"></i></div>
        <h1 class="auth-title">OneID Pension System</h1>
        <p class="auth-subtitle">Unified Elderly Citizen Identification & Pension Portal</p>
        <div class="gov-badge">🇮🇳 <span>Government of India</span> — Ministry of Social Justice & Empowerment</div>
        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
