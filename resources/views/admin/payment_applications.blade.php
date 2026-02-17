@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Payment Applications</h2>
        <a href="{{ route('admin.dashboard.now') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Payment Method</th>
                            <th>Bank Details</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Applied On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr id="app-row-{{ $app->id }}">
                            <td>{{ $app->id }}</td>
                            <td>
                                <strong>{{ $app->name }}</strong><br>
                                <small class="text-muted">{{'@'. $app->username }}</small><br>
                                <small>{{ $app->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $app->payment_method)) }}
                                </span>
                            </td>
                            <td>
                                @if($app->payment_method == 'bank_transfer')
                                    <strong>Bank:</strong> {{ $app->bank_name }}<br>
                                    <strong>Account:</strong> {{ $app->account_number }}<br>
                                    <strong>Name:</strong> {{ $app->account_name }}
                                @elseif($app->payment_method == 'paypal')
                                    <strong>PayPal:</strong> {{ $app->paypal_email }}
                                @else
                                    <span class="text-muted">Flutterwave</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-success">
                                    {{ $app->currency }} {{ number_format($app->amount_requested, 2) }}
                                </strong>
                            </td>
                            <td>
                                @if($app->reason)
                                    <small>{{ Str::limit($app->reason, 50) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($app->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($app->status == 'approved')
                                    <span class="badge bg-info">Approved</span>
                                @elseif($app->status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                    @if($app->paid_at)
                                        <br><small>{{ \Carbon\Carbon::parse($app->paid_at)->format('M d, Y') }}</small>
                                    @endif
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y H:i') }}</td>
                            <td>
                                @if($app->status == 'pending')
                                    <button class="btn btn-sm btn-success mb-1" 
                                            onclick="approveApplication({{ $app->id }})">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-danger mb-1" 
                                            onclick="rejectApplication({{ $app->id }})">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                @endif
                                
                                @if($app->status == 'approved' || $app->status == 'pending')
                                    <button class="btn btn-sm btn-primary mb-1" 
                                            onclick="payApplication({{ $app->id }}, {{ $app->user_id }}, {{ $app->amount_requested }}, '{{ $app->currency }}')">
                                        <i class="fas fa-money-bill"></i> Pay Now
                                    </button>
                                @endif
                                
                                <button class="btn btn-sm btn-info mb-1" 
                                        onclick="viewDetails({{ $app->id }})">
                                    <i class="fas fa-eye"></i> Details
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">No payment applications found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Pay Modal -->
<div class="modal fade" id="payModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Process Payment Withdrawal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>⚠️ Important:</strong> This will DEBIT the user's wallet and mark the application as paid.
                </div>
                
                <div class="card mb-3 bg-light">
                    <div class="card-body">
                        <h6>Transaction Breakdown:</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td><strong>Amount Requested:</strong></td>
                                <td class="text-end" id="amount-requested">-</td>
                            </tr>
                            <tr class="text-success">
                                <td><strong>User Receives (70%):</strong></td>
                                <td class="text-end" id="user-receives">-</td>
                            </tr>
                            <tr class="text-info">
                                <td><strong>Platform Fee (30%):</strong></td>
                                <td class="text-end" id="platform-fee">-</td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Wallet Deduction:</strong></td>
                                <td class="text-end text-danger" id="wallet-deduction">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mb-3 d-none">
                    <label>Amount:</label>
                    <input type="number" id="pay-amount" class="form-control" readonly>
                </div>
                <div class="mb-3 d-none">
                    <label>Currency:</label>
                    <input type="text" id="pay-currency" class="form-control" readonly>
                </div>
                <input type="hidden" id="pay-user-id">
                <input type="hidden" id="payment-app-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmPayment()">
                    <i class="fas fa-check"></i> Confirm & Process Payment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="details-content">
                <!-- Content loaded via JS -->
            </div>
        </div>
    </div>
</div>


<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
function approveApplication(id) {
    if(confirm('Approve this payment application?')) {
        fetch(`/admin/payment-applications/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            location.reload();
        });
    }
}

function rejectApplication(id) {
    const reason = prompt('Reason for rejection (optional):');
    
    fetch(`/admin/payment-applications/${id}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}

function payApplication(appId, userId, amount, currency) {
    const userReceives = amount * 0.70;
    const platformFee = amount * 0.30;
    
    document.getElementById('pay-amount').value = amount;
    document.getElementById('pay-currency').value = currency;
    document.getElementById('pay-user-id').value = userId;
    document.getElementById('payment-app-id').value = appId;
    
    // Update breakdown display
    document.getElementById('amount-requested').textContent = currency + ' ' + amount.toFixed(2);
    document.getElementById('user-receives').textContent = currency + ' ' + userReceives.toFixed(2);
    document.getElementById('platform-fee').textContent = currency + ' ' + platformFee.toFixed(2);
    document.getElementById('wallet-deduction').textContent = '-' + currency + ' ' + amount.toFixed(2);
    
    new bootstrap.Modal(document.getElementById('payModal')).show();
}

function confirmPayment() {
    const userId = document.getElementById('pay-user-id').value;
    const amount = document.getElementById('pay-amount').value;
    const currency = document.getElementById('pay-currency').value;
    const appId = document.getElementById('payment-app-id').value;
    
    fetch(`/admin/users/${userId}/pay`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            amount: amount, 
            currency: currency,
            payment_app_id: appId
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.success) {
            location.reload();
        }
    });
}

function viewDetails(id) {
    // Fetch application details via AJAX
    fetch(`/admin/payment-applications/${id}/details`, {
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
            new bootstrap.Modal(document.getElementById('detailsModal')).show();
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