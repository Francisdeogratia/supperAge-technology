<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monetization Dashboard - Supperage</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('css/bar.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}"> -->

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        
        body { background: #f5f7fa; min-height: 100vh; }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white; padding: 40px 20px; border-radius: 20px; margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }
        
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px; margin-bottom: 30px;
        }
        
        .stat-card {
            background: white; padding: 25px; border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s;
        }
        
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        
        .stat-icon {
            width: 60px; height: 60px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 15px;
        }
        
        .stat-value { font-size: 32px; font-weight: bold; margin-bottom: 5px; }
        .stat-label { color: #666; font-size: 14px; }
        
        .users-table-container {
            background: white; border-radius: 20px; padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .user-row {
            padding: 20px; border-bottom: 1px solid #f0f0f0; transition: all 0.3s;
        }
        
        .user-row:hover { background: #f8f9fa; transform: translateX(5px); }
        
        .user-avatar {
            width: 60px; height: 60px; border-radius: 50%;
            object-fit: cover; border: 3px solid var(--primary);
        }
        
        .badge-monetized { background: linear-gradient(135deg, var(--success) 0%, #059669 100%); color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-pending { background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-not-monetized { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        
        .action-btn {
            padding: 8px 16px; border-radius: 10px; border: none;
            font-size: 14px; font-weight: 600; cursor: pointer;
            transition: all 0.3s; margin: 0 5px; color: white;
        }
        
        .btn-monetize { background: linear-gradient(135deg, var(--success) 0%, #059669 100%); }
        .btn-monetize:hover { transform: scale(1.05); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); }
        
        .btn-details { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .btn-message { background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); }
        .btn-suspend { background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%); }
        
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.7); display: none; align-items: center;
            justify-content: center; z-index: 10000; backdrop-filter: blur(5px);
        }
        
        .modal-overlay.show { display: flex; }
        
        .modal-content {
            background: white; border-radius: 20px; padding: 30px;
            max-width: 900px; width: 90%; max-height: 90vh; overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: modalSlideIn 0.3s ease;
        }
        
        @keyframes modalSlideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .performance-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px; border-radius: 15px; margin-bottom: 20px;
        }
        
        .engagement-bar {
            height: 30px; background: #e9ecef; border-radius: 15px;
            overflow: hidden; position: relative;
        }
        
        .engagement-fill {
            height: 100%; background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            transition: width 0.5s ease; display: flex; align-items: center;
            justify-content: flex-end; padding-right: 10px; color: white;
            font-weight: bold; font-size: 12px;
        }
        
        .viral-badge {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white; padding: 8px 16px; border-radius: 20px;
            font-weight: bold; display: inline-flex; align-items: center;
            gap: 8px; animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(255, 107, 107, 0); }
        }
        
        .search-bar {
            background: white; border-radius: 15px; padding: 15px 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 30px;
        }
        
        .search-input {
            border: none; outline: none; width: 100%; font-size: 16px;
        }
        
        .filter-chips {
            display: flex; gap: 10px; margin-top: 15px; flex-wrap: wrap;
        }
        
        .filter-chip {
            padding: 8px 16px; border-radius: 20px; border: 2px solid #e9ecef;
            background: white; cursor: pointer; transition: all 0.3s; font-size: 14px;
        }
        
        .filter-chip.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white; border-color: var(--primary);
        }
    </style>
</head>
<body>
{{-- --<!-- @include('layouts.navbar') -->---}}
@extends('layouts.app')

@section('content')


<div class="container" style="margin-top: 30px; margin-bottom: 100px;">
    <div class="dashboard-header">
        <h1 style="margin: 0; font-size: 36px;">
            <i class="fa fa-dollar-sign"></i> Monetization Dashboard
        </h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Manage user monetization and track performance</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fa fa-users" style="color: white;"></i>
            </div>
            <div class="stat-value" style="color: #667eea;">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fa fa-check-circle" style="color: white;"></i>
            </div>
            <div class="stat-value" style="color: #10b981;">{{ number_format($stats['monetized_users']) }}</div>
            <div class="stat-label">Monetized Users</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <i class="fa fa-clock" style="color: white;"></i>
            </div>
            <div class="stat-value" style="color: #f59e0b;">{{ number_format($stats['pending_requests']) }}</div>
            <div class="stat-label">Pending</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                <i class="fa fa-money-bill-wave" style="color: white;"></i>
            </div>
            <div class="stat-value" style="color: #3b82f6;">₦{{ number_format($stats['total_earnings'], 2) }}</div>
            <div class="stat-label">Total Earnings</div>
        </div>
    </div>
    
    <div class="search-bar">
        <div style="display: flex; align-items: center; gap: 15px;">
            <i class="fa fa-search" style="color: #999;"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Search users..." value="{{ request('search') }}">
        </div>
        
        <div class="filter-chips">
            <div class="filter-chip {{ !request('status') ? 'active' : '' }}" onclick="filterStatus(null)">All Users</div>
            <div class="filter-chip {{ request('status') === 'approved' ? 'active' : '' }}" onclick="filterStatus('approved')">Monetized</div>
            <div class="filter-chip {{ request('status') === 'pending' ? 'active' : '' }}" onclick="filterStatus('pending')">Pending</div>
            <div class="filter-chip {{ request('status') === 'not_monetized' ? 'active' : '' }}" onclick="filterStatus('not_monetized')">Not Monetized</div>
            <div class="filter-chip {{ request('status') === 'suspended' ? 'active' : '' }}" onclick="filterStatus('suspended')">Suspended</div>
        </div>
    </div>
    
    <div class="users-table-container">
        @if($users->count() > 0)
            @foreach($users as $listUser)
                <div class="user-row">
                    <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 250px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                @if($listUser->profileimg)
                                    <img src="{{ $listUser->profileimg }}" class="user-avatar" alt="{{ $listUser->name }}">
                                @else
                                    <div class="user-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px;">
                                        {{ strtoupper(substr($listUser->name, 0, 1)) }}
                                    </div>
                                @endif
                                
                                <div>
                                    <div style="font-weight: bold; font-size: 16px;">
                                        {{ $listUser->name }}
                                        @if($listUser->badge_status)
                                            <img src="{{ asset($listUser->badge_status) }}" style="width: 20px; height: 20px;">
                                        @endif
                                    </div>
                                    <div style="color: #666; font-size: 14px;">{{ '@' . $listUser->username }}</div>
                                    <div style="font-size: 12px; color: #999;">
                                        <i class="fa fa-image"></i> {{ $listUser->total_posts }} posts
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="min-width: 150px;">
                            @if($listUser->monetization && $listUser->monetization->status === 'approved')
                                <span class="badge-monetized"><i class="fa fa-check-circle"></i> Monetized</span>
                            @elseif($listUser->monetization && $listUser->monetization->status === 'pending')
                                <span class="badge-pending"><i class="fa fa-clock"></i> Pending</span>
                            @elseif($listUser->monetization && $listUser->monetization->status === 'suspended')
                                <span class="badge-not-monetized" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);"><i class="fa fa-ban"></i> Suspended</span>
                            @else
                                <span class="badge-not-monetized"><i class="fa fa-times-circle"></i> Not Monetized</span>
                            @endif
                        </div>
                        
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button class="action-btn btn-details" onclick="showUserDetails({{ $listUser->id }})">
                                <i class="fa fa-chart-line"></i> Details
                            </button>
                            
                            @if(!$listUser->monetization || $listUser->monetization->status !== 'approved')
                                <button class="action-btn btn-monetize" onclick="monetizeUser({{ $listUser->id }}, '{{ $listUser->name }}')">
                                    <i class="fa fa-dollar-sign"></i> Monetize
                                </button>
                            @else
                                <button class="action-btn btn-suspend" onclick="suspendUser({{ $listUser->id }}, '{{ $listUser->name }}')">
                                    <i class="fa fa-ban"></i> Suspend
                                </button>
                            @endif
                            
                            <button class="action-btn btn-message" onclick="showMessageModal({{ $listUser->id }}, '{{ $listUser->name }}')">
                                <i class="fa fa-envelope"></i> Message
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div style="margin-top: 30px;">{{ $users->links() }}</div>
        @else
            <div style="text-align: center; padding: 60px;">
                <i class="fa fa-users" style="font-size: 80px; color: #e9ecef;"></i>
                <h3 style="color: #999; margin-top: 20px;">No users found</h3>
            </div>
        @endif
    </div>
