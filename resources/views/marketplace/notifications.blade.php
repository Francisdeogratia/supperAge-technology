<!DOCTYPE html>
<html lang="en">
<head>
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

    <title>  Store notification - SupperAge</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
    </head>
    <body>
    @include('layouts.navbar')
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0"><i class="fas fa-bell"></i> Marketplace Notifications</h2>
            <p class="text-muted mb-0">Stay updated with your store and orders</p>
        </div>
        <div>
            @if($notifications->where('read_notification', 'no')->count() > 0)
                <a href="{{ route('marketplace.mark-all-read') }}" class="btn btn-outline-primary">
                    <i class="fas fa-check-double"></i> Mark All as Read
                </a>
            @endif
            <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-store"></i> Marketplace
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4" id="notificationTabs">
        <li class="nav-item">
            <a class="nav-link active" data-filter="all" href="#all">
                All <span class="badge bg-secondary ms-1">{{ $notifications->total() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-filter="marketplace_order" href="#orders">
                Orders <span class="badge bg-warning ms-1">{{ $notifications->where('type', 'marketplace_order')->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-filter="marketplace_new_product" href="#products">
                New Products <span class="badge bg-info ms-1">{{ $notifications->where('type', 'marketplace_new_product')->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-filter="subscription" href="#subscription">
                Subscription <span class="badge bg-danger ms-1">{{ $notifications->whereIn('type', ['marketplace_subscription_expired', 'marketplace_subscription_reminder_7', 'marketplace_subscription_reminder_3', 'marketplace_subscription_reminder_1'])->count() }}</span>
            </a>
        </li>
    </ul>

    @if($notifications->count() > 0)
        <div class="list-group">
            @foreach($notifications as $notification)
                <div class="list-group-item notification-item {{ $notification->read_notification === 'no' ? 'list-group-item-light border-start border-primary border-4' : '' }}" 
                     data-type="{{ $notification->type }}">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <!-- Icon based on type -->
                            @php
                                $icon = match($notification->type) {
                                    'marketplace_order' => 'fas fa-shopping-cart text-warning',
                                    'marketplace_order_placed' => 'fas fa-check-circle text-success',
                                    'marketplace_order_update' => 'fas fa-box text-info',
                                    'marketplace_new_product' => 'fas fa-box-open text-primary',
                                    'marketplace_subscription_expired' => 'fas fa-exclamation-triangle text-danger',
                                    'marketplace_subscription_reminder_7', 'marketplace_subscription_reminder_3', 'marketplace_subscription_reminder_1' => 'fas fa-clock text-warning',
                                    'marketplace_store' => 'fas fa-store text-success',
                                    default => 'fas fa-bell text-secondary'
                                };
                            @endphp
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="{{ $icon }} fa-lg"></i>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 {{ $notification->read_notification === 'no' ? 'fw-bold' : '' }}">
                                    {{ $notification->message }}
                                </h6>
                                @if($notification->read_notification === 'no')
                                    <span class="badge bg-primary">New</span>
                                @endif
                            </div>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                <span class="mx-2">•</span>
                                <span class="badge bg-secondary">{{ ucfirst(str_replace(['marketplace_', '_'], ['', ' '], $notification->type)) }}</span>
                            </p>
                        </div>
                        
                        <div class="col-auto">
                            <div class="btn-group">
                                @if($notification->link)
                                    <a href="{{ $notification->link }}" class="btn btn-sm btn-outline-primary" 
                                       onclick="markAsRead({{ $notification->id }})">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @endif
                                @if($notification->read_notification === 'no')
                                    <button class="btn btn-sm btn-outline-success" onclick="markAsRead({{ $notification->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification({{ $notification->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Data (if available) -->
                    @if($notification->data)
                        @php
                            $data = json_decode($notification->data, true);
                        @endphp
                        @if($data && isset($data['order_number']))
                            <div class="mt-2 p-2 bg-light rounded small">
                                <strong>Order #:</strong> {{ $data['order_number'] ?? 'N/A' }}
                                @if(isset($data['total_amount']))
                                    <span class="mx-2">•</span>
                                    <strong>Amount:</strong> {{ $data['currency'] ?? 'NGN' }} {{ number_format($data['total_amount'] ?? 0, 2) }}
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No notifications yet</h4>
            <p class="text-secondary">You'll receive notifications for orders, new products, and subscription updates</p>
            <a href="{{ route('marketplace.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-store"></i> Browse Marketplace
            </a>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

    <script src="{{ asset('myjs/bar.js') }}"></script>
    <script src="{{ asset('myjs/more_lesstext.js') }}"></script>
    <script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
    <script src="{{ asset('myjs/allpost.js') }}"></script>
    <script src="{{ asset('myjs/tales.js') }}"></script>
    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
// Filter notifications
document.querySelectorAll('#notificationTabs .nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Update active tab
        document.querySelectorAll('#notificationTabs .nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.getAttribute('data-filter');
        const items = document.querySelectorAll('.notification-item');
        
        items.forEach(item => {
            if (filter === 'all') {
                item.style.display = '';
            } else if (filter === 'subscription') {
                const type = item.getAttribute('data-type');
                if (type.includes('subscription')) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            } else {
                if (item.getAttribute('data-type') === filter) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            }
        });
    });
});

// Mark as read
function markAsRead(notificationId) {
    fetch(`/marketplace/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (item) {
                item.classList.remove('list-group-item-light', 'border-start', 'border-primary', 'border-4');
                const badge = item.querySelector('.badge.bg-primary');
                if (badge) badge.remove();
            }
        }
    });
}

// Delete notification
function deleteNotification(notificationId) {
    if (!confirm('Are you sure you want to delete this notification?')) return;
    
    fetch(`/marketplace/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to delete notification');
        }
    });
}

// Add data attribute to items for filtering
document.querySelectorAll('.notification-item').forEach((item, index) => {
    item.setAttribute('data-notification-id', item.querySelector('button[onclick*="markAsRead"]')?.onclick.toString().match(/\d+/)?.[0] || index);
});
</script>

<style>
.notification-item {
    transition: all 0.3s ease;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

#notificationTabs .nav-link {
    cursor: pointer;
    color: #6c757d;
}

#notificationTabs .nav-link.active {
    background-color: #0d6efd;
    color: white;
}

#notificationTabs .nav-link:hover:not(.active) {
    background-color: #e9ecef;
}
</style>
@endsection
</body>
</html>