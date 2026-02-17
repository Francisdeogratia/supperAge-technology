<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P7ZNRWKS7Z"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-P7ZNRWKS7Z');
    </script>

    <meta charset="utf-8">
    <meta name="author" content="omoha Ekenedilichukwu Francis">
    <meta name="description" content="SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money.">
    <meta name="keywords" content="SupperAge, social financial app, earn money online, chat and earn, online marketplace, digital wallet, social networking, e-commerce platform">
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />

    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>first dashbord - SupperAge</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    </head>


@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Users Management</h2>
        <a href="{{ route('admin.dashboard.now') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.now') }}" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, username, email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr id="user-row-{{ $user->id }}">
                            <td>{{ $user->id }}</td>
                            <td>
                                <img src="{{ $user->profileimg ?? asset('images/default-avatar.png') }}" 
                                     class="rounded-circle" width="40" height="40" alt="Profile">
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                @if($user->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Suspended</span>
                                @endif
                                
                                @if($user->unsetacct == 'locked')
                                    <span class="badge bg-warning">Locked</span>
                                @endif
                            </td>
                            <td><span class="badge bg-info">{{ ucfirst($user->role ?? 'user') }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.users.edit.now', $user->id) }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.users.access.now', $user->id) }}">
                                            <i class="fas fa-sign-in-alt"></i> Access Account
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.users.message.now', $user->id) }}">
                                            <i class="fas fa-envelope"></i> Send Message
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.users.posts.now', $user->id) }}">
                                            <i class="fas fa-images"></i> View Posts
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.users.tales.now', $user->id) }}">
                                            <i class="fas fa-book"></i> View Tales
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="payUser({{ $user->id }})">
                                            <i class="fas fa-money-bill"></i> Pay User
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        @if($user->status == 'active')
                                        <li><a class="dropdown-item text-warning" href="#" onclick="suspendUser({{ $user->id }})">
                                            <i class="fas fa-ban"></i> Suspend
                                        </a></li>
                                        @else
                                        <li><a class="dropdown-item text-success" href="#" onclick="enableUser({{ $user->id }})">
                                            <i class="fas fa-check"></i> Enable
                                        </a></li>
                                        @endif
                                        <li><a class="dropdown-item" href="#" onclick="toggleLock({{ $user->id }})">
                                            <i class="fas fa-lock"></i> {{ $user->unsetacct == 'locked' ? 'Unlock' : 'Lock' }}
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser({{ $user->id }})">
                                            <i class="fas fa-trash"></i> Delete
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Suspend Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Suspend User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label>Suspension Duration (days):</label>
                <input type="number" id="suspend-days" class="form-control" value="7" min="1">
                <input type="hidden" id="suspend-user-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="confirmSuspend()">Suspend</button>
            </div>
        </div>
    </div>
</div>

<!-- Pay User Modal -->
<div class="modal fade" id="payModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pay User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
    <div class="alert alert-info" id="app-info">
        Loading application details...
    </div>
    
    <div class="mb-3">
        <label>Payment Application ID:</label>
        <input type="text" id="payment-app-id" class="form-control" readonly>
        <small class="text-muted">Auto-filled from user's pending application</small>
    </div>
    
    <div class="mb-3">
        <label>Amount:</label>
        <input type="number" id="pay-amount" class="form-control" min="1" step="0.01">
    </div>
    
    <div class="mb-3">
        <label>Currency:</label>
        <select id="pay-currency" class="form-select form-control">
            <option value="NGN">NGN</option>
            <option value="USD">USD</option>
            <option value="GBP">GBP</option>
            <option value="EUR">EUR</option>
        </select>
    </div>
    
    <input type="hidden" id="pay-user-id">
</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmPay()">Pay Now</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    
function deleteUser(id) {
    if(confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        fetch(`/admin/users/${id}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                document.getElementById(`user-row-${id}`).remove();
                alert(data.message);
            }
        });
    }
}

function suspendUser(id) {
    document.getElementById('suspend-user-id').value = id;
    $('#suspendModal').modal('show'); // ✅ Bootstrap 4 syntax
}

function confirmSuspend() {
    const id = document.getElementById('suspend-user-id').value;
    const days = document.getElementById('suspend-days').value;
    
    fetch(`/admin/users/${id}/suspend`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ days: days })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            location.reload();
        }
    });
}

function enableUser(id) {
    fetch(`/admin/users/${id}/enable`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            location.reload();
        }
    });
}

function toggleLock(id) {
    fetch(`/admin/users/${id}/toggle-lock`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            location.reload();
        }
    });
}

function payUser(id) {
    document.getElementById('pay-user-id').value = id;
    document.getElementById('app-info').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    
    // Fetch pending application
    fetch(`/admin/users/${id}/pending-application`, {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.application) {
            document.getElementById('payment-app-id').value = data.application.id;
            document.getElementById('pay-amount').value = data.application.amount_requested;
            document.getElementById('pay-currency').value = data.application.currency;
            document.getElementById('app-info').innerHTML = 
                `<i class="fas fa-check-circle text-success"></i> Application #${data.application.id} loaded`;
        } else {
            document.getElementById('payment-app-id').value = '';
            document.getElementById('pay-amount').value = '';
            document.getElementById('pay-currency').value = 'NGN';
            document.getElementById('app-info').innerHTML = 
                '<i class="fas fa-exclamation-triangle text-warning"></i> No pending application found for this user. Enter manual payment details.';
        }
    })
    .catch(error => {
        document.getElementById('app-info').innerHTML = 
            '<i class="fas fa-times-circle text-danger"></i> Error loading application';
    });
    
    $('#payModal').modal('show');
}

function confirmPay() {
    const userId = document.getElementById('pay-user-id').value;
    const amount = document.getElementById('pay-amount').value;
    const currency = document.getElementById('pay-currency').value;
    const paymentAppId = document.getElementById('payment-app-id').value;
    
    if(!amount || amount <= 0) {
        alert('Please enter a valid amount');
        return;
    }
    
    if(!paymentAppId) {
        if(!confirm('No payment application found. Create a manual payment?')) {
            return;
        }
    }
    
    fetch(`/admin/users/${userId}/pay`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            amount: amount, 
            currency: currency,
            payment_app_id: paymentAppId || null
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.success) {
            $('#payModal').modal('hide');
            location.reload();
        }
    })
    .catch(error => {
        alert('Payment failed: ' + error.message);
    });
}
</script>
@endsection