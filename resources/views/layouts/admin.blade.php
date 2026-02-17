<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <title>Admin Panel - SupperAge</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            min-height: 100vh;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 10px;
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .badge {
            padding: 5px 10px;
        }
        
        .table {
            background-color: white;
        }
        
        .btn-group .btn {
            margin: 0 2px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar text-white" style="width: 260px;">
            <div class="p-4">
                <h4 class="mb-0">
                    <i class="fas fa-shield-alt"></i> Admin Panel
                </h4>
                <small class="text">SupperAge</small>
            </div>
            
            <nav class="nav flex-column px-2">
                <a class="nav-link {{ request()->routeIs('admin.dashboard.now') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard.now') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                   href="{{ route('admin.users.now') }}">
                    <i class="fas fa-users"></i> Manage Users
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.payment-applications.now') ? 'active' : '' }}" 
                   href="{{ route('admin.payment-applications.now') }}">
                    <i class="fas fa-file-invoice-dollar"></i> Payments
                    @php
                        $pendingCount = DB::table('payment_applications')
                            ->where('status', 'pending')
                            ->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-danger ms-2">{{ $pendingCount }}</span>
                    @endif
                </a>
                
                <hr class="border-secondary mx-3 my-3">
                
                <a class="nav-link" href="{{ route('update') }}">
                    <i class="fas fa-home"></i> Back to Site
                </a>
                
                <a class="nav-link" href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
            
            <div class="p-3 mt-auto">
                <div class="card bg-dark border-secondary">
                    <div class="card-body py-2">
                        <small class="text-muted d-block">Logged in as:</small>
                        <strong>{{ Session::get('username') }}</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Top Bar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
                <div class="container-fluid">
                    <span class="navbar-text">
                        <i class="fas fa-user-shield text-danger"></i>
                        <strong>Admin Mode</strong>
                    </span>
                    
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="fas fa-calendar"></i> 
                            {{ now()->format('l, F j, Y') }}
                        </span>
                        <span class="badge bg-success">Online</span>
                    </div>
                </div>
            </nav>
            
            <!-- Page Content -->
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show">
                        <i class="fas fa-info-circle"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (optional, for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @yield('scripts')
</body>
</html>