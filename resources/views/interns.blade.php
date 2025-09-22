@extends('layouts.app')

@section('content')
 
</head>
<body>
   <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f1f5f9;
      margin: 0;
      padding: 40px;
      color: #334155;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 32px;
    }

    a.back {
      display: inline-block;
      margin: 0 auto 30px;
      background: #64748b;
      color: #fff;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
    }

    .section-buttons {
      text-align: center;
      margin-bottom: 30px;
    }

    .section-buttons form {
      display: inline-block;
      margin: 5px;
    }

    .section-button {
      background: #3b82f6;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      position: relative;
      transition: background 0.2s;
    }

    .section-button:hover {
      background: #2563eb;
    }

    .notification {
      background: #ef4444;
      color: #fff;
      border-radius: 9999px;
      font-size: 12px;
      padding: 2px 8px;
      margin-left: 8px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      overflow: hidden;
    }

    th {
      background: #3b82f6;
      color: #fff;
      padding: 14px;
      text-align: left;
    }

    td {
      padding: 14px;
      border-bottom: 1px solid #e2e8f0;
    }

    .section-heading {
      background: #f1f5f9;
      font-weight: 600;
      text-align: center;
      padding: 10px;
      color: #475569;
    }

    .button {
      padding: 6px 12px;
      font-size: 13px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin: 0 2px;
      color: #fff;
      transition: background 0.2s;
    }

    .view { background: #0ea5e9; }
    .edit { background: #64748b; }
    .delete { background: #ef4444; }
    .accept { background: #22c55e; }

    .view:hover { background: #0284c7; }
    .edit:hover { background: #475569; }
    .delete:hover { background: #dc2626; }
    .accept:hover { background: #16a34a; }

    /* Pagination Styles */
    .pagination-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 30px 0;
      gap: 15px;
    }

    .pagination {
      display: flex;
      align-items: center;
      gap: 8px;
      background: #fff;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .pagination a,
    .pagination span {
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 40px;
      height: 40px;
      padding: 8px 12px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s;
      border: 1px solid #e5e7eb;
    }

    .pagination a {
      background: #f8fafc;
      color: #475569;
    }

    .pagination a:hover {
      background: #3b82f6;
      color: #fff;
      border-color: #3b82f6;
      transform: translateY(-1px);
    }

    .pagination .active span {
      background: #3b82f6;
      color: #fff;
      border-color: #3b82f6;
      font-weight: 600;
    }

    .pagination .disabled span {
      background: #f1f5f9;
      color: #cbd5e1;
      cursor: not-allowed;
      border-color: #e5e7eb;
    }

    .pagination-info {
      background: #f8fafc;
      padding: 10px 15px;
      border-radius: 6px;
      color: #64748b;
      font-size: 14px;
      border: 1px solid #e5e7eb;
    }

    /* Previous/Next button styles */
    .pagination .prev-next {
      padding: 8px 16px;
      font-weight: 600;
      min-width: auto;
    }

    .pagination .prev-next:hover {
      background: #2563eb;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.5);
      overflow: auto;
    }

    .modal-content {
      background: #fff;
      margin: 5% auto;
      padding: 30px;
      border-radius: 8px;
      width: 90%;
      max-width: 600px;
      position: relative;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .close {
      position: absolute;
      right: 20px; top: 20px;
      font-size: 24px;
      cursor: pointer;
      color: #64748b;
    }

    .close:hover {
      color: #000;
    }

    .modal label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
    }

    .modal p {
      margin: 5px 0 10px;
    }

    .modal input {
      width: 100%;
      padding: 8px;
      border: 1px solid #cbd5e1;
      border-radius: 4px;
      margin-top: 5px;
    }

    .modal button {
      margin-top: 20px;
      width: 100%;
    }

    .doc-link {
      display: inline-block;
      margin-top: 5px;
      color: #3b82f6;
      text-decoration: underline;
    }

    /* Message Modal Styles */
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
      from {
        transform: translateY(-50px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .message-icon {
      font-size: 48px;
      margin-bottom: 20px;
    }

    .message-text {
      font-size: 18px;
      margin-bottom: 25px;
      line-height: 1.5;
    }

    .message-text.success {
      color: #15803d;
    }

    .message-text.error {
      color: #b91c1c;
    }

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

    .message-ok-btn:hover {
      background: #2563eb;
    }

    .no-interns-message {
      text-align: center;
      padding: 40px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      margin-top: 20px;
    }

    .no-interns-message h3 {
      color: #b91c1c;
      margin-bottom: 10px;
    }

    .no-interns-message p {
      color: #64748b;
      margin: 0;
    }

    /* Confirmation Modal Styles */
    .confirm-modal {
      display: none;
      position: fixed;
      z-index: 1200;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      overflow: auto;
    }

    .confirm-modal-content {
      background: #fff;
      margin: 15% auto;
      padding: 30px;
      border-radius: 12px;
      width: 90%;
      max-width: 450px;
      text-align: center;
      box-shadow: 0 20px 40px rgba(0,0,0,0.2);
      position: relative;
      animation: slideIn 0.3s ease-out;
    }

    .confirm-icon {
      font-size: 48px;
      margin-bottom: 20px;
      color: #ef4444;
    }

    .confirm-text {
      font-size: 18px;
      margin-bottom: 25px;
      line-height: 1.5;
      color: #374151;
    }

    .confirm-buttons {
      display: flex;
      gap: 15px;
      justify-content: center;
    }

    .confirm-btn {
      padding: 12px 25px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      font-size: 16px;
      transition: all 0.2s;
      min-width: 100px;
    }

    .confirm-btn.yes {
      background: #ef4444;
      color: #fff;
    }

    .confirm-btn.yes:hover {
      background: #dc2626;
    }

    .confirm-btn.no {
      background: #6b7280;
      color: #fff;
    }

    .confirm-btn.no:hover {
      background: #4b5563;
    }

    /* Document Tab Styles */
    .doc-tab {
      background: #f1f5f9;
      color: #64748b;
      padding: 10px 20px;
      border: none;
      border-radius: 6px 6px 0 0;
      cursor: pointer;
      font-weight: 600;
      margin-right: 5px;
      transition: all 0.2s;
    }

    .doc-tab.active {
      background: #3b82f6;
      color: white;
    }

    .doc-tab:hover {
      background: #2563eb;
      color: white;
    }

    .document-tab {
      display: none;
      height: 100%;
    }

    .document-tab.active {
      display: block;
    }

    /* Phase Indicator Banner Styles */
    .phase-indicator {
      transition: all 0.3s ease;
    }

    .phase-indicator:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    }

    .phase-indicator a:hover {
      background: rgba(255, 255, 255, 0.2);
      border-color: rgba(255, 255, 255, 0.5);
      transform: translateY(-1px);
    }
  </style>

  <h1>Pending Interns</h1>

  <!-- Phase Indicator Banner -->
  @if($phase && $phase !== 'all')
    <div class="phase-indicator" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 15px 25px; border-radius: 10px; margin-bottom: 25px; text-align: center; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
      <h3 style="margin: 0; font-size: 20px;">
        📋 Currently Viewing: {{ ucfirst(str_replace('_', ' ', $phase)) }} Phase
      </h3>
      <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">
        Showing interns in the {{ ucfirst(str_replace('_', ' ', $phase)) }} phase
        @if(isset($phaseCounts[$phase]))
          • {{ $phaseCounts[$phase] }} intern(s) found
        @endif
      </p>
      <a href="{{ route('interns', ['filter' => request('filter')]) }}" style="display: inline-block; margin-top: 10px; color: white; text-decoration: none; padding: 8px 16px; border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 20px; font-size: 14px; transition: all 0.3s ease;">
        👁 View All Phases
      </a>
    </div>
  @endif

  <div class="section-buttons">
    @foreach(['North', 'East', 'West', 'South', 'Northeast'] as $section)
      <form method="GET" action="{{ route('interns') }}">
        <input type="hidden" name="filter" value="{{ $section }}">
        @if($phase && $phase !== 'all')
          <input type="hidden" name="phase" value="{{ $phase }}">
        @endif
        <button class="section-button">
          {{ $section }}
          @if(isset($sectionCounts[$section]) && $sectionCounts[$section] > 0)
            <span class="notification">{{ $sectionCounts[$section] }}</span>
          @endif
        </button>
      </form>
    @endforeach
  </div>

  @if($interns->count() > 0)
  <table>
    <thead>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Course</th>
        <th>Section</th>
        <th>Current Phase</th>
        <th>Phase Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @php $currentSection = null; @endphp
      @foreach($interns as $intern)
        @if($currentSection !== $intern->section)
          @php $currentSection = $intern->section; @endphp
          <tr>
            <td colspan="7" class="section-heading">📌 Section: {{ $currentSection }}</td>
          </tr>
        @endif
        <tr>
          <td>{{ $intern->first_name }}</td>
          <td>{{ $intern->last_name }}</td>
          <td>{{ $intern->course }}</td>
          <td>{{ $intern->section }}</td>
          <td>
            @switch($intern->current_phase)
              @case('pre_enrollment')
                <span style="color: #6b7280; font-weight: 600;">Pre-Enrollment</span>
                @break
              @case('pre_deployment')
                <span style="color: #f59e0b; font-weight: 600;">Pre-Deployment</span>
                @break
              @case('mid_deployment')
                <span style="color: #3b82f6; font-weight: 600;">Mid-Deployment</span>
                @break
              @case('deployment')
                <span style="color: #8b5cf6; font-weight: 600;">Deployment</span>
                @break
              @default
                <span style="color: #6b7280;">Unknown</span>
            @endswitch
          </td>
          <td>
            @switch($intern->current_phase)
              @case('pre_enrollment')
                <span style="color: {{ $intern->pre_enrollment_status === 'accepted' ? '#10b981' : ($intern->pre_enrollment_status === 'rejected' ? '#ef4444' : '#f59e0b') }}; font-weight: 600;">
                  {{ ucfirst($intern->pre_enrollment_status) }}
                </span>
                @break
              @case('pre_deployment')
                <span style="color: {{ $intern->pre_deployment_status === 'accepted' ? '#10b981' : ($intern->pre_deployment_status === 'rejected' ? '#ef4444' : '#f59e0b') }}; font-weight: 600;">
                  {{ ucfirst($intern->pre_deployment_status) }}
                </span>
                @break
              @case('mid_deployment')
                <span style="color: {{ $intern->mid_deployment_status === 'accepted' ? '#10b981' : ($intern->mid_deployment_status === 'rejected' ? '#ef4444' : '#f59e0b') }}; font-weight: 600;">
                  {{ ucfirst($intern->mid_deployment_status) }}
                </span>
                @break
              @case('deployment')
                <span style="color: {{ $intern->deployment_status === 'accepted' ? '#10b981' : ($intern->deployment_status === 'rejected' ? '#ef4444' : '#f59e0b') }}; font-weight: 600;">
                  {{ ucfirst($intern->deployment_status) }}
                </span>
                @break
              @default
                <span style="color: #6b7280;">Unknown</span>
            @endswitch
          </td>
          <td>
            <!-- View Documents Button -->
            <button class="button view"
              onclick="openDocumentsModal(
                '{{ $intern->resume ? route('documents.view', ['filename' => basename($intern->resume)]) : '#' }}',
                '{{ $intern->application_letter ? route('documents.view', ['filename' => basename($intern->application_letter)]) : '#' }}',
                '{{ $intern->medical_certificate ? route('documents.view', ['filename' => basename($intern->medical_certificate)]) : '#' }}',
                '{{ $intern->insurance ? route('documents.view', ['filename' => basename($intern->insurance)]) : '#' }}',
                '{{ $intern->acceptance_letter ? route('documents.view', ['filename' => basename($intern->acceptance_letter)]) : '#' }}',
                '{{ $intern->parents_waiver ? route('documents.view', ['filename' => basename($intern->parents_waiver)]) : '#' }}',
                '{{ $intern->memorandum_of_agreement ? route('documents.view', ['filename' => basename($intern->memorandum_of_agreement)]) : '#' }}',
                '{{ $intern->internship_contract ? route('documents.view', ['filename' => basename($intern->internship_contract)]) : '#' }}',
                '{{ $intern->recommendation_letter ? route('documents.view', ['filename' => basename($intern->recommendation_letter)]) : '#' }}',
                '{{ $intern->current_phase }}',
                '{{ route('documents.endorsement', ['id' => $intern->id]) }}'
              )">
              👁 View Documents
            </button>

            <!-- Phase-specific Action Buttons with Confirmations -->
            @if($intern->status === 'pending' && $intern->current_phase === 'pre_deployment')
              <button type="button" class="button accept" style="background:#22c55e;" 
                onclick="showAcceptConfirmModal('account', '{{ $intern->first_name }} {{ $intern->last_name }}', '{{ route('intern.accept', $intern->id) }}')">
                ✔ Accept Account
              </button>
            @endif

            @if($intern->current_phase === 'pre_deployment' && $intern->pre_deployment_status === 'pending')
              <button type="button" class="button accept" style="background: #10b981;" 
                onclick="showAcceptConfirmModal('pre-deployment', '{{ $intern->first_name }} {{ $intern->last_name }}', '{{ route('intern.accept.pre-deployment', $intern->id) }}')">
                ✅ Accept Pre-Deployment
              </button>
            @elseif($intern->current_phase === 'mid_deployment' && $intern->mid_deployment_status === 'pending')
              <button type="button" class="button accept" style="background: #10b981;" 
                onclick="showAcceptConfirmModal('mid-deployment', '{{ $intern->first_name }} {{ $intern->last_name }}', '{{ route('intern.accept.mid-deployment', $intern->id) }}')">
                ✅ Accept Mid-Deployment
              </button>
            @elseif($intern->current_phase === 'deployment' && $intern->deployment_status === 'pending')
              <button type="button" class="button accept" style="background: #10b981;" 
                onclick="showAcceptConfirmModal('deployment', '{{ $intern->first_name }} {{ $intern->last_name }}', '{{ route('intern.accept.deployment', $intern->id) }}')">
                ✅ Accept Deployment
              </button>
            @endif

            <!-- General Action Buttons -->
            <button class="button edit" onclick="openEditModal({{ $intern->id }}, '{{ $intern->first_name }}', '{{ $intern->last_name }}', '{{ $intern->course }}', '{{ $intern->section }}')">✎ Edit</button>
            
            <button type="button" class="button delete" 
              onclick="showDeleteConfirmModal('{{ $intern->first_name }} {{ $intern->last_name }}', '{{ route('intern.destroy', $intern->id) }}')">
              🗑 Delete
            </button>
            
            <!-- Accept Intern Button (only show if all phases are completed) -->
            @if($intern->current_phase === 'completed')
              <button type="button" class="button accept" 
                onclick="showAcceptConfirmModal('intern', '{{ $intern->first_name }} {{ $intern->last_name }}', '{{ route('intern.accept', $intern->id) }}')">
                ✔ Accept Intern
              </button>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Pagination -->
  @if($interns->hasPages())
  <div class="pagination-container">
    <div class="pagination-info">
      Showing {{ $interns->firstItem() }} to {{ $interns->lastItem() }} of {{ $interns->total() }} pending interns
    </div>
    
    <nav class="pagination">
      {{-- Previous Page Link --}}
      @if ($interns->onFirstPage())
        <span class="disabled">
          <span>← Previous</span>
        </span>
      @else
        <a href="{{ $interns->previousPageUrl() }}" class="prev-next">
          ← Previous
        </a>
      @endif

      {{-- Pagination Elements --}}
      @foreach ($interns->getUrlRange(1, $interns->lastPage()) as $page => $url)
        @if ($page == $interns->currentPage())
          <span class="active"><span>{{ $page }}</span></span>
        @else
          <a href="{{ $url }}">{{ $page }}</a>
        @endif
      @endforeach

      {{-- Next Page Link --}}
      @if ($interns->hasMorePages())
        <a href="{{ $interns->nextPageUrl() }}" class="prev-next">
          Next →
        </a>
      @else
        <span class="disabled">
          <span>Next →</span>
        </span>
      @endif
    </nav>
  </div>
  @endif

  @else
    <div class="no-interns-message">
      <h3>No Pending Interns</h3>
      <p>{{ request('filter') ? 'No pending interns found for section ' . request('filter') : 'There are currently no pending intern applications.' }}</p>
    </div>
  @endif

  <!-- Documents Modal -->
  <div id="documentsModal" class="modal">
    <div class="modal-content" style="max-width: 95%; height: 90%;">
      <span class="close" onclick="closeDocumentsModal()">&times;</span>
      <h3>Intern Documents</h3>

      <div class="doc-layout" style="display: flex; height: calc(100% - 60px); gap: 16px;">
        <!-- Sidebar -->
        <div class="doc-sidebar" style="width: 260px; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; height: 100%; overflow-y:auto;">
          <div style="font-weight:600; color:#475569; margin-bottom:8px;">Pre-Deployment</div>
          <button class="doc-tab active" style="width: 100%; text-align: left; margin-bottom: 8px;" onclick="switchDocumentTab('resume')">📄 Resume</button>
          <button class="doc-tab" style="width: 100%; text-align: left; margin-bottom: 8px;" onclick="switchDocumentTab('application')">📝 Application Letter</button>
          <button class="doc-tab" style="width: 100%; text-align: left; margin-bottom: 8px;" onclick="switchDocumentTab('medical')">🧾 Medical Certificate</button>
          <button class="doc-tab" style="width: 100%; text-align: left; margin-bottom: 8px;" onclick="switchDocumentTab('insurance')">🛡️ Insurance</button>
          <button class="doc-tab" style="width: 100%; text-align: left; margin-bottom: 8px;" onclick="switchDocumentTab('acceptance')">✅ Acceptance Letter</button>
          <button class="doc-tab" style="width: 100%; text-align: left; margin-bottom: 12px;" onclick="switchDocumentTab('parents')">👪 Notarized Parent's Waiver</button>

          <div style="font-weight:600; color:#475569; margin:8px 0;">Mid-Deployment</div>
          <button class="doc-tab" style="width: 100%; text-align: left; margin-bottom: 8px;" onclick="switchDocumentTab('memorandum')">📑 Memorandum of Agreement</button>
          <button class="doc-tab" style="width: 100%; text-align: left; margin-bottom: 12px;" onclick="switchDocumentTab('contract')">🖋️ Internship Contract</button>

          <div style="font-weight:600; color:#475569; margin:8px 0;">Deployment</div>
          <button class="doc-tab" style="width: 100%; text-align: left;" onclick="switchDocumentTab('recommendation')">🎓 Recommendation Letter</button>
          <button class="doc-tab" style="width: 100%; text-align: left;" onclick="switchDocumentTab('endorsement')">📄 Endorsement (HTML)</button>
        </div>

        <!-- Content -->
        <div class="doc-content" style="flex: 1; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #fff;">
          <!-- Resume -->
          <div id="resumeTab" class="document-tab active" style="height: 100%; display: flex; flex-direction: column;">
            <div style="padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"><h4 style="margin:0; color:#3b82f6;">Resume</h4></div>
            <div style="flex:1; overflow:hidden;"><iframe id="resumeFrame" style="width:100%; height:100%; border:none;" frameborder="0"></iframe></div>
          </div>

          <!-- Application Letter -->
          <div id="applicationTab" class="document-tab" style="height: 100%; display: none; flex-direction: column;">
            <div style="padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"><h4 style="margin:0; color:#3b82f6;">Application Letter</h4></div>
            <div style="flex:1; overflow:hidden;"><iframe id="applicationLetterFrame" style="width:100%; height:100%; border:none;" frameborder="0"></iframe></div>
          </div>

          <!-- Medical Certificate -->
          <div id="medicalTab" class="document-tab" style="height: 100%; display: none; flex-direction: column;">
            <div style="padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"><h4 style="margin:0; color:#3b82f6;">Medical Certificate</h4></div>
            <div style="flex:1; overflow:hidden;"><iframe id="medicalCertificateFrame" style="width:100%; height:100%; border:none;" frameborder="0"></iframe></div>
          </div>

          <!-- Insurance -->
          <div id="insuranceTab" class="document-tab" style="height: 100%; display: none; flex-direction: column;">
            <div style="padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"><h4 style="margin:0; color:#3b82f6;">Insurance</h4></div>
            <div style="flex:1; overflow:hidden;"><iframe id="insuranceFrame" style="width:100%; height:100%; border:none;" frameborder="0"></iframe></div>
          </div>

          <!-- Acceptance Letter -->
          <div id="acceptanceTab" class="document-tab" style="height: 100%; display: none; flex-direction: column;">
            <div style="padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"><h4 style="margin:0; color:#3b82f6;">Acceptance Letter</h4></div>
            <div style="flex:1; overflow:hidden;"><iframe id="acceptanceLetterFrame" style="width:100%; height:100%; border:none;" frameborder="0"></iframe></div>
          </div>

          <!-- Parent's Waiver -->
          <div id="parentsTab" class="document-tab" style="height: 100%; display: none; flex-direction: column;">
            <div style="padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"><h4 style="margin:0; color:#3b82f6;">Notarized Parent's Waiver</h4></div>
            <div style="flex:1; overflow:hidden;"><iframe id="parentsWaiverFrame" style="width:100%; height:100%; border:none;" frameborder="0"></iframe></div>
          </div>

          <!-- Memorandum of Agreement -->
          <div id="memorandumTab" class="document-tab" style="height: 100%; display: none; flex-direction: column;">
            <div style="padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"><h4 style="margin:0; color:#3b82f6;">Memorandum of Agreement</h4></div>
            <div style="flex:1; overflow:hidden;"><iframe id="memorandumFrame" style="width:100%; height:100%; border:none;" frameborder="0"></iframe></div>
          </div>

          <!-- Internship Contract -->
          <div id="contractTab" class="document-tab" style="height: 100%; display: none; flex-direction: column;">
            <div style="padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"><h4 style="margin:0; color:#3b82f6;">Internship Contract</h4></div>
            <div style="flex:1; overflow:hidden;"><iframe id="contractFrame" style="width:100%; height:100%; border:none;" frameborder="0"></iframe></div>
          </div>

          <!-- Recommendation Letter -->
          <div id="recommendationTab" class="document-tab" style="height: 100%; display: none; flex-direction: column;">
            <div style="padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"><h4 style="margin:0; color:#3b82f6;">Recommendation Letter</h4></div>
            <div style="flex:1; overflow:hidden;"><iframe id="recommendationFrame" style="width:100%; height:100%; border:none;" frameborder="0"></iframe></div>
          </div>

          <!-- Endorsement (HTML) -->
          <div id="endorsementTab" class="document-tab" style="height: 100%; display: none; flex-direction: column;">
            <div style="padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"><h4 style="margin:0; color:#3b82f6;">Endorsement (Auto-generated)</h4></div>
            <div style="flex:1; overflow:hidden;"><iframe id="endorsementFrame" style="width:100%; height:100%; border:none;" frameborder="0"></iframe></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeEditModal()">&times;</span>
      <h3>Edit Intern</h3>
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <label>First Name:</label>
        <input type="text" name="first_name" id="edit_first_name" required>
        <label>Last Name:</label>
        <input type="text" name="last_name" id="edit_last_name" required>
        <label>Course:</label>
        <input type="text" name="course" id="edit_course" required>
        <label>Section:</label>
        <input type="text" name="section" id="edit_section" required>
        <button type="submit" class="button edit">💾 Save</button>
      </form>
    </div>
  </div>

  <!-- Accept Confirmation Modal -->
 <div id="acceptConfirmModal" class="confirm-modal hidden">
  <div class="confirm-modal-content">
    <div class="confirm-icon">✅</div>
    <div class="confirm-text" id="acceptConfirmText"></div>
    <div class="confirm-buttons">
      <button class="confirm-btn yes" id="acceptConfirmYes">
        <span>✓</span> Yes, Accept
      </button>
      <button class="confirm-btn no" onclick="closeAcceptConfirmModal()">
        <span>✗</span> Cancel
      </button>
    </div>
  </div>
</div>

<div id="deleteConfirmModal" class="confirm-modal hidden">
  <div class="confirm-modal-content">
    <div class="confirm-icon" style="color: #ef4444;">⚠️</div>
    <div class="confirm-text" id="deleteConfirmText"></div>
    <div class="confirm-buttons">
      <button class="confirm-btn yes" id="deleteConfirmYes">
        <span>✓</span> Yes, Delete
      </button>
      <button class="confirm-btn no" onclick="closeDeleteConfirmModal()">
        <span>✗</span> Cancel
      </button>
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

  <!-- Hidden Forms for Actions -->
  <form id="hiddenAcceptForm" method="POST" style="display: none;">
    @csrf
  </form>

  <form id="hiddenDeleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
  </form>

  <script>
    // Check for Laravel session messages and show popup
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

    // Accept Confirmation Modal Functions
    function showAcceptConfirmModal(actionType, internName, actionUrl) {
      const modal = document.getElementById('acceptConfirmModal');
      const text = document.getElementById('acceptConfirmText');
      const yesBtn = document.getElementById('acceptConfirmYes');
      
      let actionText = '';
      switch(actionType) {
        case 'account':
          actionText = 'accept the account for';
          break;
        case 'pre-deployment':
          actionText = 'accept the pre-deployment phase for';
          break;
        case 'mid-deployment':
          actionText = 'accept the mid-deployment phase for';
          break;
        case 'deployment':
          actionText = 'accept the deployment phase for';
          break;
        case 'intern':
          actionText = 'fully accept the intern';
          break;
        default:
          actionText = 'accept';
      }
      
      text.innerHTML = `Are you sure you want to <strong>${actionText}</strong><br><strong>${internName}</strong>?`;
      
      yesBtn.onclick = function() {
        const form = document.getElementById('hiddenAcceptForm');
        form.action = actionUrl;
        form.submit();
        closeAcceptConfirmModal();
      };
      
      modal.style.display = 'block';
    }

    function closeAcceptConfirmModal() {
      document.getElementById('acceptConfirmModal').style.display = 'none';
    }

    // Delete Confirmation Modal Functions
    function showDeleteConfirmModal(internName, actionUrl) {
      const modal = document.getElementById('deleteConfirmModal');
      const text = document.getElementById('deleteConfirmText');
      const yesBtn = document.getElementById('deleteConfirmYes');
      
      text.innerHTML = `Are you sure you want to <strong>delete</strong><br><strong>${internName}</strong>?<br><small style="color: #6b7280;">This action cannot be undone.</small>`;
      
      yesBtn.onclick = function() {
        const form = document.getElementById('hiddenDeleteForm');
        form.action = actionUrl;
        form.submit();
        closeDeleteConfirmModal();
      };
      
      modal.style.display = 'block';
    }

    function closeDeleteConfirmModal() {
      document.getElementById('deleteConfirmModal').style.display = 'none';
    }

    function openDocumentsModal(resume, appLetter, medicalCert, insurance, acceptanceLetter, parentsWaiver, memorandum, contract, recommendation, currentPhase, endorsementUrl) {
      // Load ALL documents regardless of current phase
      
      // Pre-Deployment documents
      if (resume && resume !== '#') {
        document.getElementById('resumeFrame').src = resume;
      } else {
        document.getElementById('resumeFrame').src = 'about:blank';
      }
      
      if (appLetter && appLetter !== '#') {
        document.getElementById('applicationLetterFrame').src = appLetter;
      } else {
        document.getElementById('applicationLetterFrame').src = 'about:blank';
      }
      
      if (medicalCert && medicalCert !== '#') {
        document.getElementById('medicalCertificateFrame').src = medicalCert;
      } else {
        document.getElementById('medicalCertificateFrame').src = 'about:blank';
      }
      
      if (insurance && insurance !== '#') {
        document.getElementById('insuranceFrame').src = insurance;
      } else {
        document.getElementById('insuranceFrame').src = 'about:blank';
      }
      
      if (acceptanceLetter && acceptanceLetter !== '#') {
        document.getElementById('acceptanceLetterFrame').src = acceptanceLetter;
      } else {
        document.getElementById('acceptanceLetterFrame').src = 'about:blank';
      }
      
      if (parentsWaiver && parentsWaiver !== '#') {
        document.getElementById('parentsWaiverFrame').src = parentsWaiver;
      } else {
        document.getElementById('parentsWaiverFrame').src = 'about:blank';
      }
      
      // Mid-Deployment documents
      if (memorandum && memorandum !== '#') {
        document.getElementById('memorandumFrame').src = memorandum;
      } else {
        document.getElementById('memorandumFrame').src = 'about:blank';
      }
      
      if (contract && contract !== '#') {
        document.getElementById('contractFrame').src = contract;
      } else {
        document.getElementById('contractFrame').src = 'about:blank';
      }
      
      // Deployment documents
      if (recommendation && recommendation !== '#') {
        document.getElementById('recommendationFrame').src = recommendation;
      } else {
        document.getElementById('recommendationFrame').src = 'about:blank';
      }

      // Endorsement HTML (auto-generated)
      document.getElementById('endorsementFrame').src = endorsementUrl || 'about:blank';
      
      // Determine which tab to show first based on current phase
      let defaultTab = 'resume'; // Default fallback
      
      switch(currentPhase) {
        case 'pre_deployment':
          defaultTab = 'resume';
          break;
        case 'mid_deployment':
          defaultTab = 'memorandum';
          break;
        case 'deployment':
          defaultTab = 'recommendation';
          break;
        default:
          defaultTab = 'resume';
      }
      
      // Switch to the appropriate default tab
      switchDocumentTab(defaultTab);
      
      // Show the modal
      document.getElementById('documentsModal').style.display = 'block';
    }

    function closeDocumentsModal() {
      document.getElementById('documentsModal').style.display = 'none';
      // Clear all iframe sources
      document.getElementById('applicationLetterFrame').src = '';
      document.getElementById('parentsWaiverFrame').src = '';
      document.getElementById('acceptanceLetterFrame').src = '';
    }

    function switchDocumentTab(tabName) {
      const tabs = ['resume', 'application', 'medical', 'insurance', 'acceptance', 'parents', 'memorandum', 'contract', 'recommendation', 'endorsement'];
      tabs.forEach(name => {
        const tabEl = document.getElementById(name + 'Tab');
        const btnEl = document.querySelector(`[onclick="switchDocumentTab('${name}')"]`);
        if (!tabEl || !btnEl) return;
        if (name === tabName) {
          tabEl.style.display = 'flex';
          btnEl.classList.add('active');
        } else {
          tabEl.style.display = 'none';
          btnEl.classList.remove('active');
        }
      });
    }

    function openEditModal(id, first, last, course, section) {
      document.getElementById('edit_first_name').value = first;
      document.getElementById('edit_last_name').value = last;
      document.getElementById('edit_course').value = course;
      document.getElementById('edit_section').value = section;
      document.getElementById('editForm').action = `/interns/${id}`;
      document.getElementById('editModal').style.display = 'block';
    }

    function closeEditModal() {
      document.getElementById('editModal').style.display = 'none';
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
      const editModal = document.getElementById('editModal');
      const messageModal = document.getElementById('messageModal');
      const acceptConfirmModal = document.getElementById('acceptConfirmModal');
      const deleteConfirmModal = document.getElementById('deleteConfirmModal');
      const documentsModal = document.getElementById('documentsModal');
      
      if (event.target === editModal) {
        closeEditModal();
      }
      if (event.target === messageModal) {
        closeMessageModal();
      }
      if (event.target === acceptConfirmModal) {
        closeAcceptConfirmModal();
      }
      if (event.target === deleteConfirmModal) {
        closeDeleteConfirmModal();
      }
      if (event.target === documentsModal) {
        closeDocumentsModal();
      }
    }

    // Add hover effects for confirmation buttons
 document.addEventListener('DOMContentLoaded', function () {
  const style = document.createElement('style');
  style.textContent = `
    .confirm-modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(5px);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .confirm-modal-content {
      background: white;
      padding: 30px;
      border-radius: 15px;
      text-align: center;
      max-width: 400px;
      width: 90%;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .confirm-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      transition: all 0.2s ease;
    }

    .confirm-btn.yes:hover {
      background: #16a34a !important;
    }

    .confirm-btn.no:hover {
      background: #dc2626 !important;
    }

    .confirm-btn:active {
      transform: translateY(0);
    }
  `;
  document.head.appendChild(style);
});

  </script>

</body>
</html>
@endsection