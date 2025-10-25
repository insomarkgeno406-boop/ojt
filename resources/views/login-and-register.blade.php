<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login & Register - OJT Management System</title>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            transition: filter 0.3s ease;
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
            transition: filter 0.3s ease;
        }

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

        .form-container form {
            display: none;
            flex-direction: column;
        }

        .form-container form.active {
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

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Blur ONLY the background and container when SweetAlert is active */
        body.swal2-shown::before {
            filter: blur(8px);
        }

        body.swal2-shown .container {
            filter: blur(8px);
        }

        /* Ensure SweetAlert popup is completely unaffected by blur */
        .swal2-container {
            filter: none !important;
            backdrop-filter: none !important;
        }

        .swal2-popup {
            filter: none !important;
            backdrop-filter: none !important;
        }

        /* Make SweetAlert stand out */
        .swal2-popup {
            transform: scale(1.05) !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4) !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
        }

        /* Make the backdrop darker for better focus */
        .swal2-backdrop-show {
            background-color: rgba(0, 0, 0, 0.6) !important;
        }

        /* Add subtle animation to SweetAlert */
        .swal2-show {
            animation: sweetAlertFocus 0.4s ease-out !important;
        }

        @keyframes sweetAlertFocus {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: scale(1.05) translateY(0);
            }
        }

        /* Terms Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            overflow-y: auto;
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            margin: 5% auto;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            color: #333;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .modal-title {
            font-size: 24px;
            font-weight: bold;
            color: #1d3557;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #000;
        }

        .modal-body {
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .modal-body h3 {
            color: #1d3557;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .modal-body ul {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 15px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #457b9d, #1d3557);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d3557, #457b9d);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        /* Forgot Password Styles */
        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-password-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: rgba(255, 255, 255, 0.8);
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .forgot-password-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Terms Checkbox */
        .terms-checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
        }

        .terms-checkbox input[type="checkbox"] {
            margin-right: 8px;
            transform: scale(1.1);
        }

        .terms-checkbox a {
            color: #60a5fa;
            text-decoration: none;
            margin-left: 5px;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }

        /* Security Indicator */
        .security-indicator {
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #22c55e;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            margin-bottom: 15px;
            text-align: center;
        }

        .security-indicator i {
            margin-right: 5px;
        }

        /* Modal Input Styles - Force override all other styles */
        .modal input[type="email"],
        #resetEmail {
            width: 100% !important;
            padding: 12px !important;
            margin-bottom: 15px !important;
            border: 2px solid #ddd !important;
            border-radius: 6px !important;
            font-size: 14px !important;
            background: white !important;
            color: #333 !important;
            box-sizing: border-box !important;
            transition: border-color 0.3s ease !important;
            display: block !important;
            position: relative !important;
            z-index: 1 !important;
            opacity: 1 !important;
            pointer-events: auto !important;
            cursor: text !important;
        }

        .modal input[type="email"]:focus,
        #resetEmail:focus {
            outline: none !important;
            border-color: #457b9d !important;
            box-shadow: 0 0 0 3px rgba(69, 123, 157, 0.1) !important;
        }

        .modal input[type="email"]::placeholder,
        #resetEmail::placeholder {
            color: #999 !important;
        }

        .modal .security-indicator {
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #22c55e;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Ensure modal is above everything */
        #forgotPasswordModal {
            z-index: 99999 !important;
        }

        #forgotPasswordModal .modal-content {
            z-index: 99999 !important;
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
        <form id="login" class="active" method="POST" action="{{ route('login') }}">
            @csrf
            
            <input type="email" name="email" placeholder="Email" required autocomplete="email">
            <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
            <button class="submit" type="submit">Login</button>
            <div class="forgot-password">
                <button type="button" class="forgot-password-btn" onclick="showForgotPassword()">Forgot Password?</button>
            </div>
        </form>

        {{-- Register Form --}}
        <form id="register" method="POST" action="{{ route('register') }}">
            @csrf
            
            <input type="text" name="name" placeholder="Full Name" required autocomplete="name">
            <input type="email" name="email" placeholder="Email Address" required autocomplete="email">
            <input type="password" name="password" placeholder="Password (Min 8 chars)" required autocomplete="new-password" minlength="8">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
            
            <div class="terms-checkbox">
                <input type="checkbox" id="terms_agreement" name="terms_agreement" required>
                <label for="terms_agreement">
                    I agree to the <a href="#" onclick="showTermsModal()">Terms of Service</a>
                </label>
            </div>
            
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

