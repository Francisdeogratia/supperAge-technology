
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

    <title>SupperAge marketplace</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

    <!-- Stylesheets -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <!-- Font Awesome 4.7 (matches your fa fa-user-circle class) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/post.css') }}">

    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
    

    <!-- Scripts -->
    <style>
#marketplaceNotifications .dropdown-menu {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

#marketplaceNotifications .dropdown-item:hover {
    background-color: #f8f9fa;
}

#marketplaceNotifCount {
    font-size: 0.65rem;
    padding: 0.25em 0.4em;
}





.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.transition {
    transition: all 0.3s ease;
}
</style>


</head>

<!-- Your notify  navbar content  -->
    @include('layouts.navbar')

@extends('layouts.app')

@section('seo_title', 'Marketplace - SupperAge | Buy & Sell')
@section('seo_description', 'Browse and shop the SupperAge marketplace. Discover products, create your own store, and sell to customers worldwide.')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">🛍️ Marketplace</h2>
            <p class="text-muted">Discover stores and products from our community</p>

            <a href="{{ route('marketplace.notifications') }}" class="btn btn-outline-primary position-relative" title="Notifications">
    <i class="fas fa-bell fa-lg"></i>
    @php
        $unreadCount = \App\Helpers\NotificationHelper::getMarketplaceNotificationCount($user->id);
    @endphp
    @if($unreadCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $unreadCount }}
        </span>
    @endif
</a>


        </div>
        <div class="col-md-4 text-end">
            @if($userStore)
                <a href="{{ route('marketplace.my-store') }}" class="btn btn-primary">
                    <i class="fas fa-store"></i> My Store
                    @if($storeNotifications > 0)
                        <span class="badge bg-danger">{{ $storeNotifications }}</span>
                    @endif
                </a>
            @else
                <a href="{{ route('marketplace.show-create-store') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Create Store
                </a>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stores Grid -->
    @if($stores->count() > 0)
        <div class="row g-4">
            @foreach($stores as $store)
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm hover-shadow transition">
                        <!-- Store Banner -->
                        <div class="position-relative" style="height: 150px; overflow: hidden;">
                            @if($store->banner)
                                <img src="{{ asset($store->banner) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $store->store_name }}">
                            @else
                                <div class="w-100 h-100 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                            @endif
                            
                            <!-- Store Logo -->
                            <div class="position-absolute" style="bottom: -30px; left: 20px;">
                                @if($store->logo)
                                    <img src="{{ asset($store->logo) }}" class="rounded-circle border border-white border-4" 
                                         style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $store->store_name }}">
                                @else
                                    <div class="rounded-circle bg-white border border-4 d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-store fa-2x text-primary"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card-body pt-5">
                            <h5 class="card-title fw-bold mb-2">{{ $store->store_name }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-user"></i> {{ $store->owner->name ?? 'Unknown Owner' }}
                            </p>
                            <p class="card-text text-secondary small mb-3" style="height: 60px; overflow: hidden;">
                                {{ Str::limit($store->description, 100) }}
                            </p>

                            <!-- Store Stats -->
                            <div class="d-flex justify-content-between text-muted small mb-3">
                                <span><i class="fas fa-box"></i> {{ $store->active_products_count }} Products</span>
                                <span><i class="fas fa-shopping-cart"></i> {{ $store->total_orders }} Orders</span>
                                <span><i class="fas fa-eye"></i> {{ $store->total_views }} Views</span>
                            </div>

                            <a href="{{ route('marketplace.view-store', $store->store_slug) }}" class="btn btn-outline-primary w-100">
                                Visit Store <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $stores->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-store fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No stores available yet</h4>
            <p class="text-secondary">Be the first to create a store!</p>
            <a href="{{ route('marketplace.show-create-store') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus"></i> Create Your Store
            </a>
        </div>
    @endif
</div>

<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


<script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<!-- Add this JavaScript at the bottom of your layout file -->
<script>
// Fetch marketplace notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    fetchMarketplaceNotifications();
    
    // Poll for new notifications every 30 seconds
    setInterval(fetchMarketplaceNotifications, 30000);
});

function fetchMarketplaceNotifications() {
    fetch('{{ route("marketplace.notification.latest") }}')
        .then(response => response.json())
        .then(data => {
            const count = data.count || 0;
            const notifications = data.notifications || [];
            
            // Update badge
            const badge = document.getElementById('marketplaceNotifCount');
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
            
            // Update dropdown list
            const notifList = document.getElementById('marketplaceNotifList');
            if (notifications.length === 0) {
                notifList.innerHTML = `
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-bell-slash fa-2x mb-2"></i>
                        <p class="mb-0 small">No new notifications</p>
                    </div>
                `;
            } else {
                notifList.innerHTML = notifications.map(notif => {
                    const icon = getNotificationIcon(notif.type);
                    return `
                        <a href="${notif.link}" class="dropdown-item py-2 border-bottom" 
                           onclick="markNotificationRead(${notif.id})">
                            <div class="d-flex align-items-start">
                                <div class="me-2">
                                    <i class="${icon} fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 small fw-bold">${notif.message}</p>
                                    <p class="mb-0 text-muted" style="font-size: 0.75rem;">
                                        <i class="fas fa-clock"></i> ${notif.created_at}
                                    </p>
                                </div>
                            </div>
                        </a>
                    `;
                }).join('');
            }
        })
        .catch(error => console.error('Error fetching notifications:', error));
}

function getNotificationIcon(type) {
    const icons = {
        'marketplace_order': 'fas fa-shopping-cart text-warning',
        'marketplace_order_placed': 'fas fa-check-circle text-success',
        'marketplace_order_update': 'fas fa-box text-info',
        'marketplace_new_product': 'fas fa-box-open text-primary',
        'marketplace_subscription_expired': 'fas fa-exclamation-triangle text-danger',
        'marketplace_store': 'fas fa-store text-success'
    };
    return icons[type] || 'fas fa-bell text-secondary';
}

function markNotificationRead(notificationId) {
    fetch(`/marketplace/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    });
}
</script>









@endsection


</html>