<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intern Login</title>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.min.css">
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Background image with overlay */
        body {
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: url('{{ asset('logo.png') }}') center center no-repeat fixed;
        }

        /* Dark overlay */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 0;
        }

        /* Login card (Glassmorphism) */
        .login-container {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 40px 35px;
            border-radius: 16px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            animation: fadeIn 0.6s ease-out;
            z-index: 1;
            position: relative;
        }

        /* Back button - positioned at top left */
        .back-button {
            position: absolute;
            top: 15px;
            left: 20px;
            color: #f1faee;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            padding: 6px 10px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .back-button:hover {
            color: #a8dadc;
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(-2px);
        }

        /* Back arrow icon */
        .back-button svg {
            width: 16px;
            height: 16px;
            stroke-width: 2;
        }

        /* Title */
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #f1faee;
            font-weight: 700;
            font-size: 26px;
            letter-spacing: 0.5px;
            margin-top: 20px; /* Add some top margin to account for back button */
        }

        /* Error message */
        .error-message {
            color: #f87171;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Input group */
        .form-group {
            position: relative;
            margin-bottom: 18px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 42px 12px 14px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-group input:focus {
            border-color: #a8dadc;
            box-shadow: 0 0 0 3px rgba(168, 218, 220, 0.35);
        }

        /* Icons inside input */
        .form-group svg {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            width: 18px;
            height: 18px;
        }

        /* Submit button */
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #457b9d, #1d3557);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            cursor: pointer;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.25s ease;
        }

        button:hover {
            transform: translateY(-1px);
            background: linear-gradient(135deg, #1d3557, #457b9d);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 25px;
            }
            
            .back-button {
                top: 12px;
                left: 15px;
                font-size: 13px;
                padding: 5px 8px;
            }
            
            h2 {
                font-size: 22px;
                margin-top: 25px;
            }
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            overflow-y: auto;
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            margin: 5% auto;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            top: 15px;
            right: 20px;
        }

        .close:hover {
            color: #000;
        }

        /* Step Indicators */
        .step-indicators {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            gap: 20px;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .step.active {
            background: linear-gradient(135deg, #457b9d, #1d3557);
            color: white;
            transform: scale(1.1);
        }

        .step.completed {
            background: #10b981;
            color: white;
        }

        /* Step Content */
        .step-content {
            display: none;
        }

        .step-content h3 {
            text-align: center;
            color: #1d3557;
            margin-bottom: 15px;
            font-size: 24px;
        }

        .step-description {
            text-align: center;
            color: #64748b;
            margin-bottom: 25px;
            font-size: 16px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1d3557;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: white;
            color: #1d3557;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #457b9d;
            box-shadow: 0 0 0 3px rgba(69, 123, 157, 0.1);
        }

        .file-hint {
            font-size: 12px;
            color: #64748b;
            font-weight: normal;
        }

        .form-actions {
            text-align: center;
            margin-top: 30px;
        }

        .submit-btn {
            background: linear-gradient(135deg, #457b9d, #1d3557);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(69, 123, 157, 0.3);
        }

        /* Responsive Modal */
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 10% auto;
                padding: 20px;
            }
            
            .step-indicators {
                gap: 15px;
            }
            
            .step {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Back button moved to top left -->
        <a href="{{ route('login') }}" class="back-button" onclick="confirmBack(event)">Back</a>


        <h2>Intern Login</h2>

        @if(session('error'))
            <div class="error-message">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('intern.login.submit') }}">
            @csrf
            <div class="form-group">
                <input type="email" name="email" placeholder="Intern Email" required>
                <!-- Email icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 12H8m0 0l4-4m-4 4l4 4" />
                </svg>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
                <!-- Lock icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m0-6a2 2 0 114 0v2H8v-2a2 2 0 114 0z" />
                </svg>
            </div>
            <button type="submit">Login</button>
        </form>

        <!-- Registration Button -->
        <div style="margin-top: 20px; text-align: center;">
            <button type="button" onclick="openRegistrationModal()" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                New Intern? Register Here
            </button>
        </div>
    </div>

    <!-- Registration Modal -->
    <div id="registrationModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeRegistrationModal()">&times;</span>
            
            <!-- Step Indicators -->
            <div class="step-indicators">
                <div class="step active" data-step="1">1</div>
                <div class="step" data-step="2">2</div>
                <div class="step" data-step="3">3</div>
                <div class="step" data-step="4">4</div>
            </div>

            <!-- Step 1: Pre-Enrollment (Initial Registration) -->
            <div class="step-content" id="step1">
                <h3>Pre-Enrollment Phase</h3>
                <p class="step-description">Please provide your basic information to start the registration process:</p>
                
                <form id="registrationForm" method="POST" action="{{ route('intern.store') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Course</label>
                        <input type="text" name="course" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Section</label>
                        <select name="section" required>
                            <option value="">Select Section</option>
                            <option value="North">North</option>
                            <option value="South">South</option>
                            <option value="West">West</option>
                            <option value="East">East</option>
                            <option value="Northeast">Northeast</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Supervisor Name</label>
                        <input type="text" name="supervisor_name" required>
                    </div>
                    <div class="form-group">
                        <label>Supervisor Position</label>
                        <input type="text" name="supervisor_position" placeholder="(Position)">
                    </div>
                    
                    <div class="form-group">
                        <label>Supervisor Email</label>
                        <input type="email" name="supervisor_email" required>
                    </div>
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" name="company_name" placeholder="(Company Name)">
                    </div>
                    <div class="form-group">
                        <label>Company Address</label>
                        <input type="text" name="company_address" placeholder="(Company Address)">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="submit-btn">Submit Pre-Enrollment</button>
                    </div>
                </form>
            </div>

            <!-- Step 2: Pre-Deployment (Hidden initially) -->
            <div class="step-content" id="step2" style="display: none;">
                <h3>Pre-Deployment Phase</h3>
                <p class="step-description">Please submit the following documents:</p>
                
                <form id="preDeploymentForm" method="POST" action="{{ route('intern.submit.pre-deployment') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label>Resume <span class="file-hint">(PDF, DOCX, JPG, PNG - Max 128 MB)</span></label>
                        <input type="file" name="resume" accept=".pdf,.docx,.jpg,.jpeg,.png" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Application Letter <span class="file-hint">(PDF, DOCX, JPG, PNG - Max 128 MB)</span></label>
                        <input type="file" name="application_letter" accept=".pdf,.docx,.jpg,.jpeg,.png" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Medical Certificate <span class="file-hint">(PDF, DOCX, JPG, PNG - Max 128 MB)</span></label>
                        <input type="file" name="medical_certificate" accept=".pdf,.docx,.jpg,.jpeg,.png" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Insurance <span class="file-hint">(PDF, DOCX, JPG, PNG - Max 128 MB)</span></label>
                        <input type="file" name="insurance" accept=".pdf,.docx,.jpg,.jpeg,.png" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Acceptance Letter <span class="file-hint">(PDF, DOCX, JPG, PNG - Max 128 MB)</span></label>
                        <input type="file" name="acceptance_letter" accept=".pdf,.docx,.jpg,.jpeg,.png" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Notarized Parent's Waiver <span class="file-hint">(PDF, DOCX, JPG, PNG - Max 128 MB)</span></label>
                        <input type="file" name="parents_waiver" accept=".pdf,.docx,.jpg,.jpeg,.png" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="submit-btn">Submit Pre-Deployment Documents</button>
                    </div>
                </form>
            </div>

            <!-- Step 3: Mid-Deployment (Hidden initially) -->
            <div class="step-content" id="step3" style="display: none;">
                <h3>Mid-Deployment Phase</h3>
                <p class="step-description">Please submit the following documents:</p>
                
                <form id="midDeploymentForm" method="POST" action="{{ route('intern.submit.mid-deployment') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label>Memorandum of Agreement <span class="file-hint">(PDF, DOCX, JPG, PNG - Max 128 MB)</span></label>
                        <input type="file" name="memorandum_of_agreement" accept=".pdf,.docx,.jpg,.jpeg,.png" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Internship Contract <span class="file-hint">(PDF, DOCX, JPG, PNG - Max 128 MB)</span></label>
                        <input type="file" name="internship_contract" accept=".pdf,.docx,.jpg,.jpeg,.png" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="submit-btn">Submit Mid-Deployment Documents</button>
                    </div>
                </form>
            </div>

            <!-- Step 4: Deployment (Hidden initially) -->
            <div class="step-content" id="step4" style="display: none;">
                <h3>Deployment Phase</h3>
                <p class="step-description">Please submit the following document:</p>
                
                <form id="deploymentForm" method="POST" action="{{ route('intern.submit.deployment') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label>Recommendation Letter <span class="file-hint">(PDF, DOCX, JPG, PNG - Max 128 MB)</span></label>
                        <input type="file" name="recommendation_letter" accept=".pdf,.docx,.jpg,.jpeg,.png" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="submit-btn">Submit Deployment Documents</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmBack(event) {
            event.preventDefault(); // Prevent default link behavior
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to go back to the main login page?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#457b9d',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, go back',
                cancelButtonText: 'No, stay here',
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.6)',
                customClass: {
                    popup: 'swal-popup-custom',
                    title: 'swal-title-custom',
                    content: 'swal-content-custom'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to main login page
                    window.location.href = '{{ route("login") }}';
                }
            });
        }

        function openRegistrationModal() {
            document.getElementById('registrationModal').style.display = 'block';
            // Reset step indicators and content
            document.querySelectorAll('.step-indicators .step').forEach(step => step.classList.remove('active'));
            document.querySelectorAll('.step-content').forEach(content => content.style.display = 'none');
            document.getElementById('step1').style.display = 'block';
            document.querySelector('.step-indicators .step:nth-child(1)').classList.add('active');
        }

        function closeRegistrationModal() {
            document.getElementById('registrationModal').style.display = 'none';
        }

        // Step navigation
        document.addEventListener('DOMContentLoaded', function() {
            const stepIndicators = document.querySelectorAll('.step-indicators .step');
            const stepContents = document.querySelectorAll('.step-content');
            let currentStep = 0;

            function showStep(stepIndex) {
                stepIndicators.forEach(indicator => indicator.classList.remove('active'));
                stepIndicators[stepIndex].classList.add('active');
                stepContents.forEach(content => content.style.display = 'none');
                stepContents[stepIndex].style.display = 'block';
                currentStep = stepIndex;
            }

            stepIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    if (index < currentStep) {
                        showStep(index);
                    } else if (index > currentStep) {
                        // Logic to handle next step (e.g., form submission)
                        if (currentStep === 0) {
                            // Pre-enrollment form submission
                            document.getElementById('registrationForm').submit();
                        } else if (currentStep === 1) {
                            // Pre-deployment form submission
                            document.getElementById('preDeploymentForm').submit();
                        } else if (currentStep === 2) {
                            // Mid-deployment form submission
                            document.getElementById('midDeploymentForm').submit();
                        } else if (currentStep === 3) {
                            // Deployment form submission
                            document.getElementById('deploymentForm').submit();
                        }
                    }
                });
            });
        });
    </script>

    <style>
        /* Custom SweetAlert styling to match the theme */
        .swal-popup-custom {
            border-radius: 16px !important;
            backdrop-filter: blur(10px) !important;
        }
        
        .swal-title-custom {
            color: #1d3557 !important;
            font-weight: 700 !important;
        }
        
        .swal-content-custom {
            color: #457b9d !important;
        }
    </style>
</body>
</html>