<!-- OTP Verification Modal -->
<div id="otpModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Verify Your Email</h2>
            <span class="close" onclick="closeOtpModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>We sent a 6-digit code to <b id="otpEmailLabel"></b>. Enter it below to verify.</p>
            <form id="otpForm" method="POST" action="{{ route('otp.verify.submit') }}">
                @csrf
                <input type="hidden" name="email" id="otpEmail" value="">
                <input 
                    type="text" 
                    name="otp" 
                    id="otpInput" 
                    maxlength="6" 
                    pattern="\d{6}"
                    required 
                    placeholder="Enter 6-digit code" 
                    inputmode="numeric"
                    style="width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid #ddd; border-radius: 6px; font-size: 18px; background: white; color: #333; box-sizing: border-box; letter-spacing: 6px; text-align: center;"
                >
                <div class="security-indicator">
                    <i>🔒</i> Code expires in 10 minutes
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="resendOtp()">Resend Code</button>
            <button class="btn btn-primary" onclick="submitOtp()">Verify</button>
        </div>
    </div>
    
</div>

<!-- Terms of Service Modal -->
<div id="termsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Terms of Service</h2>
            <span class="close" onclick="closeTermsModal()">&times;</span>
        </div>
        <div class="modal-body">
            <h3>1. Acceptance of Terms</h3>
            <p>By accessing and using this OJT (On-the-Job Training) Management System, you accept and agree to be bound by the terms and provision of this agreement.</p>
            
            <h3>2. System Usage</h3>
            <p>This system is designed for educational and training purposes. Users are expected to:</p>
            <ul>
                <li>Use the system responsibly and ethically</li>
                <li>Maintain the confidentiality of their login credentials</li>
                <li>Report any security concerns or vulnerabilities</li>
                <li>Comply with all applicable laws and regulations</li>
            </ul>
            
            <h3>3. Data Protection and Privacy</h3>
            <p>Your personal information is protected using industry-standard security measures:</p>
            <ul>
                <li>Passwords are encrypted using Argon2id algorithm</li>
                <li>All data transmissions are secured with HTTPS</li>
                <li>Account lockout protection against brute force attacks</li>
                <li>Regular security audits and updates</li>
            </ul>
            
            <h3>4. Prohibited Activities</h3>
            <p>The following activities are strictly prohibited:</p>
            <ul>
                <li>Attempting to gain unauthorized access to the system</li>
                <li>Sharing login credentials with others</li>
                <li>Attempting to circumvent security measures</li>
                <li>Uploading malicious content or code</li>
                <li>Violating any applicable laws or regulations</li>
            </ul>
            
            <h3>5. System Availability</h3>
            <p>While we strive to maintain system availability, we cannot guarantee uninterrupted service. The system may be temporarily unavailable for maintenance, updates, or due to unforeseen circumstances.</p>
            
            <h3>6. Account Security</h3>
            <p>Users are responsible for:</p>
            <ul>
                <li>Choosing strong, unique passwords</li>
                <li>Keeping their account information up to date</li>
                <li>Immediately reporting any suspicious activity</li>
                <li>Logging out when finished using the system</li>
            </ul>
            
            <h3>7. Limitation of Liability</h3>
            <p>The system is provided "as is" without warranties of any kind. We shall not be liable for any direct, indirect, incidental, or consequential damages arising from the use of this system.</p>
            
            <h3>8. Changes to Terms</h3>
            <p>We reserve the right to modify these terms at any time. Users will be notified of significant changes, and continued use of the system constitutes acceptance of the modified terms.</p>
            
            <h3>9. Contact Information</h3>
            <p>For questions regarding these terms or the system, please contact the system administrator.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeTermsModal()">Close</button>
            <button class="btn btn-primary" onclick="acceptTerms()">I Accept Terms</button>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div id="forgotPasswordModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Reset Password</h2>
            <span class="close" onclick="closeForgotPasswordModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Enter your email address and we'll send you an OTP code to reset your password.</p>
            <form id="forgotPasswordForm">
                <input 
                    type="email" 
                    name="email" 
                    id="resetEmail" 
                    placeholder="Enter your email address" 
                    required 
                    autocomplete="email"
                    style="width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: white; color: #333; box-sizing: border-box;"
                >
                <div class="security-indicator">
                    <i>🔒</i> OTP codes are valid for 10 minutes and can only be used once
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeForgotPasswordModal()">Cancel</button>
            <button class="btn btn-primary" onclick="sendForgotPasswordOtp()">Send OTP</button>
        </div>
    </div>
