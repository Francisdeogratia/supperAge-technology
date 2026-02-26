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
            <form id="filterForm" method="GET" action="{{ route('admin.transactions.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small">User</label>
                        <select name="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ '@' . $user->username }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Currency</label>
                        <select name="currency" class="form-select">
                            <option value="">All</option>
                            @foreach($currencies as $code => $name)
                                <option value="{{ $code }}" {{ request('currency') == $code ? 'selected' : '' }}>{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="task_reward" {{ request('type') == 'task_reward' ? 'selected' : '' }}>Task Reward</option>
                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                            <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">From Date</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">To Date</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search by transaction ID, reference, or description..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-6 text-end">
                        @if(request()->hasAny(['user_id', 'currency', 'type', 'date_from', 'date_to', 'search']))
                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-1"></i> Clear Filters
                            </a>
                        @endif
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
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User Balance Summaries</h5>
            <small class="text-muted" id="summaryPageInfo"></small>
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
                    <tbody id="summaryTbody"></tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <button class="btn btn-sm btn-outline-primary" id="summaryPrev" onclick="changeSummaryPage(-1)">
                <i class="fas fa-chevron-left me-1"></i> Prev
            </button>
            <span id="summaryPageLabel" class="text-muted small"></span>
            <button class="btn btn-sm btn-outline-primary" id="summaryNext" onclick="changeSummaryPage(1)">
                Next <i class="fas fa-chevron-right ms-1"></i>
            </button>
        </div>
    </div>

    @php
        // Build a flat array of user summary groups for JS
        $summaryData = [];
        foreach ($userSummaries as $userId => $currencyGroup) {
            $user = $users[$userId] ?? null;
            $rows = [];
            foreach ($currencyGroup as $cd) {
                $rows[] = [
                    'currency' => $cd->currency,
                    'total_amount' => $cd->total_amount,
                    'transaction_count' => $cd->transaction_count,
                ];
            }
            $summaryData[] = [
                'user_name' => $user->name ?? 'Unknown',
                'user_username' => $user->username ?? 'N/A',
                'user_avatar' => $user && $user->profilepix ? asset($user->profilepix) : null,
                'rows' => $rows,
            ];
        }
    @endphp

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

// Submit search on Enter key
document.querySelector('#filterForm input[name="search"]').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('filterForm').submit();
    }
});

// ---- User Balance Summaries Pagination ----
var summaryAllData = @json($summaryData);
var summaryPerPage = 25;
var summaryCurrentPage = 1;
var summaryTotalPages = Math.ceil(summaryAllData.length / summaryPerPage);

function renderSummaryPage() {
    var start = (summaryCurrentPage - 1) * summaryPerPage;
    var end = start + summaryPerPage;
    var pageData = summaryAllData.slice(start, end);
    var tbody = document.getElementById('summaryTbody');
    var html = '';

    if (pageData.length === 0) {
        html = '<tr><td colspan="4" class="text-center text-muted py-4">No user summaries found.</td></tr>';
    } else {
        pageData.forEach(function(item) {
            var rowspan = item.rows.length;
            var avatarHtml = item.user_avatar
                ? '<img src="' + item.user_avatar + '" alt="' + item.user_name + '" class="rounded-circle me-2" width="40" height="40" style="object-fit:cover;">'
                : '<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width:40px;height:40px;font-size:18px;">' + item.user_name.charAt(0).toUpperCase() + '</div>';

            item.rows.forEach(function(row, idx) {
                html += '<tr>';
                if (idx === 0) {
                    html += '<td rowspan="' + rowspan + '" class="align-middle"><div class="d-flex align-items-center">' + avatarHtml + '<div><div class="fw-bold">' + item.user_name + '</div><small class="text-muted">@' + item.user_username + '</small></div></div></td>';
                }
                html += '<td><span class="badge bg-secondary">' + row.currency + '</span></td>';
                var amountClass = row.total_amount >= 0 ? 'text-success' : 'text-danger';
                html += '<td class="text-end"><strong class="' + amountClass + '">' + Number(row.total_amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ' + row.currency + '</strong></td>';
                html += '<td class="text-center"><span class="badge bg-info">' + row.transaction_count + '</span></td>';
                html += '</tr>';
            });
        });
    }

    tbody.innerHTML = html;

    document.getElementById('summaryPrev').disabled = (summaryCurrentPage <= 1);
    document.getElementById('summaryNext').disabled = (summaryCurrentPage >= summaryTotalPages);
    document.getElementById('summaryPageLabel').textContent = 'Page ' + summaryCurrentPage + ' of ' + (summaryTotalPages || 1);
    document.getElementById('summaryPageInfo').textContent = summaryAllData.length + ' users total';
}

function changeSummaryPage(dir) {
    var newPage = summaryCurrentPage + dir;
    if (newPage < 1 || newPage > summaryTotalPages) return;
    summaryCurrentPage = newPage;
    renderSummaryPage();
}

// Render first page on load
renderSummaryPage();
</script>
@endsection