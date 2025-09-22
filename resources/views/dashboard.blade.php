<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            display: flex;
        }
        /* Sidebar */
        .sidebar {
            width: 240px;
            background-color: #3490dc;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 15px 0;
            padding: 10px 15px;
            border-radius: 5px;
            position: relative;
        }
        .sidebar a:hover { background-color: #2779bd; }
        .notification-badge {
            background-color: red;
            color: white;
            border-radius: 50%;
            font-size: 12px;
            padding: 3px 7px;
            position: absolute;
            top: 8px;
            right: 10px;
        }
        .logout-btn {
            background-color: #e3342f;
            border: none;
            padding: 10px 20px;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 30px;
            width: 100%;
        }
        .logout-btn:hover { background-color: #cc1f1a; }
        /* Main Content */
        .content {
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            flex: 1;
        }
        .top-card {
            flex: 1;
            color: white;
            border-radius: 8px;
            padding: 30px 20px;
        }
        .bg-blue { background: #3490dc; }
        .bg-green { background: #38c172; }
        .bg-red { background: #e3342f; }
        .circle-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .progress {
            background: #e0e0e0;
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .progress-bar {
            height: 100%;
            background: #3490dc;
            text-align: right;
            color: white;
            padding-right: 5px;
            font-size: 12px;
        }
        .circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: conic-gradient(#e3342f {{ $toReviewPercent }}%, #e0e0e0 {{ $toReviewPercent }}%);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            font-weight: bold;
        }
        .footer {
            background: white;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 14px;
        }
        .footer span {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: 600;
        }
        .footer .active { background: #38c172; color: white; }
        .footer .total { background: #3490dc; color: white; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Admin</h2>
    <a href="{{ route('dashboard') }}">🏠 Dashboard</a>
    <a href="{{ route('interns') }}">
      👥 Intern List
      @if(isset($pendingCount) && $pendingCount > 0)
        <span class="notification-badge">{{ $pendingCount }}</span>
      @endif
    </a>
    <a href="{{ route('documents') }}">📄 Documents</a>
    <a href="{{ route('grades') }}">📊 Grades</a>
    <a href="{{ route('messages') }}">
      ✉️ Messages
      @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
        <span class="notification-badge">{{ $unreadMessagesCount }}</span>
      @endif
    </a>
    

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="logout-btn">🚪 Logout</button>
    </form>
</div>

<!-- Main content -->
<div class="content">

    <!-- Date & Time -->
    <div class="row">
        <div class="top-card bg-blue">
            <h3>DATE TODAY</h3>
            <p>{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
        </div>
        <div class="top-card bg-blue">
            <h3>TIME TODAY</h3>
            <p>{{ \Carbon\Carbon::now()->format('h:i A') }}</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="row">
        <div class="top-card bg-green">
            <h3>TOTAL OF INTERNS</h3>
            <p style="font-size: 32px;">{{ $acceptedCount }}</p>
        </div>
        <div class="top-card bg-red">
            <h3>TO REVIEW REQUESTS</h3>
            <p style="font-size: 32px;">{{ $toReview }}</p>
        </div>
    </div>

    <!-- Circle -->
    <div class="row">
        <div class="card circle-container">
            <div class="circle">{{ $toReviewPercent }}%</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <span class="total">Total Students: {{ $acceptedCount + $pendingCount }}</span>
        <span class="active">Active Online: {{ $acceptedCount }}</span>
    </div>

    <!-- Progress Section at the bottom -->
    <div class="card" style="margin-top: 20px;">
        <h3 style="margin-bottom: 15px;">Progress Overview</h3>
        <!-- Header inside progress -->
        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
            <span style="background: #3490dc; color: white; padding: 5px 10px; border-radius: 5px;">North</span>
            <span style="background: #38c172; color: white; padding: 5px 10px; border-radius: 5px;">West</span>
            <span style="background: #ff9800; color: white; padding: 5px 10px; border-radius: 5px;">South</span>
            <span style="background: #e3342f; color: white; padding: 5px 10px; border-radius: 5px;">East</span>
        </div>

        <h4>Today's Progress</h4>
        <div class="progress">
            <div class="progress-bar" style="width: {{ $todayProgress }}%;">{{ $todayProgress }}%</div>
        </div>

        <h4>This Week's Progress</h4>
        <div class="progress">
            <div class="progress-bar" style="width: {{ $weekProgress }}%;">{{ $weekProgress }}%</div>
        </div>

        <h4>This Month's Progress</h4>
        <div class="progress">
            <div class="progress-bar" style="width: {{ $monthProgress }}%;">{{ $monthProgress }}%</div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