</div>

<!-- Forgot Password OTP Verification Modal -->
<div id="forgotPasswordOtpModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Verify OTP</h2>
            <span class="close" onclick="closeForgotPasswordOtpModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>We sent a 6-digit code to <b id="forgotPasswordOtpEmailLabel"></b>. Enter it below to continue.</p>
            <form id="forgotPasswordOtpForm">
                <input type="hidden" name="email" id="forgotPasswordOtpEmail" value="">
                <input 
                    type="text" 
                    name="otp" 
                    id="forgotPasswordOtpInput" 
                    maxlength="6" 
                    pattern="\d{6}"
                    required 
                    placeholder="Enter 6-digit code" 
                    inputmode="numeric"
                    style="width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid #ddd; border-radius: 6px; font-size: 18px; background: white; color: #333; box-sizing: border-box; letter-spacing: 6px; text-align: center;"
                >
                <div class="security-indicator">
                    <i>🔒</i> Code expires in 10 minutes
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="resendForgotPasswordOtp()">Resend Code</button>
            <button class="btn btn-primary" onclick="verifyForgotPasswordOtp()">Verify</button>
        </div>
    </div>
</div>

<!-- New Password Modal -->
<div id="newPasswordModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Set New Password</h2>
            <span class="close" onclick="closeNewPasswordModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Please enter your new password below.</p>
            <form id="newPasswordForm" method="POST" action="{{ route('password.reset.submit') }}">
                @csrf
                <input type="hidden" name="email" id="newPasswordEmail" value="">
                <input type="hidden" name="otp" id="newPasswordOtp" value="">
                
                <div class="form-group">
                    <label>New Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="newPassword" 
                        placeholder="Enter new password (Min 8 characters)" 
                        required 
                        minlength="8"
                        autocomplete="new-password"
                        style="width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: white; color: #333; box-sizing: border-box;"
                    >
                </div>
                
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="newPasswordConfirmation" 
                        placeholder="Confirm new password" 
                        required 
                        minlength="8"
                        autocomplete="new-password"
                        style="width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: white; color: #333; box-sizing: border-box;"
                    >
                </div>
                
                <div class="security-indicator">
                    <i>🔒</i> Password will be encrypted using Argon2id algorithm
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeNewPasswordModal()">Cancel</button>
            <button class="btn btn-primary" onclick="submitNewPassword()">Reset Password</button>
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

    // Terms Modal Functions
    function showTermsModal() {
        document.getElementById('termsModal').style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeTermsModal() {
        document.getElementById('termsModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function acceptTerms() {
        document.getElementById('terms_agreement').checked = true;
        closeTermsModal();
        Swal.fire({
            title: 'Terms Accepted!',
            text: 'You have accepted the Terms of Service.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Forgot Password Modal Functions
    function showForgotPassword() {
        const modal = document.getElementById('forgotPasswordModal');
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Force focus the email input after modal is fully visible
        setTimeout(() => {
            const emailInput = document.getElementById('resetEmail');
            if (emailInput) {
                emailInput.focus();
                emailInput.click(); // Force click to ensure it's active
                console.log('Email input focused and clicked');
            }
        }, 200);
        
        console.log('Forgot password modal opened');
    }

    function closeForgotPasswordModal() {
        document.getElementById('forgotPasswordModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // OTP Modal Functions
    function showOtpModal(email) {
        const modal = document.getElementById('otpModal');
        document.getElementById('otpEmail').value = email;
        document.getElementById('otpEmailLabel').textContent = email;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('otpInput').focus(), 150);
    }

    function closeOtpModal() {
        document.getElementById('otpModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function submitOtp() {
        const form = document.getElementById('otpForm');
        const input = document.getElementById('otpInput');
        const digits = (input.value || '').replace(/\D/g, '');
        if (digits.length !== 6) {
            Swal.fire('Invalid Code', 'Please enter the 6-digit code.', 'warning');
            input.focus();
            return;
        }
        input.value = digits;
        form.submit();
    }

    function resendOtp() {
        const email = document.getElementById('otpEmail').value;
        fetch('{{ route('otp.resend') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ email })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                Swal.fire('Sent', 'A new code has been emailed to you.', 'success');
                // In non-production, backend includes otp_fallback
                if (data.otp_fallback) {
                    const input = document.getElementById('otpInput');
                    input.value = data.otp_fallback;
                }
            } else {
                // Handle email sending failure with fallback
                if (data.otp_fallback) {
                    const input = document.getElementById('otpInput');
                    input.value = data.otp_fallback;
                    Swal.fire({
                        title: 'Email Sending Issue',
                        html: 'We could not send the email. Use this code:<br><b style="font-size: 24px; color: #1d3557;">' + data.otp_fallback + '</b><br><br><small>This code is valid for 10 minutes.</small>',
                        icon: 'warning',
                        confirmButtonText: 'Got it!',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to resend code.', 'error');
                }
            }
        }).catch(() => Swal.fire('Error', 'Failed to resend code.', 'error'));
    }

    // Forgot Password Functions
    function sendForgotPasswordOtp() {
        const email = document.getElementById('resetEmail').value;
        
        if (!email.trim()) {
            Swal.fire('Email Required', 'Please enter your email address.', 'warning');
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            Swal.fire('Invalid Email', 'Please enter a valid email address.', 'warning');
            return;
        }

        // Immediately transition to OTP modal
        closeForgotPasswordModal();
        showForgotPasswordOtpModal(email);

        // Send OTP request in background
        fetch('{{ route('password.forgot') }}', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
            },
            body: JSON.stringify({ email })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                Swal.fire('OTP Sent', 'A verification code has been sent to your email.', 'success');
                if (data.otp_fallback) {
                    setTimeout(() => {
                        const input = document.getElementById('forgotPasswordOtpInput');
                        input.value = data.otp_fallback;
                    }, 300);
                }
            } else {
                // Handle email sending failure with fallback
                if (data.otp_fallback) {
                    setTimeout(() => {
                        const input = document.getElementById('forgotPasswordOtpInput');
                        input.value = data.otp_fallback;
                    }, 300);
                    Swal.fire({
                        title: 'Email Sending Issue',
                        html: 'We could not send the email. Use this code:<br><b style="font-size: 24px; color: #1d3557;">' + data.otp_fallback + '</b><br><br><small>This code is valid for 10 minutes.</small>',
                        icon: 'warning',
                        confirmButtonText: 'Got it!',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
            } else {
                // If OTP sending failed, go back to email modal
                closeForgotPasswordOtpModal();
                showForgotPasswordModal();
                Swal.fire('Error', data.message || 'Failed to send OTP.', 'error');
                }
            }
        }).catch(() => {
            // If OTP sending failed, go back to email modal
            closeForgotPasswordOtpModal();
            showForgotPasswordModal();
            Swal.fire('Error', 'Failed to send OTP.', 'error');
        });
    }

    function showForgotPasswordModal() {
        const modal = document.getElementById('forgotPasswordModal');
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('resetEmail').focus(), 150);
    }

    function showForgotPasswordOtpModal(email) {
        const modal = document.getElementById('forgotPasswordOtpModal');
        document.getElementById('forgotPasswordOtpEmail').value = email;
        document.getElementById('forgotPasswordOtpEmailLabel').textContent = email;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('forgotPasswordOtpInput').focus(), 150);
    }

    function closeForgotPasswordOtpModal() {
        document.getElementById('forgotPasswordOtpModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function verifyForgotPasswordOtp() {
        const email = document.getElementById('forgotPasswordOtpEmail').value;
        const otp = document.getElementById('forgotPasswordOtpInput').value;
        
        if (!otp || otp.length !== 6) {
            Swal.fire('Invalid Code', 'Please enter the 6-digit code.', 'warning');
            return;
        }

        fetch('{{ route('password.verify-otp') }}', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
            },
            body: JSON.stringify({ email, otp })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                closeForgotPasswordOtpModal();
                showNewPasswordModal(email, otp);
                Swal.fire('OTP Verified', 'Please set your new password.', 'success');
            } else {
                Swal.fire('Error', data.message || 'Invalid OTP code.', 'error');
            }
        }).catch(() => Swal.fire('Error', 'Failed to verify OTP.', 'error'));
    }

    function resendForgotPasswordOtp() {
        const email = document.getElementById('forgotPasswordOtpEmail').value;
        
        fetch('{{ route('password.resend-otp') }}', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
            },
            body: JSON.stringify({ email })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                Swal.fire('Sent', 'A new code has been emailed to you.', 'success');
                if (data.otp_fallback) {
                    const input = document.getElementById('forgotPasswordOtpInput');
                    input.value = data.otp_fallback;
                }
            } else {
                // Handle email sending failure with fallback
                if (data.otp_fallback) {
                    const input = document.getElementById('forgotPasswordOtpInput');
                    input.value = data.otp_fallback;
                    Swal.fire({
                        title: 'Email Sending Issue',
                        html: 'We could not send the email. Use this code:<br><b style="font-size: 24px; color: #1d3557;">' + data.otp_fallback + '</b><br><br><small>This code is valid for 10 minutes.</small>',
                        icon: 'warning',
                        confirmButtonText: 'Got it!',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
            } else {
                Swal.fire('Error', data.message || 'Failed to resend code.', 'error');
                }
            }
        }).catch(() => Swal.fire('Error', 'Failed to resend code.', 'error'));
    }

    function showNewPasswordModal(email, otp) {
        const modal = document.getElementById('newPasswordModal');
        document.getElementById('newPasswordEmail').value = email;
        document.getElementById('newPasswordOtp').value = otp;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('newPassword').focus(), 150);
    }

    function closeNewPasswordModal() {
        document.getElementById('newPasswordModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function submitNewPassword() {
        const form = document.getElementById('newPasswordForm');
        const password = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('newPasswordConfirmation').value;
        
        if (!password || password.length < 8) {
            Swal.fire('Invalid Password', 'Password must be at least 8 characters long.', 'warning');
            return;
        }
        
        if (password !== confirmPassword) {
            Swal.fire('Password Mismatch', 'Passwords do not match.', 'warning');
            return;
        }

        form.submit();
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const termsModal = document.getElementById('termsModal');
        const forgotPasswordModal = document.getElementById('forgotPasswordModal');
        const forgotPasswordOtpModal = document.getElementById('forgotPasswordOtpModal');
        const newPasswordModal = document.getElementById('newPasswordModal');
        const otpModal = document.getElementById('otpModal');
        
        if (event.target === termsModal) {
            closeTermsModal();
        }
        if (event.target === forgotPasswordModal) {
            closeForgotPasswordModal();
        }
        if (event.target === forgotPasswordOtpModal) {
            closeForgotPasswordOtpModal();
        }
        if (event.target === newPasswordModal) {
            closeNewPasswordModal();
        }
        if (event.target === otpModal) {
            closeOtpModal();
        }
    }

    // Password strength validation removed for better user experience

    // Real-time password strength validation removed
    document.addEventListener('DOMContentLoaded', function() {

        // Add Enter key support and debugging for forgot password email input
        const resetEmailInput = document.getElementById('resetEmail');
        if (resetEmailInput) {
            // Debug: Log when input is clicked or focused
            resetEmailInput.addEventListener('click', function() {
                console.log('Email input clicked');
            });
            
            resetEmailInput.addEventListener('focus', function() {
                console.log('Email input focused');
            });
            
            resetEmailInput.addEventListener('input', function() {
                console.log('Email input changed:', this.value);
            });
            
            resetEmailInput.addEventListener('keypress', function(e) {
                console.log('Key pressed:', e.key);
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitForgotPassword();
                }
            });
            
            // Force enable the input
            resetEmailInput.disabled = false;
            resetEmailInput.readOnly = false;
            resetEmailInput.style.pointerEvents = 'auto';
            resetEmailInput.style.opacity = '1';
            
            console.log('Email input event listeners added');
        }

        // Form validation enhancements
        const registerForm = document.getElementById('register');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                const termsChecked = document.getElementById('terms_agreement').checked;
                if (!termsChecked) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Terms Required!',
                        text: 'You must accept the Terms of Service to continue.',
                        icon: 'warning',
                        confirmButtonText: 'Accept Terms',
                        showCancelButton: true,
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showTermsModal();
                        }
                    });
                    return false;
                }

                const password = registerForm.querySelector('input[name="password"]').value;
                const confirmPassword = registerForm.querySelector('input[name="password_confirmation"]').value;
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Password Mismatch!',
                        text: 'Passwords do not match.',
                        icon: 'error'
                    });
                    return false;
                }

                // Password strength validation removed
            });
        }
    });

    // Lockout functionality with persistent timer
    let failedAttempts = parseInt(localStorage.getItem('failedAttempts')) || 0;
    let lockoutEndTime = parseInt(localStorage.getItem('lockoutEndTime')) || 0;
    let backgroundTimerInterval = null;

    function getRemainingTime() {
        const now = Date.now();
        if (lockoutEndTime > now) {
            const remainingSeconds = Math.ceil((lockoutEndTime - now) / 1000);
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            return { 
                locked: true, 
                timeLeft: `${minutes}:${seconds.toString().padStart(2, '0')}`,
                remainingSeconds: remainingSeconds
            };
        } else if (lockoutEndTime > 0) {
            // Lockout expired, reset
            localStorage.removeItem('failedAttempts');
            localStorage.removeItem('lockoutEndTime');
            failedAttempts = 0;
            lockoutEndTime = 0;
            enableAllButtons();
            stopBackgroundTimer();
        }
        return { locked: false, timeLeft: '0:00', remainingSeconds: 0 };
    }

    function disableAllButtons() {
        document.querySelectorAll('button, input[type="submit"]').forEach(btn => {
            btn.disabled = true;
            btn.style.opacity = '0.5';
            btn.style.cursor = 'not-allowed';
        });
    }

    function enableAllButtons() {
        document.querySelectorAll('button, input[type="submit"]').forEach(btn => {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
        });
    }

    function startBackgroundTimer() {
        // Clear any existing timer
        if (backgroundTimerInterval) {
            clearInterval(backgroundTimerInterval);
        }
        
        // Start continuous background timer
        backgroundTimerInterval = setInterval(() => {
            const status = getRemainingTime();
            if (!status.locked) {
                stopBackgroundTimer();
                enableAllButtons();
            }
        }, 1000);
    }

    function stopBackgroundTimer() {
        if (backgroundTimerInterval) {
            clearInterval(backgroundTimerInterval);
            backgroundTimerInterval = null;
        }
    }

    // SweetAlert configuration for all messages
    document.addEventListener('DOMContentLoaded', function() {
        // Check lockout on page load and start persistent timer if needed
        const lockoutStatus = getRemainingTime();
        if (lockoutStatus.locked) {
            disableAllButtons();
            startBackgroundTimer(); // Start the persistent background timer
            
            Swal.fire({
                title: 'System Locked!',
                html: `Too many failed attempts.<br>Please wait <b>${lockoutStatus.timeLeft}</b> before trying again.`,
                icon: 'warning',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                didOpen: () => {
                    const content = Swal.getHtmlContainer();
                    const b = content.querySelector('b');
                    
                    const timerInterval = setInterval(() => {
                        const status = getRemainingTime();
                        if (status.locked) {
                            b.textContent = status.timeLeft;
                        } else {
                            clearInterval(timerInterval);
                            Swal.close();
                        }
                    }, 1000);
                }
            });
        }

        // Check for session error message
        @if(session('error'))
            failedAttempts++;
            localStorage.setItem('failedAttempts', failedAttempts);
            
            const attemptsLeft = 3 - failedAttempts;
            
            if (failedAttempts >= 3) {
                // Lockout for 5 minutes
                lockoutEndTime = Date.now() + (5 * 60 * 1000);
                localStorage.setItem('lockoutEndTime', lockoutEndTime);
                disableAllButtons();
                startBackgroundTimer(); // Start persistent timer
                
                Swal.fire({
                    title: 'Account Locked!',
                    html: 'Too many failed attempts.<br>Please wait <b>5:00</b> before trying again.',
                    icon: 'error',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    didOpen: () => {
                        const content = Swal.getHtmlContainer();
                        const b = content.querySelector('b');
                        
                        const timerInterval = setInterval(() => {
                            const status = getRemainingTime();
                            if (status.locked) {
                                b.textContent = status.timeLeft;
                            } else {
                                clearInterval(timerInterval);
                                Swal.close();
                            }
                        }, 1000);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    html: '{{ session('error') }}<br><br><b style="color: #fbbf24;">You only have ' + attemptsLeft + ' attempt' + (attemptsLeft > 1 ? 's' : '') + ' left!</b>',
                    icon: 'error',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    backdrop: 'rgba(0,0,0,0.7)',
                    focusConfirm: true,
                    allowEscapeKey: false
                });
            }
        @endif

        // Check for validation errors
        @if ($errors->any())
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += '{{ $error }}\n';
            @endforeach
            
            Swal.fire({
                title: 'Validation Error!',
                text: errorMessages.trim(),
                icon: 'error',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                backdrop: 'rgba(0,0,0,0.7)',
                focusConfirm: true,
                allowEscapeKey: false
            });
        @endif

        // Check for success message
        @if(session('success'))
            // Reset failed attempts on successful login
            localStorage.removeItem('failedAttempts');
            localStorage.removeItem('lockoutEndTime');
            failedAttempts = 0;
            lockoutEndTime = 0;
            stopBackgroundTimer();
            
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                backdrop: 'rgba(0,0,0,0.7)',
                focusConfirm: true,
                allowEscapeKey: false
            });
        @endif

        // Open OTP modal automatically after registration or login prompt
        @if(session('otp_email'))
            showOtpModal('{{ session('otp_email') }}');
            @if(session('otp_code_fallback'))
                // Fallback: show OTP if email could not be sent
                setTimeout(() => {
                    const input = document.getElementById('otpInput');
                    input.value = '{{ session('otp_code_fallback') }}';
                }, 300);
                Swal.fire({
                    title: 'Email Sending Issue',
                    html: 'We could not send the email. Use this code:<br><b style="font-size: 24px; color: #1d3557;">{{ session('otp_code_fallback') }}</b><br><br><small>This code is valid for 10 minutes.</small>',
                    icon: 'warning',
                    confirmButtonText: 'Got it!',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
            @endif
        @endif
    });

    // Ensure timer continues even when user navigates away and comes back
    window.addEventListener('beforeunload', function() {
        // Timer data is already saved in localStorage, so it persists
        // No need to do anything here - the timer will continue counting down
    });

    // Handle visibility change (tab switching, minimizing browser, etc.)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Page became visible again, check if lockout expired
            const status = getRemainingTime();
            if (!status.locked && lockoutEndTime > 0) {
                // Lockout expired while user was away
                localStorage.removeItem('failedAttempts');
                localStorage.removeItem('lockoutEndTime');
                failedAttempts = 0;
                lockoutEndTime = 0;
                enableAllButtons();
                stopBackgroundTimer();
            }
        }
    });

    // Debug function to test email input - call this from browser console
    window.testEmailInput = function() {
        const emailInput = document.getElementById('resetEmail');
        if (emailInput) {
            console.log('Email input found:', emailInput);
            console.log('Input disabled:', emailInput.disabled);
            console.log('Input readonly:', emailInput.readOnly);
            console.log('Input style:', emailInput.style.cssText);
            
            // Try to set a test value
            emailInput.value = 'test@example.com';
            console.log('Test value set:', emailInput.value);
            
            // Try to focus
            emailInput.focus();
            console.log('Input focused');
        } else {
            console.log('Email input not found!');
        }
    };
</script>

</body>
</html>