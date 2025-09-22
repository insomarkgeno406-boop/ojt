<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>

  <!-- Bootstrap (optional) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Your custom sidebar + layout styles -->
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f8;
      margin: 0;
      display: flex;
    }

    .sidebar {
      width: 240px;
      background-color: #3490dc;
      color: white;
      padding: 20px;
      height: 100vh;
      position: fixed;
    }

    .sidebar h2 {
      margin-bottom: 30px;
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

    .sidebar a:hover {
      background-color: #2779bd;
    }

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

    .logout-btn:hover {
      background-color: #cc1f1a;
    }

    .main-content {
      margin-left: 260px; /* width + padding of sidebar */
      padding: 40px;
      flex: 1;
    }

    /* Navigation Dropdown Styles */
    .nav-item-with-dropdown {
      position: relative;
    }

    .nav-link-main {
      display: block;
      color: white;
      text-decoration: none;
      margin: 15px 0;
      padding: 10px 15px;
      border-radius: 5px;
      position: relative;
    }

    .nav-link-main:hover {
      background-color: #2779bd;
    }

    .nav-dropdown {
      position: absolute;
      left: 100%;
      top: 0;
      background-color: #2779bd;
      border-radius: 8px;
      min-width: 200px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      opacity: 0;
      visibility: hidden;
      transform: translateX(-10px);
      transition: all 0.3s ease;
      z-index: 1000;
      margin-left: 10px;
    }

    .nav-item-with-dropdown:hover .nav-dropdown {
      opacity: 1;
      visibility: visible;
      transform: translateX(0);
    }

    .dropdown-item {
      display: block;
      color: white;
      text-decoration: none;
      padding: 12px 20px;
      border-radius: 0;
      margin: 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      transition: background-color 0.2s ease;
    }

    .dropdown-item:first-child {
      border-radius: 8px 8px 0 0;
    }

    .dropdown-item:last-child {
      border-radius: 0 0 8px 8px;
      border-bottom: none;
    }

    .dropdown-item:hover {
      background-color: #1e40af;
      color: white;
    }

    /* Arrow indicator for dropdown */
    .nav-link-main::after {
      content: '▶';
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 12px;
      opacity: 0.7;
      transition: transform 0.2s ease;
    }

    .nav-item-with-dropdown:hover .nav-link-main::after {
      transform: translateY(-50%) rotate(90deg);
    }

    /* Dropdown Count Badge */
    .dropdown-count {
      background-color: #ef4444;
      color: white;
      border-radius: 50%;
      font-size: 11px;
      padding: 2px 6px;
      margin-left: auto;
      min-width: 18px;
      text-align: center;
      display: inline-block;
    }

    .dropdown-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin</h2>

    <a href="{{ route('dashboard') }}">
      🏠 Dashboard
    </a>

    <!-- Phase Dropdown for Intern List -->
    <div class="nav-item-with-dropdown">
      <a href="{{ route('interns') }}" class="nav-link-main">
        👥 Intern List
        @if(isset($pendingCount) && $pendingCount > 0)
          <span class="notification-badge">{{ $pendingCount }}</span>
        @endif
      </a>
      <div class="nav-dropdown">
        <a href="{{ route('interns', ['phase' => 'all']) }}" class="dropdown-item">
          📋 All Phases
          @if(isset($pendingCount) && $pendingCount > 0)
            <span class="dropdown-count">{{ $pendingCount }}</span>
          @endif
        </a>
        <a href="{{ route('interns', ['phase' => 'pre_deployment']) }}" class="dropdown-item">
          🚀 Pre-Deployment
          @if(isset($phaseCounts['pre_deployment']) && $phaseCounts['pre_deployment'] > 0)
            <span class="dropdown-count">{{ $phaseCounts['pre_deployment'] }}</span>
          @endif
        </a>
        <a href="{{ route('interns', ['phase' => 'mid_deployment']) }}" class="dropdown-item">
          ⚡ Mid-Deployment
          @if(isset($phaseCounts['mid_deployment']) && $phaseCounts['mid_deployment'] > 0)
            <span class="dropdown-count">{{ $phaseCounts['mid_deployment'] }}</span>
          @endif
        </a>
        <a href="{{ route('interns', ['phase' => 'deployment']) }}" class="dropdown-item">
          🎯 Deployment
          @if(isset($phaseCounts['deployment']) && $phaseCounts['deployment'] > 0)
            <span class="dropdown-count">{{ $phaseCounts['deployment'] }}</span>
          @endif
        </a>
      </div>
    </div>

    <a href="{{ route('documents') }}">
      📄 Documents
    </a>

    <a href="{{ route('grades') }}">
      📊 Grades
    </a>

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

  <!-- Main content area -->
  <div class="main-content">
    @yield('content')
  </div>

  <!-- Bootstrap Bundle (optional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>
