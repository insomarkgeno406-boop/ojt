@extends('layouts.app')

@section('content')
<style>
    .supervisor-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }
    
    .supervisor-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .supervisor-body {
        padding: 20px;
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-accepted {
        background: #d4edda;
        color: #155724;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    
    .btn {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-primary { background: #007bff; color: white; }
    .btn-success { background: #28a745; color: white; }
    .btn-danger { background: #dc3545; color: white; }
    .btn-info { background: #17a2b8; color: white; }
    .btn-warning { background: #ffc107; color: #212529; }
    
    .btn:hover { opacity: 0.8; }
    
    .interns-section {
        background: #f8f9fa;
        padding: 15px;
        margin-top: 15px;
        border-radius: 4px;
    }
    
    .interns-list {
        list-style: none;
        padding: 0;
        margin: 10px 0 0 0;
    }
    
    .interns-list li {
        padding: 5px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .interns-list li:last-child {
        border-bottom: none;
    }
    
    .edit-form {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .edit-form input {
        padding: 6px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .no-supervisors {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }
</style>

<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom: 16px;">
        <h1 style="margin:0; color: #2d3748;">👨‍💼 Supervisor Management</h1>
        <form method="GET" action="{{ route('supervisors') }}" class="search-form" style="display:inline-flex;">
            <input type="text" name="search" class="search-input" placeholder="Search name or email..." value="{{ request('search') }}" style="padding:10px;border:1px solid #ccc;border-radius:5px 0 0 5px;width:240px;">
            <button type="submit" class="search-button" style="padding:10px 16px;border:none;background:#38c172;color:#fff;border-radius:0 5px 5px 0;font-weight:bold;">🔍</button>
        </form>
    </div>
    
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif
    
    <script>
    // Client-side real-time filter: hide non-matching supervisors as you type
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.querySelector('input[name="search"]');
        if (!input) return;
        input.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.supervisor-card').forEach(card => {
                const name = card.querySelector('h3')?.textContent.toLowerCase() || '';
                const emailField = card.querySelector('input[name="email"]');
                const email = emailField ? emailField.value.toLowerCase() : '';
                card.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
            });
        });
    });
    </script>

    @forelse($supervisors as $supervisor)
        <div class="supervisor-card">
            <div class="supervisor-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; color: #2d3748;">{{ $supervisor->name }}</h3>
                    <span class="status-badge {{ $supervisor->is_accepted ? 'status-accepted' : 'status-pending' }}">
                        {{ $supervisor->is_accepted ? 'Accepted' : 'Pending' }}
                    </span>
                </div>
            </div>
            
            <div class="supervisor-body">
                <form action="{{ route('supervisor.update', $supervisor->id) }}" method="POST" class="edit-form">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $supervisor->name }}" placeholder="Name" required>
                    <input type="email" name="email" value="{{ $supervisor->email }}" placeholder="Email" required>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                
                <div class="action-buttons">
                    @if(!$supervisor->is_accepted)
                        <form action="{{ route('supervisor.accept', $supervisor->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">✅ Accept</button>
                        </form>
                        <form action="{{ route('supervisor.reject', $supervisor->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning">❌ Reject</button>
                        </form>
                    @endif
                    
                    <form action="{{ route('supervisor.delete', $supervisor->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this supervisor?')">🗑️ Delete</button>
                    </form>
                    
                    <a href="{{ route('admin.connect-interns', $supervisor->id) }}" class="btn btn-info">🔗 Connect Interns</a>
                </div>
                
                <div class="interns-section">
                    <strong>👥 Connected Interns:</strong>
                    @if($supervisor->interns->count())
                        <ul class="interns-list">
                            @foreach($supervisor->interns as $intern)
                                <li>📋 {{ $intern->first_name }} {{ $intern->last_name }} ({{ $intern->email }})</li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color: #6c757d; margin: 10px 0 0 0;">No interns connected to this supervisor.</p>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="no-supervisors">
            <h3>📭 No Supervisors Found</h3>
            <p>There are currently no supervisors registered in the system.</p>
        </div>
    @endforelse
</div>
@endsection 