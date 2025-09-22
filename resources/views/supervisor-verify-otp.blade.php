<!DOCTYPE html>
<html>
<head>
<title>Verify OTP</title>
</head>
<body>
<div class="container" style="max-width:420px;margin:40px auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.05)">
    <h2 style="margin:0 0 16px;">Verify OTP</h2>

    @if(session('success'))
        <div style="background:#d1fae5;color:#065f46;padding:10px;border-radius:6px;margin-bottom:12px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2;color:#991b1b;padding:10px;border-radius:6px;margin-bottom:12px;">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('supervisor.otp.verify') }}">
        @csrf
        <div style="margin-bottom:12px;">
            <label>Enter 6-digit code</label>
            <input type="text" name="otp" maxlength="6" pattern="\d{6}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;letter-spacing:4px;text-align:center;font-size:18px;">
        </div>
        <button type="submit" style="width:100%;padding:10px 14px;border:none;background:#38c172;color:#fff;border-radius:6px;font-weight:600;">Verify</button>
    </form>
    <p style="margin-top:10px;color:#6b7280;">Code expires in 2 minutes.</p>
</div>
</body>
</html>





