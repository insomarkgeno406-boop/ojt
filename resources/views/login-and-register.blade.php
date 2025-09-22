<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Background image with contain size */
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('{{ asset('logo.png') }}') center center no-repeat fixed;
            background-size: contain; /* Keep image size */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Dark overlay */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        /* Card container */
        .container {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            max-width: 440px;
            width: 100%;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            animation: fadeIn 0.6s ease-out;
        }

        /* QR code section removed */

        /* Tab buttons */
        .tab-buttons {
            display: flex;
            justify-content: space-around;
        }

        .tab-buttons button {
            flex: 1;
            padding: 12px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transition: background-color 0.3s ease;
        }

        .tab-buttons button.active {
            background: rgba(0, 123, 255, 0.7);
        }

        /* Forms */
        .form-container {
            padding: 30px 40px;
        }

        form {
            display: none;
            flex-direction: column;
        }

        form.active {
            display: flex;
        }

        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 14px;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .submit {
            background: linear-gradient(135deg, #457b9d, #1d3557);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit:hover {
            background: linear-gradient(135deg, #1d3557, #457b9d);
        }

        /* Error messages */
        .error {
            color: #f87171;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Additional login buttons */
        .intern-link {
            text-align: center;
            margin-top: 20px;
        }

        .intern-link button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            margin-top: 5px;
        }

        .intern-btn { background-color: #38c172; }
        .intern-btn:hover { background-color: #2fa360; }

        /* supervisor button removed */

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Tab Switch Buttons -->
    <div class="tab-buttons">
        <button class="tab-btn active" onclick="switchTab('login')">Login</button>
        <button class="tab-btn" onclick="switchTab('register')">Register</button>
    </div>

    <!-- Forms -->
    <div class="form-container">
        {{-- Login Form --}}
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form id="login" class="active" method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="submit" type="submit">Login</button>
        </form>

        {{-- Register Form --}}
        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form id="register" method="POST" action="{{ route('register') }}">
            @csrf
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            <button class="submit" type="submit">Register</button>
        </form>

        <!-- Intern Login -->
        <div class="intern-link">
            <p>Are you an intern?</p>
            <a href="{{ route('intern.login') }}">
                <button type="button" class="intern-btn">Login as Intern</button>
            </a>
        </div>
        
    </div>
</div>

<script>
    function switchTab(tabId) {
        const tabs = document.querySelectorAll('form');
        const buttons = document.querySelectorAll('.tab-btn');

        tabs.forEach(form => form.classList.remove('active'));
        buttons.forEach(btn => btn.classList.remove('active'));

        document.getElementById(tabId).classList.add('active');
        document.querySelector(`[onclick="switchTab('${tabId}')"]`).classList.add('active');
    }
</script>

</body>
</html>
