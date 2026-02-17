@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-flag"></i> User Reports</h2>
        <a href="{{ route('admin.dashboard.now') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $status == 'pending' ? 'active' : '' }}" 
               href="{{ route('admin.user.reports', ['status' => 'pending']) }}">
                Pending
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'reviewed' ? 'active' : '' }}" 
               href="{{ route('admin.user.reports', ['status' => 'reviewed']) }}">
                Reviewed
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'action_taken' ? 'active' : '' }}" 
               href="{{ route('admin.user.reports', ['status' => 'action_taken']) }}">
                Action Taken
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'dismissed' ? 'active' : '' }}" 
               href="{{ route('admin.user.reports', ['status' => 'dismissed']) }}">
                Dismissed
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" 
               href="{{ route('admin.user.reports', ['status' => 'all']) }}">
                All Reports
            </a>
        </li>
    </ul>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Reporter</th>
                            <th>Reported User</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action Taken</th>
                            <th>Reported On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr id="report-row-{{ $report->id }}">
                            <td>{{ $report->id }}</td>
                            <td>
                                <strong>{{ $report->reporter_name }}</strong><br>
                                <small class="text-muted">{{'@'. $report->reporter_username }}</small>
                            </td>
                            <td>
                                <strong>{{ $report->reported_name }}</strong><br>
                                <small class="text-muted">{{'@'. $report->reported_username }}</small><br>
                                @if($report->reported_status == 'suspended')
                                    <span class="badge bg-danger">Suspended</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="alert alert-warning py-2 px-3 mb-0">
                                    {{ Str::limit($report->reason, 100) }}
                                </div>
                            </td>
                            <td>
                                @if($report->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($report->status == 'action_taken')
                                    <span class="badge bg-success">Action Taken</span>
                                @elseif($report->status == 'dismissed')
                                    <span class="badge bg-secondary">Dismissed</span>
                                @else
                                    <span class="badge bg-info">Reviewed</span>
                                @endif
                            </td>
                            <td>
                                @if($report->action_taken)
                                    @if($report->action_taken == 'warned')
                                        <span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Warned</span>
                                    @elseif($report->action_taken == 'suspended')
                                        <span class="badge bg-danger"><i class="fas fa-ban"></i> Suspended</span>
                                    @elseif($report->action_taken == 'deleted')
                                        <span class="badge bg-dark"><i class="fas fa-trash"></i> Deleted</span>
                                    @endif
                                    @if($report->admin_note)
                                        <br><small class="text-muted">{{ Str::limit($report->admin_note, 50) }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($report->created_at)->format('M d, Y H:i') }}</td>
                            <td>
                                @if($report->status == 'pending')
                                    <button class="btn btn-sm btn-warning mb-1" 
                                            onclick="warnUser({{ $report->id }}, '{{ $report->reported_name }}')">
                                        <i class="fas fa-exclamation-triangle"></i> Warn
                                    </button>
                                    <button class="btn btn-sm btn-danger mb-1" 
                                            onclick="blockUser({{ $report->id }}, '{{ $report->reported_name }}')">
                                        <i class="fas fa-ban"></i> Block
                                    </button>
                                    <button class="btn btn-sm btn-dark mb-1" 
                                            onclick="deleteUser({{ $report->id }}, '{{ $report->reported_name }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                    <button class="btn btn-sm btn-secondary mb-1" 
                                            onclick="dismissReport({{ $report->id }})">
                                        <i class="fas fa-times"></i> Dismiss
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-info mb-1" 
                                        onclick="viewDetails({{ $report->id }})">
                                    <i class="fas fa-eye"></i> Details
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No reports found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Warn Modal -->
<div class="modal fade" id="warnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Warn User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>You are about to warn: <strong id="warn-user-name"></strong></p>
                <div class="mb-3">
                    <label>Warning Message:</label>
                    <textarea id="warning-message" class="form-control" rows="4" required></textarea>
                </div>
                <input type="hidden" id="warn-report-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="confirmWarn()">Send Warning</button>
            </div>
        </div>
    </div>
</div>

<!-- Block Modal -->
<div class="modal fade" id="blockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Block User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>You are about to block: <strong id="block-user-name"></strong></p>
                <div class="mb-3">
                    <label>Block Duration (days):</label>
                    <input type="number" id="block-days" class="form-control" value="7" min="1" max="365">
                </div>
                <div class="mb-3">
                    <label>Reason:</label>
                    <textarea id="block-reason" class="form-control" rows="4" required></textarea>
                </div>
                <input type="hidden" id="block-report-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmBlock()">Block User</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Delete User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong>⚠️ WARNING:</strong> This action is PERMANENT and cannot be undone!
                </div>
                <p>You are about to permanently delete: <strong id="delete-user-name"></strong></p>
                <div class="mb-3">
                    <label>Reason for Deletion:</label>
                    <textarea id="delete-reason" class="form-control" rows="4" required></textarea>
                </div>
                <input type="hidden" id="delete-report-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark" onclick="confirmDelete()">Delete Permanently</button>
            </div>
        </div>
    </div>
</div>


<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> Report Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="details-content">
                <!-- Content loaded via JS -->
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function warnUser(reportId, userName) {
    document.getElementById('warn-report-id').value = reportId;
    document.getElementById('warn-user-name').textContent = userName;
    $('#warnModal').modal('show');
}

function confirmWarn() {
    const reportId = document.getElementById('warn-report-id').value;
    const message = document.getElementById('warning-message').value;
    
    if(!message) {
        alert('Please enter a warning message');
        return;
    }
    
    fetch(`/admin/user-reports/${reportId}/warn`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ warning_message: message })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.success) {
            location.reload();
        }
    });
}

function blockUser(reportId, userName) {
    document.getElementById('block-report-id').value = reportId;
    document.getElementById('block-user-name').textContent = userName;
    $('#blockModal').modal('show');
}

function confirmBlock() {
    const reportId = document.getElementById('block-report-id').value;
    const days = document.getElementById('block-days').value;
    const reason = document.getElementById('block-reason').value;
    
    if(!reason) {
        alert('Please enter a reason');
        return;
    }
    
    fetch(`/admin/user-reports/${reportId}/block`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ block_days: days, block_reason: reason })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.success) {
            location.reload();
        }
    });
}

function deleteUser(reportId, userName) {
    document.getElementById('delete-report-id').value = reportId;
    document.getElementById('delete-user-name').textContent = userName;
    $('#deleteModal').modal('show');
}

function confirmDelete() {
    const reportId = document.getElementById('delete-report-id').value;
    const reason = document.getElementById('delete-reason').value;
    
    if(!reason) {
        alert('Please enter a reason for deletion');
        return;
    }
    
    if(!confirm('Are you ABSOLUTELY SURE? This cannot be undone!')) {
        return;
    }
    
    fetch(`/admin/user-reports/${reportId}/delete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ delete_reason: reason })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.success) {
            location.reload();
        }
    });
}

function dismissReport(reportId) {
    const note = prompt('Add a note (optional):');
    
    fetch(`/admin/user-reports/${reportId}/dismiss`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ note: note })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.success) {
            location.reload();
        }
    });
}

function viewDetails(reportId) {
    fetch(`/admin/user-reports/${reportId}/details`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            document.getElementById('details-content').innerHTML = data.html;
            $('#detailsModal').modal('show');
        } else {
            alert('Failed to load details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading details');
    });
}
</script>
@endsection