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

    <title>  order details - SupperAge</title>

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
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('marketplace.index') }}">Marketplace</a></li>
            <li class="breadcrumb-item"><a href="{{ route('marketplace.my-orders') }}">My Orders</a></li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <!-- Order Header -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-receipt"></i> Order Details</h5>
                        <span class="badge bg-{{ 
                            $order->status === 'pending' ? 'warning' : 
                            ($order->status === 'delivered' ? 'success' : 
                            ($order->status === 'cancelled' ? 'danger' : 'light')) 
                        }} px-3 py-2">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Order Number:</strong></p>
                            <p class="h5 text-primary">{{ $order->order_number }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>Order Date:</strong></p>
                            <p>{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <hr>
                    <div class="d-flex align-items-center mb-3">
                        @if($order->product && $order->product->images && count($order->product->images) > 0)
                            <img src="{{ asset($order->product->images[0]) }}" 
                                 class="rounded me-3" style="width: 100px; height: 100px; object-fit: cover;" 
                                 alt="{{ $order->product->name }}">
                        @else
                            <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-image fa-2x text-white"></i>
                            </div>
                        @endif
                        
                        <div>
                            <h5 class="mb-1">{{ $order->product->name ?? 'Product Unavailable' }}</h5>
                            <p class="text-muted mb-1">
                                <i class="fas fa-store"></i> 
                                <a href="{{ route('marketplace.view-store', $order->store->store_slug) }}" 
                                   class="text-decoration-none">
                                    {{ $order->store->store_name }}
                                </a>
                            </p>
                            <p class="mb-0">
                                <strong>Quantity:</strong> {{ $order->quantity }} x 
                                {{ $order->currency }} {{ number_format($order->unit_price, 2) }}
                            </p>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1">Subtotal:</p>
                            <p class="mb-1">Delivery:</p>
                            @if($order->notes)
                                <p class="mb-1 mt-3"><strong>Order Notes:</strong></p>
                                <p class="text-muted">{{ $order->notes }}</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="mb-1">{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</p>
                            <p class="mb-1 text-success">Free</p>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Total:</h5>
                        <h4 class="text-primary fw-bold mb-0">
                            {{ $order->currency }} {{ number_format($order->total_amount, 2) }}
                        </h4>
                    </div>

                    <!-- Status Update (Seller Only) -->
                    @if($order->store->owner_id === $user->id && !in_array($order->status, ['delivered', 'cancelled']))
                        <hr>
                        <div class="mt-3">
                            <label class="form-label fw-bold">Update Order Status:</label>
                            <select class="form-select" id="orderStatus">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button class="btn btn-primary mt-2" onclick="updateOrderStatus()">
                                <i class="fas fa-save"></i> Update Status
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Order Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <p class="mb-0"><strong>Order Placed</strong></p>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>

                        @if($order->confirmed_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <p class="mb-0"><strong>Order Confirmed</strong></p>
                                    <small class="text-muted">{{ $order->confirmed_at->format('M d, Y h:i A') }}</small>
                                </div>
                            </div>
                        @endif

                        @if($order->shipped_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <p class="mb-0"><strong>Order Shipped</strong></p>
                                    <small class="text-muted">{{ $order->shipped_at->format('M d, Y h:i A') }}</small>
                                </div>
                            </div>
                        @endif

                        @if($order->delivered_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <p class="mb-0"><strong>Order Delivered</strong></p>
                                    <small class="text-muted">{{ $order->delivered_at->format('M d, Y h:i A') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Delivery Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-truck"></i> Delivery Information</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $order->buyer_name }}</strong></p>
                    <p class="mb-1">{{ $order->buyer_phone }}</p>
                    <p class="mb-3 text-muted">{{ $order->buyer_email }}</p>
                    
                    <hr>
                    
                    <p class="mb-1"><strong>Delivery Address:</strong></p>
                    <p class="mb-1">{{ $order->buyer_address }}</p>
                    @if($order->buyer_city || $order->buyer_state)
                        <p class="mb-1">{{ $order->buyer_city }}{{ $order->buyer_city && $order->buyer_state ? ', ' : '' }}{{ $order->buyer_state }}</p>
                    @endif
                    <p class="mb-0">{{ $order->buyer_country }}</p>
                </div>
            </div>

            <!-- Store Information -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-store"></i> Store Information</h6>
                </div>
                <div class="card-body text-center">
                    @if($order->store->logo)
                        <img src="{{ asset($order->store->logo) }}" class="rounded-circle mb-2" 
                             style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $order->store->store_name }}">
                    @else
                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-2" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-store fa-2x text-white"></i>
                        </div>
                    @endif
                    
                    <h5 class="mb-1">{{ $order->store->store_name }}</h5>
                    <p class="text-muted small mb-3">{{ $order->store->phone }}</p>
                    
                    <a href="{{ route('marketplace.view-store', $order->store->store_slug) }}" 
                       class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-store"></i> Visit Store
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -19px;
    top: 20px;
    width: 2px;
    height: calc(100% - 10px);
    background: #dee2e6;
}

.timeline-marker {
    position: absolute;
    left: -24px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
}
</style>

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
function updateOrderStatus() {
    const status = document.getElementById('orderStatus').value;
    
    if(!confirm(`Are you sure you want to update the order status to "${status}"?`)) {
        return;
    }
    
    fetch('{{ route("marketplace.update-order-status", $order->order_number) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.error || 'Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
</script>
@endsection
    </body>
    </html>