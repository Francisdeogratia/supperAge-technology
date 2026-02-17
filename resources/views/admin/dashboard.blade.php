@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <h2 class="mb-4">Admin Dashboard</h2>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-1">Total Users</h6>
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="{{ route('admin.users.now') }}" class="text-white text-decoration-none">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-1">Active Users</h6>
                            <h2 class="mb-0">{{ $activeUsers }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <small>Currently active</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-1">Suspended</h6>
                            <h2 class="mb-0">{{ $suspendedUsers }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-user-slash"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="{{ route('admin.users.now', ['status' => 'suspended']) }}" class="text-white text-decoration-none">
                        View Suspended <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-1">Pending Payments</h6>
                            <h2 class="mb-0">{{ $pendingPayments }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="{{ route('admin.payment-applications.now') }}" class="text-white text-decoration-none">
                        Review Now <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Statistics -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-images"></i> Posts Statistics
                </div>
                <div class="card-body">
                    <h3 class="text-primary">{{ $totalPosts }}</h3>
                    <p class="text-muted mb-0">Total Posts Created</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-book"></i> Tales Statistics
                </div>
                <div class="card-body">
                    <h3 class="text-info">{{ $totalTales }}</h3>
                    <p class="text-muted mb-0">Total Tales Created</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.users.now') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-users"></i><br>
                                Manage Users
                            </a>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.payment-applications.now') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-money-check-alt"></i><br>
                                Payment Applications
                            </a>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('update') }}" class="btn btn-secondary btn-lg w-100">
                                <i class="fas fa-home"></i><br>
                                Back to Main Site
                            </a>
                        </div>

                        <div class="col-md-4 mb-4">
    <div class="card bg-danger text-white">
        <div class="card-body">
            <h5 class="card-title">Pending Reports</h5>
            <h2>{{ $pendingReports ?? 0 }}</h2>
            <a href="{{ route('admin.user.reports') }}" class="btn btn-light btn-sm mt-2">View Reports</a>
        </div>
    </div>
</div>




                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity (Optional) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Recent Users</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentUsers = \App\Models\UserRecord::orderBy('created_at', 'desc')->limit(5)->get();
                                @endphp
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ '@'. $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : $user->created ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.edit.now', $user->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.fs-1 {
    font-size: 3rem !important;
}
</style>
@endsection