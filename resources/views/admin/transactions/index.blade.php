@extends('admin.layouts.app')

@section('title', 'Transaction Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Transaction Management</h2>
            <p class="text-muted">View and manage all user wallet transactions</p>
        </div>
    </div>

    <!-- Currency Converter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">Currency Converter</h5>
                    <p class="text-muted small mb-0">Convert all balances to a single currency</p>
                </div>
                <div class="col-md-6">
                    <div class="row g-2">
                        <div class="col-8">
                            <select id="targetCurrency" class="form-select">
                                @foreach($currencies as $code => $name)
                                    <option value="{{ $code }}" {{ $code == 'NGN' ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-primary w-100" onclick="convertAllCurrencies()">
                                <i class="fas fa-exchange-alt me-1"></i> Convert
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('admin.transactions.filter') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small">User</label>
                        <select name="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ '@' . $user->username }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Currency</label>
                        <select name="currency" class="form-select">
                            <option value="">All</option>
                            @foreach($currencies as $code => $name)
                                <option value="{{ $code }}">{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="task_reward">Task Reward</option>
                            <option value="debit">Debit</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">From Date</label>
                        <input type="date" name="date_from" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">To Date</label>
                        <input type="date" name="date_to" class="form-control">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search by transaction ID, reference, or description...">
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="button" class="btn btn-success" onclick="exportTransactions()">
                            <i class="fas fa-download me-1"></i> Export CSV
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- User Summaries Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">User Balance Summaries</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="userSummaryTable">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Currency</th>
                            <th class="text-end">Total Balance</th>
                            <th class="text-center">Transactions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userSummaries as $userId => $currencies)
                            @php
                                $user = $users[$userId] ?? null;
                                $rowspan = count($currencies);
                                $firstRow = true;
                            @endphp
                            @foreach($currencies as $currencyData)
                                <tr>
                                    @if($firstRow)
                                        <td rowspan="{{ $rowspan }}" class="align-middle">
                                            <div class="d-flex align-items-center">
                                                @if($user && $user->profilepix)
                                                    <img src="{{ asset($user->profilepix) }}" 
                                                         alt="{{ $user->name }}" 
                                                         class="rounded-circle me-2" 
                                                         width="40" 
                                                         height="40"
                                                         style="object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 40px; height: 40px; font-size: 18px;">
                                                        {{ $user ? strtoupper(substr($user->name, 0, 1)) : '?' }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $user->name ?? 'Unknown' }}</div>
                                                    <small class="text-muted">{{ '@' . ($user->username ?? 'N/A') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        @php $firstRow = false; @endphp
                                    @endif
                                    <td>
                                        <span class="badge bg-secondary">{{ $currencyData->currency }}</span>
                                    </td>
                                    <td class="text-end">
                                        <strong class="{{ $currencyData->total_amount >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($currencyData->total_amount, 2) }} {{ $currencyData->currency }}
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $currencyData->transaction_count }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- All Transactions Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">All Transactions</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="transactionsList">
                @include('admin.transactions.partials.transaction_list', ['transactions' => $transactions])
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>

<!-- Converted Summary Modal -->
<div class="modal fade" id="convertedModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Converted Balances</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="convertedContent">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function convertAllCurrencies() {
    const targetCurrency = document.getElementById('targetCurrency').value;
    
    // Show loading
    const modal = new bootstrap.Modal(document.getElementById('convertedModal'));
    document.getElementById('convertedContent').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Converting currencies...</p>
        </div>
    `;
    modal.show();

    // Make AJAX request
    fetch('{{ route("admin.transactions.convert") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ target_currency: targetCurrency })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayConvertedResults(data);
        } else {
            document.getElementById('convertedContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Conversion failed. Please try again.
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('convertedContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Failed to convert currencies. Please try again.
            </div>
        `;
    });
}

function displayConvertedResults(data) {
    let html = `
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            All balances converted to <strong>${data.target_currency}</strong>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th class="text-end">Total Balance</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
    `;

    Object.entries(data.conversions).forEach(([userId, conversion]) => {
        const user = data.users[userId];
        
        // Check if user exists
        if (!user) {
            console.warn('User not found for ID:', userId);
            return; // Skip this user
        }
        
        html += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        ${user.profilepix ? 
                            `<img src="${user.profilepix}" alt="${user.name}" class="rounded-circle me-2" width="35" height="35" style="object-fit: cover;">` :
                            `<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">${user.name ? user.name.charAt(0).toUpperCase() : '?'}</div>`
                        }
                        <div>
                            <div class="fw-bold">${user.name || 'Unknown'}</div>
                            <small class="text-muted">@${user.username || 'N/A'}</small>
                        </div>
                    </div>
                </td>
                <td class="text-end">
                    <strong class="text-success fs-5">
                        ${Number(conversion.total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} ${conversion.currency}
                    </strong>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#details-${userId}">
                        <i class="fas fa-eye me-1"></i> View Breakdown
                    </button>
                    <div class="collapse mt-2" id="details-${userId}">
                        <div class="card card-body">
        `;
        
        conversion.details.forEach(detail => {
            html += `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-secondary">${detail.original_currency}</span>
                    <span>${Number(detail.original_amount).toLocaleString('en-US', {minimumFractionDigits: 2})} ${detail.original_currency}</span>
                    <i class="fas fa-arrow-right text-muted"></i>
                    <span class="text-success">${Number(detail.converted_amount).toLocaleString('en-US', {minimumFractionDigits: 2})} ${conversion.currency}</span>
                </div>
            `;
        });
        
        html += `
                        </div>
                    </div>
                </td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>
    `;

    document.getElementById('convertedContent').innerHTML = html;
}

function exportTransactions() {
    window.location.href = '{{ route("admin.transactions.export") }}';
}

// Auto-submit filter form on select change
document.querySelectorAll('#filterForm select').forEach(select => {
    select.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
</script>
@endsection