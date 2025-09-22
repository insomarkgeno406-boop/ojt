<!DOCTYPE html>
<html>
<head>
    <title>Supervisor Registration</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }
        .container { max-width: 400px; margin: 80px auto; background: #fff; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); padding: 40px; }
        h2 { text-align: center; margin-bottom: 30px; }
        .error { color: red; margin-bottom: 15px; text-align: center; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; }
        button { width: 100%; background: #f59e42; color: #fff; padding: 10px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; }
        button:hover { background: #d97706; }
        .note { color: #666; font-size: 13px; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Supervisor Registration</h2>
        <div class="note">Your account must be approved by the admin before you can log in.</div>
        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('supervisor.register.submit') }}">
            @csrf
            <input type="text" name="name" placeholder="Name" required value="{{ old('name') }}">
            <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <div class="note" style="margin-top:20px;">
            Already have an account? <a href="{{ route('supervisor.login') }}">Login here</a>
        </div>
    </div>
</body>
</html> 