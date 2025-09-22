@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f8fafc;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 40px auto;
        background-color: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    h1 {
        margin-bottom: 25px;
        color: #2c3e50;
        font-weight: 600;
        text-align: center;
    }

    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
    }

    .search-form {
        display: inline-flex;
    }

    .section-button {
        position: relative;
        padding: 10px 16px;
        background-color: #3490dc;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.2s ease;
        font-weight: bold;
    }

    .section-button:hover {
        background-color: #2779bd;
    }

    .notification {
        position: absolute;
        top: -6px;
        right: -6px;
        background-color: #e3342f;
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 3px 6px;
        border-radius: 50%;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .search-input {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        width: 180px;
    }

    .search-button {
        padding: 8px 12px;
        background-color: #10b981;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .search-button:hover {
        background-color: #059669;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        overflow: hidden;
        border-radius: 10px;
    }

    th, td {
        padding: 14px 16px;
        text-align: center;
        vertical-align: middle;
    }

    th {
        background-color: #3490dc;
        color: white;
        font-weight: 600;
        white-space: nowrap;
    }

    td {
        background-color: white;
        border-bottom: 1px solid #e5e7eb;
    }

    tr:nth-child(even) td {
        background-color: #f9fafb;
    }

    .no-data {
        color: #6b7280;
        font-style: italic;
        text-align: center;
        padding: 20px;
    }

    .button {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        color: white;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }

    .dtr-button { background-color: #f59e0b; }
    .dtr-button:hover { background-color: #d97706; }

    .journal-button { background-color: #38c172; }
    .journal-button:hover { background-color: #2f9e63; }

    .delete-button {
        background-color: #e3342f;
    }
    .delete-button:hover {
        background-color: #cc1f1a;
    }

    .progress-bar-container {
        width: 100%;
        background-color: #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        height: 20px;
        position: relative;
    }

    .progress-bar {
        height: 100%;
        background-color: #38c172;
        color: white;
        text-align: center;
        line-height: 20px;
        font-size: 13px;
        font-weight: bold;
        white-space: nowrap;
        transition: width 0.4s ease;
    }

    .hours-text {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
        display: block;
    }

    /* Enhanced Pagination Styles */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        padding: 15px 0;
        border-top: 1px solid #e5e7eb;
    }

    .pagination-info {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
    }

    /* Custom Pagination Navigation */
    .pagination-nav {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pagination-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        text-decoration: none;
        color: #374151;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        background-color: white;
        min-width: 80px;
        justify-content: center;
    }

    .pagination-btn:hover:not(.disabled) {
        background-color: #3490dc;
        color: white;
        border-color: #3490dc;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(52, 144, 220, 0.2);
    }

    .pagination-btn.disabled {
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination-btn.disabled:hover {
        background-color: white;
        color: #9ca3af;
        border-color: #d1d5db;
        transform: none;
        box-shadow: none;
    }

    .page-numbers {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .page-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        text-decoration: none;
        color: #374151;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        background-color: white;
    }

    .page-number:hover:not(.active):not(.disabled) {
        background-color: #f3f4f6;
        border-color: #9ca3af;
    }

    .page-number.active {
        background-color: #3490dc;
        color: white;
        border-color: #3490dc;
        font-weight: 600;
    }

    .page-number.disabled {
        color: #9ca3af;
        cursor: not-allowed;
    }

    .pagination-ellipsis {
        padding: 8px 4px;
        color: #9ca3af;
        font-weight: 500;
    }

    /* Default Laravel pagination override */
    .pagination {
        display: none !important;
    }

    /* Responsive pagination */
    @media (max-width: 768px) {
        .pagination-wrapper {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .pagination-nav {
            justify-content: center;
        }

        .page-numbers {
            display: none;
        }
    }
</style>

<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px;">
        <h1 style="margin:0;">📁 Intern Document Overview</h1>
        <a href="{{ route('documents.archive') }}" class="button" style="background:#6b7280; text-decoration:none;">📦 Archive</a>
    </div>

    <!-- Section Filter and Search -->
    <div class="filter-bar">
        {{-- Show All Button --}}
        <form method="GET" action="{{ route('documents') }}">
            <input type="hidden" name="filter" value="all">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <button type="submit" class="section-button">
                Show All
                @if(array_sum($sectionCounts))
                    <span class="notification">{{ array_sum($sectionCounts) }}</span>
                @endif
            </button>
        </form>

        {{-- Section Buttons --}}
        @foreach(array_keys($sectionCounts) as $section)
            <form method="GET" action="{{ route('documents') }}">
                <input type="hidden" name="filter" value="{{ $section }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <button type="submit" class="section-button">
                    {{ $section }}
                    @if(isset($sectionCounts[$section]) && $sectionCounts[$section] > 0)
                        <span class="notification">{{ $sectionCounts[$section] }}</span>
                    @endif
                </button>
            </form>
        @endforeach

        {{-- Search Bar --}}
        <form method="GET" action="{{ route('documents') }}" class="search-form">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            <input type="text" name="search" class="search-input" placeholder="Search name or section..." value="{{ request('search') }}" id="searchInput">
            <button type="submit" class="search-button">🔍</button>
        </form>
    </div>

    @if($interns->isEmpty())
        <div class="no-data">No accepted interns found.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Section</th>
                    <th>Company</th>
                    <th>📅 Attendance (by Month/Year)</th>
                    <th>📌 Task</th>
                    <th style="width: 180px;">Progress (486h)</th>
                    <th style="width: 260px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($interns as $intern)
                    @php
                        $totalHours = $intern->timeLogs->sum(function($log) {
                            if ($log->time_in && $log->time_out) {
                                $in = \Carbon\Carbon::parse($log->date . ' ' . $log->time_in, 'Asia/Manila');
                                $out = \Carbon\Carbon::parse($log->date . ' ' . $log->time_out, 'Asia/Manila');
                                return round($in->floatDiffInRealHours($out), 2);
                            }
                            return 0;
                        });
                        $progressPercent = min(round(($totalHours / 486) * 100, 2), 100);
                    @endphp
                    <tr>
                        <td>{{ $intern->first_name }} {{ $intern->last_name }}</td>
                        <td>{{ $intern->section ?? 'N/A' }}</td>
                        <td>{{ $intern->company_name }}</td>
                        <td>
                            <a href="{{ route('documents.dtr', ['id' => $intern->id]) }}" class="button dtr-button">🕒 View DTR</a>
                            <div style="margin-top:8px; font-size:12px; color:#555;">
                                @php
                                    $grouped = $intern->timeLogs->groupBy(function($log){
                                        return \Carbon\Carbon::parse($log->date, 'Asia/Manila')->format('F Y');
                                    });
                                @endphp
                                @foreach($grouped as $monthYear => $logs)
                                    <div>{{ $monthYear }} ({{ $logs->count() }} days)</div>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.journal', ['id' => $intern->id]) }}" class="button journal-button">📝 Journal</a>
                        </td>
                        <td>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: {{ $progressPercent }}%;">
                                    {{ $progressPercent }}%
                                </div>
                            </div>
                            <span class="hours-text">{{ $totalHours }} / 486 hours</span>
                        </td>
                        <td>
                            <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                                <button type="button" class="button" style="background:#6b7280;" onclick="openArchiveModal({{ $intern->id }}, '{{ $intern->first_name }} {{ $intern->last_name }}', '{{ $intern->application_letter ? route('documents.view', ['filename' => basename($intern->application_letter)]) : '' }}', '{{ $intern->parents_waiver ? route('documents.view', ['filename' => basename($intern->parents_waiver)]) : '' }}', '{{ $intern->acceptance_letter ? route('documents.view', ['filename' => basename($intern->acceptance_letter)]) : '' }}')">📦 Archive</button>
                                <form class="delete-form" action="{{ route('intern.destroy', $intern->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="button delete-button delete-btn">🗑 Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Enhanced Pagination -->
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Showing {{ $interns->firstItem() }} to {{ $interns->lastItem() }} of {{ $interns->total() }} results
            </div>
            
            <div class="pagination-nav">
                <!-- Previous Button -->
                @if($interns->onFirstPage())
                    <span class="pagination-btn disabled">
                        ← Previous
                    </span>
                @else
                    <a href="{{ $interns->appends(request()->query())->previousPageUrl() }}" class="pagination-btn">
                        ← Previous
                    </a>
                @endif

                <!-- Page Numbers -->
                <div class="page-numbers">
                    @php
                        $currentPage = $interns->currentPage();
                        $lastPage = $interns->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp

                    {{-- First Page --}}
                    @if($startPage > 1)
                        <a href="{{ $interns->appends(request()->query())->url(1) }}" class="page-number">1</a>
                        @if($startPage > 2)
                            <span class="pagination-ellipsis">...</span>
                        @endif
                    @endif

                    {{-- Page Numbers --}}
                    @for($i = $startPage; $i <= $endPage; $i++)
                        @if($i == $currentPage)
                            <span class="page-number active">{{ $i }}</span>
                        @else
                            <a href="{{ $interns->appends(request()->query())->url($i) }}" class="page-number">{{ $i }}</a>
                        @endif
                    @endfor

                    {{-- Last Page --}}
                    @if($endPage < $lastPage)
                        @if($endPage < $lastPage - 1)
                            <span class="pagination-ellipsis">...</span>
                        @endif
                        <a href="{{ $interns->appends(request()->query())->url($lastPage) }}" class="page-number">{{ $lastPage }}</a>
                    @endif
                </div>

                <!-- Next Button -->
                @if($interns->hasMorePages())
                    <a href="{{ $interns->appends(request()->query())->nextPageUrl() }}" class="pagination-btn">
                        Next →
                    </a>
                @else
                    <span class="pagination-btn disabled">
                        Next →
                    </span>
                @endif
            </div>
        </div>

        <!-- Hide default Laravel pagination -->
        <div style="display: none;">
            {{ $interns->links() }}
        </div>
    @endif
</div>

<script>
    // Archived list is now a dedicated page; no header modal needed

    function openArchiveModal(id, fullName, appUrl, parentUrl, acceptUrl) {
        const links = [];
        if (appUrl) links.push(`<a href="${appUrl}" target="_blank">Application Letter</a>`);
        if (parentUrl) links.push(`<a href="${parentUrl}" target="_blank">Parent's Waiver</a>`);
        if (acceptUrl) links.push(`<a href="${acceptUrl}" target="_blank">Acceptance Letter</a>`);

        const html = `
            <div style="text-align:left">
                <p><strong>Intern:</strong> ${fullName}</p>
                ${links.length ? `<p><strong>Documents:</strong></p><ul style="padding-left:18px;">${links.map(l=>`<li>${l}</li>`).join('')}</ul>` : '<p><em>No document links available.</em></p>'}
                <p>Archiving will hide this intern from the Documents list but keep their data for future reference.</p>
            </div>
        `;

        Swal.fire({
            title: 'Archive Intern?',
            html,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Archive',
            cancelButtonText: 'Cancel',
        }).then(res => {
            if (res.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/interns/${id}/archive`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let form = this.closest('.delete-form');
            Swal.fire({
                title: 'Are you absolutely sure?',
                text: "This will permanently delete the intern and ALL their data including time logs, journals, grades, messages, and documents. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete permanently!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}"
        });
    @endif
</script>
@endsection