</div>

<!-- Modals -->
<div class="modal-overlay" id="detailsModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; margin-bottom: 25px;">
            <h3><i class="fa fa-chart-line"></i> Post Performance</h3>
            <button onclick="closeDetailsModal()" style="background: none; border: none; font-size: 24px; cursor: pointer;">×</button>
        </div>
        <div id="detailsContent"></div>
    </div>
</div>

<div class="modal-overlay" id="messageModal">
    <div class="modal-content" style="max-width: 600px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 25px;">
            <h3><i class="fa fa-envelope"></i> Send Message</h3>
            <button onclick="closeMessageModal()" style="background: none; border: none; font-size: 24px; cursor: pointer;">×</button>
        </div>
        
        <form id="messageForm" onsubmit="sendMessage(event)">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold;">To:</label>
                <input type="text" id="messageUserName" readonly style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 10px; background: #f8f9fa;">
                <input type="hidden" id="messageUserId">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold;">Message:</label>
                <textarea id="messageText" rows="6" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 10px;" required></textarea>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeMessageModal()" style="padding: 12px 24px; border-radius: 10px; border: 2px solid #e9ecef; background: white; cursor: pointer;">Cancel</button>
                <button type="submit" class="action-btn btn-message" style="padding: 12px 24px;"><i class="fa fa-paper-plane"></i> Send</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="{{ asset('myjs/bar.js') }}"></script> -->
