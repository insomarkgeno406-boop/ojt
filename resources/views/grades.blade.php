@extends('layouts.app')

@section('content')
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f8fa;
            margin: 30px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .section-search-wrapper {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .section-buttons form,
        .search-form {
            display: inline-flex;
        }

        .section-button {
            padding: 10px 20px;
            border: none;
            background-color: #3490dc;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .section-button:hover {
            background-color: #2779bd;
        }

        .notification {
            background-color: #6c757d;
            color: white;
            padding: 2px 6px;
            font-size: 12px;
            border-radius: 10px;
            margin-left: 6px;
        }

        .search-input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            width: 200px;
        }

        .search-button {
            padding: 10px 16px;
            border: none;
            background-color: #38c172;
            color: white;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            font-weight: bold;
        }

        .search-button:hover {
            background-color: #2f9e60;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #3490dc;
            color: white;
        }

        .alert {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .alert.success {
            color: #38c172;
        }

        .alert.error {
            color: #e3342f;
        }

        .request-btn {
            background-color: #38c172;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .request-btn:hover {
            background-color: #2f9e60;
        }

        .requested-label {
            color: #aaa;
            font-weight: bold;
            font-size: 14px;
        }

        .view-btn {
            background-color: #6f42c1;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .view-btn:hover {
            background-color: #563d7c;
        }

        .delete-btn {
            background-color: #e3342f;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 12px;
        }

        .delete-btn:hover {
            background-color: #cc1f1a;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
            align-items: center;
        }
    </style>

    <h1>📊 Grades</h1>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <div class="section-search-wrapper">
        {{-- Show All Button --}}
        <form method="GET" action="{{ route('grades') }}">
            <input type="hidden" name="filter" value="all">
            <button class="section-button">
                Show All
                @if(array_sum($sectionCounts))
                    <span class="notification">{{ array_sum($sectionCounts) }}</span>
                @endif
            </button>
        </form>

        {{-- Section Buttons --}}
        @foreach(array_keys($sectionCounts) as $section)
            <form method="GET" action="{{ route('grades') }}">
                <input type="hidden" name="filter" value="{{ $section }}">
                <button class="section-button">
                    {{ $section }}
                    @if(isset($sectionCounts[$section]) && $sectionCounts[$section] > 0)
                        <span class="notification">{{ $sectionCounts[$section] }}</span>
                    @endif
                </button>
            </form>
        @endforeach

        {{-- Search Bar --}}
        <form class="search-form" action="{{ route('grades') }}" method="GET">
            <input type="text" name="search" class="search-input" placeholder="Search name or section..." value="{{ request('search') }}" id="searchInput">
            <button type="submit" class="search-button">🔍</button>
        </form>
    </div>

    @if($interns->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Section</th>
                    <th>Certificate</th>
                    <th>Evaluation Form</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($interns as $intern)
                    <tr>
                        <td>{{ $intern->first_name }} {{ $intern->last_name }}</td>
                        <td>{{ $intern->section }}</td>
                        @foreach(['certificate', 'evaluation'] as $type)
                            <td>
                                @php
                                    $hasSubmission = isset($submissions[$intern->id][$type]);
                                    $wasRequested = isset($requests[$intern->id][$type]);
                                @endphp

                                @if($hasSubmission && !empty($submissions[$intern->id][$type]->file_path))
                                    <a href="{{ asset('storage/' . $submissions[$intern->id][$type]->file_path) }}"
                                       class="view-btn" target="_blank">View</a>
                                @elseif($wasRequested)
                                    <span class="requested-label">Requested</span>
                                @else
                                    <form action="{{ route('grades.request') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="intern_id" value="{{ $intern->id }}">
                                        <input type="hidden" name="type" value="{{ $type }}">
                                        <button type="submit" class="request-btn">Request</button>
                                    </form>
                                @endif
                            </td>
                        @endforeach
                        <td>
                            <div class="action-buttons">
                                <form action="{{ route('intern.destroy', $intern->id) }}" method="POST" 
                                      onsubmit="return confirm('Are you absolutely sure you want to delete {{ $intern->first_name }} {{ $intern->last_name }}? This will permanently delete the intern and ALL their data including time logs, journals, grades, messages, and documents. This action cannot be undone!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">🗑 Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="alert error">
            No accepted interns{{ request('filter') && request('filter') !== 'all' ? ' for section ' . request('filter') : '' }}.
        </p>
    @endif

    <script>
        // Real-time search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('tbody tr');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    
                    tableRows.forEach(row => {
                        const nameCell = row.querySelector('td:first-child');
                        const sectionCell = row.querySelector('td:nth-child(2)');
                        
                        if (nameCell && sectionCell) {
                            const name = nameCell.textContent.toLowerCase();
                            const section = sectionCell.textContent.toLowerCase();
                            
                            if (name.includes(searchTerm) || section.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                });
            }
        });
    </script>
@endsection