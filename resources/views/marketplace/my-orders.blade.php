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

    <title>product order page </title>

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
    </head>
<body>
    @include('layouts.navbar')
@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0"><i class="fas fa-shopping-bag"></i> My Orders</h2>
            <p class="text-muted">Track your marketplace orders</p>
        </div>
        <a href="{{ route('marketplace.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-store"></i> Browse Marketplace
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="row g-3">
            @foreach($orders as $order)
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Order Image -->
                                <div class="col-md-2">
                                    @if($order->product && $order->product->images && count($order->product->images) > 0)
                                        <img src="{{ asset($order->product->images[0]) }}" 
                                             class="img-fluid rounded" style="max-height: 100px; object-fit: cover;" 
                                             alt="{{ $order->product->name }}">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                             style="height: 100px;">
                                            <i class="fas fa-image fa-2x text-white"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Order Details -->
                                <div class="col-md-5">
                                    <h5 class="mb-2">
                                        <a href="{{ route('marketplace.order-details', $order->order_number) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ $order->product->name ?? 'Product Unavailable' }}
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-1 small">
                                        <i class="fas fa-store"></i> {{ $order->store->store_name }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Order #:</strong> {{ $order->order_number }}
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-calendar"></i> {{ $order->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>

                                <!-- Order Info -->
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <strong>Quantity:</strong> {{ $order->quantity }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Total:</strong> <span class="text-primary fw-bold">
                                            {{ $order->currency }} {{ number_format($order->total_amount, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="badge bg-{{ 
                                            $order->status === 'pending' ? 'warning' : 
                                            ($order->status === 'delivered' ? 'success' : 
                                            ($order->status === 'cancelled' ? 'danger' : 'info')) 
                                        }} px-3 py-2">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="col-md-2 text-end">
                                    <a href="{{ route('marketplace.order-details', $order->order_number) }}" 
                                       class="btn btn-primary btn-sm w-100 mb-2">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <a href="{{ route('marketplace.view-store', $order->store->store_slug) }}" 
                                       class="btn btn-outline-secondary btn-sm w-100">
                                        <i class="fas fa-store"></i> Visit Store
                                    </a>
                                </div>
                            </div>

                            <!-- Delivery Status Timeline -->
                            @if(in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']))
                                <hr class="my-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between position-relative" style="padding: 0 10px;">
                                            <div class="progress" style="height: 4px; position: absolute; top: 12px; left: 0; right: 0; z-index: 0;">
                                                <div class="progress-bar bg-success" style="width: {{ 
                                                    $order->status === 'confirmed' ? '25%' : 
                                                    ($order->status === 'processing' ? '50%' : 
                                                    ($order->status === 'shipped' ? '75%' : '100%')) 
                                                }}%"></div>
                                            </div>
                                            
                                            <div class="text-center position-relative" style="z-index: 1;">
                                                <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center" 
                                                     style="width: 30px; height: 30px;">
                                                    <i class="fas fa-check text-white small"></i>
                                                </div>
                                                <div class="small mt-1">Confirmed</div>
                                            </div>
                                            
                                            <div class="text-center position-relative" style="z-index: 1;">
                                                <div class="rounded-circle bg-{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'success' : 'secondary' }} d-inline-flex align-items-center justify-content-center" 
                                                     style="width: 30px; height: 30px;">
                                                    <i class="fas fa-{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'check' : 'circle' }} text-white small"></i>
                                                </div>
                                                <div class="small mt-1">Processing</div>
                                            </div>
                                            
                                            <div class="text-center position-relative" style="z-index: 1;">
                                                <div class="rounded-circle bg-{{ in_array($order->status, ['shipped', 'delivered']) ? 'success' : 'secondary' }} d-inline-flex align-items-center justify-content-center" 
                                                     style="width: 30px; height: 30px;">
                                                    <i class="fas fa-{{ in_array($order->status, ['shipped', 'delivered']) ? 'check' : 'circle' }} text-white small"></i>
                                                </div>
                                                <div class="small mt-1">Shipped</div>
                                            </div>
                                            
                                            <div class="text-center position-relative" style="z-index: 1;">
                                                <div class="rounded-circle bg-{{ $order->status === 'delivered' ? 'success' : 'secondary' }} d-inline-flex align-items-center justify-content-center" 
                                                     style="width: 30px; height: 30px;">
                                                    <i class="fas fa-{{ $order->status === 'delivered' ? 'check' : 'circle' }} text-white small"></i>
                                                </div>
                                                <div class="small mt-1">Delivered</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No orders yet</h4>
            <p class="text-secondary">Start shopping on our marketplace!</p>
            <a href="{{ route('marketplace.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-store"></i> Browse Products
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

@endsection
</body>
</html>