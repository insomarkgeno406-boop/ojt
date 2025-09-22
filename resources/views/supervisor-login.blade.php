<!DOCTYPE html>
<html>
<head>
<title>Supervisor Login</title>
</head>
<body>
<div class="container" style="max-width:420px;margin:40px auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.05)">
    <h2 style="margin:0 0 16px;">Supervisor Login</h2>

    @if(session('success'))
        <div style="background:#d1fae5;color:#065f46;padding:10px;border-radius:6px;margin-bottom:12px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2;color:#991b1b;padding:10px;border-radius:6px;margin-bottom:12px;">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('supervisor.login.submit') }}">
        @csrf
        <div style="margin-bottom:12px;">
            <label>Email</label>
            <input type="email" name="email" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
        </div>
        <div style="margin-bottom:16px;">
            <label>Password</label>
            <input type="password" name="password" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
        </div>
        <button type="submit" style="width:100%;padding:10px 14px;border:none;background:#3490dc;color:#fff;border-radius:6px;font-weight:600;">Login</button>
    </form>
    </form>

</div>

<script></script>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Supervisor Login & Register</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }
        .container { max-width: 420px; margin: 60px auto; background: #fff; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); overflow: hidden; }
        .tab-buttons { display: flex; justify-content: space-around; background-color: #f59e42; }
        .tab-buttons button { flex: 1; padding: 12px; background: none; border: none; color: white; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease; }
        .tab-buttons button.active { background-color: #d97706; }
        .form-container { padding: 30px 40px; }
        form { display: none; flex-direction: column; }
        form.active { display: flex; }
        input { padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; }
        button.submit { background-color: #f59e42; color: white; padding: 10px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; }
        button.submit:hover { background-color: #d97706; }
        .error { color: red; font-size: 14px; margin-bottom: 10px; text-align: center; }
        .note { color: #666; font-size: 13px; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="container">
    <a href="{{ route('login') }}" class="back-link">&larr; Back</a>
    <div class="tab-buttons">
        <button class="tab-btn active" onclick="switchTab('supervisor-login')">Login</button>
        <button class="tab-btn" onclick="switchTab('supervisor-register')">Register</button>
    </div>
    <div class="form-container">
        {{-- Login Form --}}
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        <form id="supervisor-login" class="active" method="POST" action="{{ route('supervisor.login.submit') }}">
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
        <form id="supervisor-register" method="POST" action="{{ route('supervisor.register.submit') }}">
            @csrf
            <input type="text" name="name" placeholder="Name" required value="{{ old('name') }}">
            <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            <button class="submit" type="submit">Register</button>
            <div class="note">Your account must be approved by the admin before you can log in.</div>
        </form>
    </div>
</div>
<div id="messageModal" class="message-modal" style="display:none;">
    <div class="message-modal-content">
        <div class="message-icon" id="messageIcon"></div>
        <div class="message-text" id="messageText"></div>
        <button class="message-ok-btn" onclick="closeMessageModal()">Okay</button>
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
document.addEventListener('DOMContentLoaded', function() {
  @if(session('success'))
    showMessageModal('success', '{{ session('success') }}');
  @elseif(session('error'))
    showMessageModal('error', '{{ session('error') }}');
  @elseif($errors->any())
    showMessageModal('error', `{{ implode(' ', $errors->all()) }}`);
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
</script>
<style>
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
.message-ok-btn { background: #3b82f6; color: #fff; padding: 12px 30px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 16px; transition: background 0.2s; }
.message-ok-btn:hover { background: #2563eb; }
.back-link {
  display: inline-block;
  margin-bottom: 18px;
  color: #3490dc;
  text-decoration: none;
  font-size: 16px;
}
.back-link:hover {
  text-decoration: underline;
}
</style>
</body>
</html> 