<!-- <script src="{{ asset('myjs/tales.js') }}"></script> -->
<!-- <script src="{{ asset('myjs/mobilenavbar.js') }}"></script> -->
<!-- <script src="{{ asset('myjs/searchuser.js') }}"></script> -->

<script>
const csrfToken = '{{ csrf_token() }}';

let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const url = new URL(window.location.href);
        if (this.value) url.searchParams.set('search', this.value);
        else url.searchParams.delete('search');
        window.location.href = url.toString();
    }, 500);
});

function filterStatus(status) {
    const url = new URL(window.location.href);
    if (status) url.searchParams.set('status', status);
    else url.searchParams.delete('status');
    window.location.href = url.toString();
}

async function showUserDetails(userId) {
    console.log('Fetching details for user ID:', userId); // Debug
    document.getElementById('detailsModal').classList.add('show');
    document.getElementById('detailsContent').innerHTML = '<div style="text-align:center;padding:40px;"><i class="fa fa-spinner fa-spin" style="font-size:48px;color:#667eea;"></i></div>';
    
    try {
        const response = await fetch(`/admin/monetization/user/${userId}/details`, {
            method: 'GET',
            headers: { 
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        console.log('Response status:', response.status); // Debug
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Error response:', errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Received data:', data); // Debug
        
        displayUserDetails(data);
    } catch (error) {
        console.error('Detailed error:', error);
        document.getElementById('detailsContent').innerHTML = `
            <div style="text-align:center;padding:40px;">
                <i class="fa fa-exclamation-triangle" style="font-size:48px;color:#ef4444;"></i>
                <p style="color:#666;margin-top:15px;">Failed to load user details</p>
                <p style="color:#999;font-size:14px;">${error.message}</p>
            </div>
        `;
    }
}

function displayUserDetails(data) {
    const { user, stats, top_post, viral_posts } = data;
    
    let html = `
        <div style="display:flex;align-items:center;gap:20px;margin-bottom:30px;padding:20px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:15px;color:white;">
            ${user.profileimg ? `<img src="${user.profileimg}" style="width:80px;height:80px;border-radius:50%;border:4px solid white;">` : `<div style="width:80px;height:80px;border-radius:50%;background:white;color:#667eea;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:bold;">${user.name.charAt(0)}</div>`}
            <div>
                <h4 style="margin:0;font-size:24px;">${user.name}</h4>
                <p style="margin:5px 0 0 0;opacity:0.9;">@${user.username}</p>
            </div>
        </div>
        
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:15px;margin-bottom:30px;">
            <div class="performance-card"><div style="font-size:24px;font-weight:bold;color:#667eea;">${stats.total_posts}</div><div style="font-size:12px;color:#666;">Total Posts</div></div>
            <div class="performance-card"><div style="font-size:24px;font-weight:bold;color:#10b981;">${stats.today_posts}</div><div style="font-size:12px;color:#666;">Today</div></div>
            <div class="performance-card"><div style="font-size:24px;font-weight:bold;color:#ef4444;">${stats.viral_posts}</div><div style="font-size:12px;color:#666;">Viral</div></div>
        </div>
        
        <div class="performance-card" style="margin-bottom:30px;">
            <h5 style="margin:0 0 15px 0;">Total Engagement</h5>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(100px,1fr));gap:15px;">
                <div><div style="font-size:20px;font-weight:bold;color:#e91e63;">${stats.total_likes.toLocaleString()}</div><div style="font-size:12px;color:#666;"><i class="fa fa-heart"></i> Likes</div></div>
                <div><div style="font-size:20px;font-weight:bold;color:#3b82f6;">${stats.total_comments.toLocaleString()}</div><div style="font-size:12px;color:#666;"><i class="fa fa-comment"></i> Comments</div></div>
                <div><div style="font-size:20px;font-weight:bold;color:#10b981;">${stats.total_reposts.toLocaleString()}</div><div style="font-size:12px;color:#666;"><i class="fa fa-retweet"></i> Reposts</div></div>
                <div><div style="font-size:20px;font-weight:bold;color:#f59e0b;">${stats.total_shares.toLocaleString()}</div><div style="font-size:12px;color:#666;"><i class="fa fa-share"></i> Shares</div></div>
                <div><div style="font-size:20px;font-weight:bold;color:#8b5cf6;">${stats.total_views.toLocaleString()}</div><div style="font-size:12px;color:#666;"><i class="fa fa-eye"></i> Views</div></div>
            </div>
        </div>
    `;
    
    if (top_post) {
        const percent = Math.min((top_post.engagement_score / 100000) * 100, 100);
        html += `
            <div class="performance-card">
                <h5 style="margin:0 0 15px 0;"><i class="fa fa-trophy" style="color:#f59e0b;"></i> Top Post</h5>
                <p style="margin-bottom:15px;color:#666;">${top_post.content}</p>
                <div class="engagement-bar"><div class="engagement-fill" style="width:${percent}%;">${percent.toFixed(1)}%</div></div>
            </div>
        `;
    }
    
    if (viral_posts && viral_posts.length > 0) {
        html += `<h5 style="margin:30px 0 15px 0;"><span class="viral-badge"><i class="fa fa-fire"></i> ${viral_posts.length} Viral Posts</span></h5>`;
        viral_posts.forEach(post => {
            html += `<div class="performance-card"><p style="font-size:14px;color:#666;">${post.content}</p></div>`;
        });
    }
    
    document.getElementById('detailsContent').innerHTML = html;
}

function closeDetailsModal() {
    document.getElementById('detailsModal').classList.remove('show');
}

async function monetizeUser(userId, userName) {
    if (!confirm(`Approve ${userName} for monetization?`)) return;
    
    try {
        const response = await fetch(`/admin/monetization/user/${userId}/approve`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }
        });
        const data = await response.json();
        alert(data.message);
        location.reload();
    } catch (error) {
        alert('Failed to monetize user');
    }
}

async function suspendUser(userId, userName) {
    if (!confirm(`Suspend ${userName}'s monetization?`)) return;
    
    try {
        const response = await fetch(`/admin/monetization/user/${userId}/suspend`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        const data = await response.json();
        alert(data.message);
        location.reload();
    } catch (error) {
        alert('Failed to suspend user');
    }
}

function showMessageModal(userId, userName) {
    document.getElementById('messageUserId').value = userId;
    document.getElementById('messageUserName').value = userName;
    document.getElementById('messageText').value = '';
    document.getElementById('messageModal').classList.add('show');
}

function closeMessageModal() {
    document.getElementById('messageModal').classList.remove('show');
}

async function sendMessage(e) {
    e.preventDefault();
    const userId = document.getElementById('messageUserId').value;
    const message = document.getElementById('messageText').value;
    
    try {
        const response = await fetch(`/admin/monetization/user/${userId}/message`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' },
            body: JSON.stringify({ message })
        });
        const data = await response.json();
        alert(data.message);
        closeMessageModal();
    } catch (error) {
        alert('Failed to send message');
    }
}
</script>
@endsection
</body>
</html>