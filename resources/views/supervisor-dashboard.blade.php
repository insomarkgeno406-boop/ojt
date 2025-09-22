<!DOCTYPE html>
<html>
<head>
    <title>Supervisor Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #2d3748;
            line-height: 1.6;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #2c5282 0%, #1a365d 100%);
            color: white;
            padding: 30px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 0 30px 30px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 30px;
        }

        .sidebar-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            color: #a0aec0;
            font-size: 14px;
        }

        .sidebar-nav {
            padding: 0 20px;
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            color: #e2e8f0;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        .logout-btn {
            margin-top: 30px;
            background: #e53e3e;
            color: white;
            border: none;
            padding: 15px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c53030;
            transform: translateY(-2px);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            width: calc(100% - 280px);
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: #718096;
            font-size: 16px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-icon.total { background: #4299e1; }
        .stat-icon.present { background: #48bb78; }
        .stat-icon.absent { background: #f56565; }
        .stat-icon.pending { background: #ed8936; }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1a202c;
        }

        .stat-label {
            color: #718096;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 15px 25px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-primary {
            background: #4299e1;
            color: white;
        }

        .btn-primary:hover {
            background: #3182ce;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #48bb78;
            color: white;
        }

        .btn-success:hover {
            background: #38a169;
            transform: translateY(-2px);
        }

        .btn-warning {
            background: #ed8936;
            color: white;
        }

        .btn-warning:hover {
            background: #dd6b20;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #f56565;
            color: white;
        }

        .btn-danger:hover {
            background: #e53e3e;
            transform: translateY(-2px);
        }

        /* Attendance Table */
        .attendance-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a202c;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }

        .attendance-table th,
        .attendance-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .attendance-table th {
            background: #f7fafc;
            font-weight: 600;
            color: #4a5568;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .attendance-table tr:hover {
            background: #f7fafc;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-present {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-absent {
            background: #fed7d7;
            color: #742a2a;
        }

        .status-released {
            background: #bee3f8;
            color: #2a4365;
        }

        .status-not-released {
            background: #fef5e7;
            color: #744210;
        }

        .action-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 5px;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .btn-mark-absent {
            background: #f56565;
            color: white;
        }

        .btn-mark-absent:hover {
            background: #e53e3e;
        }

        .btn-view-details {
            background: #4299e1;
            color: white;
        }

        .btn-view-details:hover {
            background: #3182ce;
        }

        /* QR Code Section */
        .qr-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 30px;
        }

        .qr-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f7fafc;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .qr-status {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qr-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .qr-active {
            background: #48bb78;
            box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.2);
        }

        .qr-inactive {
            background: #a0aec0;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                width: 250px;
            }
            
            .main-content {
                margin-left: 250px;
                width: calc(100% - 250px);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Message Modal */
        .message-modal {
            display: none;
            position: fixed;
            z-index: 1100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }

        .message-modal-content {
            background: #fff;
            margin: 15% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            position: relative;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .message-icon { font-size: 48px; margin-bottom: 20px; }
        .message-text { font-size: 18px; margin-bottom: 25px; line-height: 1.5; }
        .message-text.success { color: #15803d; }
        .message-text.error { color: #b91c1c; }
        .message-ok-btn { 
            background: #3b82f6; 
            color: #fff; 
            padding: 12px 30px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: 600; 
            font-size: 16px; 
            transition: background 0.2s; 
        }
        .message-ok-btn:hover { background: #2563eb; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>👨‍💼 Supervisor</h2>
                <p>{{ $supervisor->name }}</p>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="#dashboard" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </div>
             
            
            <form method="POST" action="{{ route('supervisor.logout') }}" style="padding: 0 20px;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <h1 class="page-title">Supervisor Dashboard</h1>
                <p class="page-subtitle">Monitor your interns' attendance and manage daily operations</p>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon total">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $totalInterns }}</div>
                    <div class="stat-label">Total Interns</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon present">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $presentCount }}</div>
                    <div class="stat-label">Present Today</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon absent">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $absentCount }}</div>
                    <div class="stat-label">Absent Today</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $notNoticedCount }}</div>
                    <div class="stat-label">Not Noticed</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <form method="POST" action="{{ route('supervisor.releaseAttendance') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-bell"></i>
                        Release Time In
                    </button>
                </form>
                
                @if($activeAttendance)
                    <div class="btn btn-success" style="cursor: default;">
                        <i class="fas fa-clock"></i>
                        Time In Active ({{ $activeAttendance->created_at->diffForHumans() }})
                    </div>
                @else
                    <div class="btn btn-warning" style="cursor: default;">
                        <i class="fas fa-clock"></i>
                        No Active Time In
                    </div>
                @endif
                
                <button type="button" class="btn btn-danger" onclick="resetAttendance()">
                    <i class="fas fa-redo"></i>
                    Reset Attendance
                </button>
            </div>

            <!-- Time In Status -->
            @if($activeAttendance)
            <div class="qr-section">
                <div class="section-header">
                    <h3 class="section-title">⏰ Active Time In Session</h3>
                </div>
                <div class="qr-info">
                    <div class="qr-status">
                        <div class="qr-indicator qr-active"></div>
                        <span><strong>Time In Active</strong> - Started {{ $activeAttendance->created_at->format('g:i A') }} (Expires in {{ max(0, 5 - $activeAttendance->created_at->diffInMinutes(now())) }} minutes)</span>
                    </div>
                    <div class="expiry-timer" id="expiryTimer">
                        <span style="font-weight: bold; color: #e53e3e;">
                            ⏰ {{ max(0, 5 - $activeAttendance->created_at->diffInMinutes(now())) }}:00 minutes remaining
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Attendance Monitoring -->
            <div class="attendance-section">
                <div class="section-header">
                    <h3 class="section-title">📊 Attendance Monitoring</h3>
                    <button class="btn btn-primary" onclick="refreshAttendance()">
                        <i class="fas fa-sync-alt"></i>
                        Refresh
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>Intern Name</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Time</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($interns as $intern)
                                <tr>
                                    <td>
                                        <strong>{{ $intern->first_name }} {{ $intern->last_name }}</strong>
                                    </td>
                                    <td>{{ $intern->section }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $intern->attendance_status }}">
                                            {{ ucfirst(str_replace('_', ' ', $intern->attendance_status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($intern->attendance_time)
                                            {{ $intern->attendance_time->format('g:i A') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($intern->attendance_notes)
                                            <span title="{{ $intern->attendance_notes }}">
                                                {{ Str::limit($intern->attendance_notes, 30) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($intern->attendance_status === 'released')
                                            <button class="action-btn btn-mark-absent" onclick="markAbsent({{ $intern->id }})">
                                                Mark Absent
                                            </button>
                                        @elseif($intern->attendance_status === 'present')
                                            <button class="action-btn btn-view-details" onclick="viewDetails({{ $intern->id }})">
                                                View Details
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="message-modal">
        <div class="message-modal-content">
            <div class="message-icon" id="messageIcon"></div>
            <div class="message-text" id="messageText"></div>
            <button class="message-ok-btn" onclick="closeMessageModal()">Okay</button>
        </div>
    </div>

    <script>
        // Show message modal on page load if there are session messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showMessageModal('success', '{{ session('success') }}');
            @elseif(session('error'))
                showMessageModal('error', '{{ session('error') }}');
            @endif
        });

        function showMessageModal(type, message) {
            const modal = document.getElementById('messageModal');
            const icon = document.getElementById('messageIcon');
            const text = document.getElementById('messageText');
            
            if (type === 'success') {
                icon.innerHTML = '✅';
                text.className = 'message-text success';
            } else {
                icon.innerHTML = '❌';
                text.className = 'message-text error';
            }
            
            text.textContent = message;
            modal.style.display = 'block';
        }

        function closeMessageModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        function refreshAttendance() {
            location.reload();
        }

        function markAbsent(internId) {
            if (confirm('Are you sure you want to mark this intern as absent?')) {
                fetch(`/supervisor/attendance/mark-absent/${internId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessageModal('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showMessageModal('error', data.message);
                    }
                })
                .catch(error => {
                    showMessageModal('error', 'An error occurred while marking attendance.');
                });
            }
        }

        function resetAttendance() {
            if (confirm('Are you sure you want to reset all attendance? This will clear all attendance records for today.')) {
                fetch('/supervisor/attendance/reset', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessageModal('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showMessageModal('error', data.message);
                    }
                })
                .catch(error => {
                    showMessageModal('error', 'An error occurred while resetting attendance.');
                });
            }
        }

        function viewDetails(internId) {
            // Implement view details functionality
            alert('View details functionality will be implemented here.');
        }

        // Auto-refresh attendance status every 30 seconds
        setInterval(refreshAttendance, 30000);

        // Countdown timer for active time in session
        @if($activeAttendance)
        function updateCountdown() {
            const startTime = new Date('{{ $activeAttendance->created_at }}').getTime();
            const now = new Date().getTime();
            const elapsed = now - startTime;
            const remaining = Math.max(0, (5 * 60 * 1000) - elapsed); // 5 minutes in milliseconds
            
            if (remaining <= 0) {
                document.getElementById('expiryTimer').innerHTML = '<span style="font-weight: bold; color: #e53e3e;">⏰ EXPIRED</span>';
                return;
            }
            
            const minutes = Math.floor(remaining / (1000 * 60));
            const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
            
            document.getElementById('expiryTimer').innerHTML = `
                <span style="font-weight: bold; color: #e53e3e;">
                    ⏰ ${minutes}:${seconds.toString().padStart(2, '0')} minutes remaining
                </span>
            `;
        }
        
        // Update countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown(); // Initial call
        @endif
    </script>
</body>
</html> 