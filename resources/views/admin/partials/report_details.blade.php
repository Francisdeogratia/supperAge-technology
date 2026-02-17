<div class="row">
    <div class="col-md-6">
        <h6 class="text-primary"><i class="fas fa-user"></i> Reporter Information</h6>
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $report->reporter_img ?? asset('images/default-avatar.png') }}" 
                         class="rounded-circle me-3" width="60" height="60" alt="Reporter">
                    <div>
                        <h6 class="mb-0">{{ $report->reporter_name }}</h6>
                        <small class="text-muted">{{'@'.$report->reporter_username }}</small>
                    </div>
                </div>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $report->reporter_email }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <h6 class="text-danger"><i class="fas fa-flag"></i> Reported User Information</h6>
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $report->reported_img ?? asset('images/default-avatar.png') }}" 
                         class="rounded-circle me-3" width="60" height="60" alt="Reported">
                    <div>
                        <h6 class="mb-0">{{ $report->reported_name }}</h6>
                        <small class="text-muted">{{'@'.$report->reported_username }}</small>
                        @if($report->reported_status == 'suspended')
                            <br><span class="badge bg-danger mt-1">Suspended</span>
                        @else
                            <br><span class="badge bg-success mt-1">Active</span>
                        @endif
                    </div>
                </div>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $report->reported_email }}</td>
                    </tr>
                    @if($report->disabled_until)
                    <tr>
                        <td><strong>Suspended Until:</strong></td>
                        <td class="text-danger">{{ \Carbon\Carbon::parse($report->disabled_until)->format('M d, Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-primary"><i class="fas fa-exclamation-triangle"></i> Report Details</h6>
        <div class="card">
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td width="150"><strong>Report ID:</strong></td>
                        <td>#{{ $report->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Reported On:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('M d, Y H:i A') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
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
                    </tr>
                    <tr>
                        <td><strong>Reason:</strong></td>
                        <td>
                            <div class="alert alert-warning mb-0">
                                {{ $report->reason }}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if($report->action_taken)
<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-success"><i class="fas fa-check-circle"></i> Action Taken</h6>
        <div class="card">
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td width="150"><strong>Action:</strong></td>
                        <td>
                            @if($report->action_taken == 'warned')
                                <span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> User Warned</span>
                            @elseif($report->action_taken == 'suspended')
                                <span class="badge bg-danger"><i class="fas fa-ban"></i> User Suspended</span>
                            @elseif($report->action_taken == 'deleted')
                                <span class="badge bg-dark"><i class="fas fa-trash"></i> User Deleted</span>
                            @endif
                        </td>
                    </tr>
                    @if($report->admin_note)
                    <tr>
                        <td><strong>Admin Note:</strong></td>
                        <td>
                            <div class="alert alert-info mb-0">
                                {{ $report->admin_note }}
                            </div>
                        </td>
                    </tr>
                    @endif
                    @if($report->reviewed_by)
                    <tr>
                        <td><strong>Reviewed By:</strong></td>
                        <td>{{ $report->reviewer_name }}</td>
                    </tr>
                    @endif
                    @if($report->reviewed_at)
                    <tr>
                        <td><strong>Reviewed At:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($report->reviewed_at)->format('M d, Y H:i A') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endif