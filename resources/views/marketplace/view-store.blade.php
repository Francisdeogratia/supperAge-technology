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

    <title>  view store SupperAge</title>

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
<div class="container-fluid py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('marketplace.index') }}">Marketplace</a></li>
            <li class="breadcrumb-item active">{{ $store->store_name }}</li>
        </ol>
    </nav>

    <!-- Store Header -->
    <div class="card mb-4 shadow">
        <!-- Banner -->
        <div class="position-relative" style="height: 250px; overflow: hidden;">
            @if($store->banner)
                <img src="{{ asset($store->banner) }}" class="w-100 h-100" style="object-fit: cover;" alt="Store Banner">
            @else
                <div class="w-100 h-100 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            @endif
            
            <!-- Logo Overlay -->
            <div class="position-absolute" style="bottom: -60px; left: 40px;">
                @if($store->logo)
                    <img src="{{ asset($store->logo) }}" class="rounded-circle border border-white border-5 shadow" 
                         style="width: 150px; height: 150px; object-fit: cover;" alt="{{ $store->store_name }}">
                @else
                    <div class="rounded-circle bg-white border border-5 shadow d-flex align-items-center justify-content-center" 
                         style="width: 150px; height: 150px;">
                        <i class="fas fa-store fa-4x text-primary"></i>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body pt-5 pb-3">
            <div class="row align-items-start">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2 mt-3">{{ $store->store_name }}</h2>
                    <p class="text-muted mb-3">
                        <i class="fas fa-user"></i> Owned by {{ $store->owner->name }}
                    </p>
                    @if($store->description)
                        <p class="mb-3">{{ $store->description }}</p>
                    @endif
                    
                    <!-- Store Stats -->
                    <div class="d-flex gap-4 mb-3">
                        <div>
                            <i class="fas fa-box text-primary"></i>
                            <strong>{{ $store->total_products }}</strong> Products
                        </div>
                        <div>
                            <i class="fas fa-shopping-cart text-success"></i>
                            <strong>{{ $store->total_orders }}</strong> Orders
                        </div>
                        <div>
                            <i class="fas fa-eye text-info"></i>
                            <strong>{{ $store->total_views }}</strong> Views
                        </div>
                    </div>
                </div>

                <div class="col-md-4 text-md-end mt-3">
                    <!-- Contact Information -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2"><i class="fas fa-info-circle"></i> Contact Information</h6>
                            <p class="mb-1 small">
                                <i class="fas fa-envelope"></i> {{ $store->email }}
                            </p>
                            <p class="mb-1 small">
                                <i class="fas fa-phone"></i> {{ $store->phone }}
                            </p>
                            @if($store->address)
                                <p class="mb-0 small">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    {{ $store->city }}, {{ $store->state }}, {{ $store->country }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="fas fa-box"></i> Products 
            <span class="badge bg-primary">{{ $products->total() }}</span>
        </h4>
        
        <!-- Filter/Sort Options -->
        <div class="">
            <select class="form-select form-select-sm" id="categoryFilter" style="width: auto;">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
            <select class="form-select form-select-sm" id="sortBy" style="width: auto;">
                <option value="newest">Newest First</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
                <option value="popular">Most Popular</option>
            </select>
        </div>
    </div>

    @if($products->count() > 0)
        <div class="row g-4" id="productsGrid">
            @foreach($products as $product)
                <div class="col-md-3 col-sm-6 product-item" 
                     data-category="{{ $product->category }}" 
                     data-price="{{ $product->price }}" 
                     data-views="{{ $product->views }}"
                     data-created="{{ $product->created_at->timestamp }}">
                    <div class="card h-100 shadow-sm hover-card">
                        <!-- Product Image -->
                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset($product->images[0]) }}" class="card-img-top h-100" 
                                     style="object-fit: cover;" alt="{{ $product->name }}">
                            @else
                                <div class="h-100 bg-secondary d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-white"></i>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            @if($product->status === 'out_of_stock')
                                <span class="position-absolute top-0 end-0 badge bg-danger m-2">Out of Stock</span>
                            @elseif($product->status === 'active' && $product->created_at->diffInDays() < 7)
                                <span class="position-absolute top-0 end-0 badge bg-success m-2">New</span>
                            @endif
                        </div>

                        <div class="card-body">
                            <h6 class="card-title fw-bold mb-2">
                                <a href="{{ route('marketplace.view-product', $product->slug) }}" 
                                   class="text-decoration-none text-dark stretched-link">
                                    {{ Str::limit($product->name, 40) }}
                                </a>
                            </h6>
                            
                            @if($product->category)
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-tag"></i> {{ $product->category }}
                                </p>
                            @endif
                            
                            <p class="text-primary fw-bold h5 mb-2">
                                {{ $product->currency }} {{ number_format($product->price, 2) }}
                            </p>
                            
                            <div class="d-flex justify-content-between small text-muted">
                                <span><i class="fas fa-eye"></i> {{ $product->views }}</span>
                                <span><i class="fas fa-shopping-cart"></i> {{ $product->orders }} sold</span>
                                @if($product->type === 'product')
                                    <span><i class="fas fa-box"></i> {{ $product->stock }} left</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No products available</h4>
            <p class="text-secondary">This store hasn't listed any products yet</p>
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
// Category Filter
document.getElementById('categoryFilter').addEventListener('change', function() {
    filterAndSort();
});

// Sort
document.getElementById('sortBy').addEventListener('change', function() {
    filterAndSort();
});

function filterAndSort() {
    const category = document.getElementById('categoryFilter').value;
    const sortBy = document.getElementById('sortBy').value;
    const products = Array.from(document.querySelectorAll('.product-item'));
    
    // Filter
    products.forEach(product => {
        const productCategory = product.getAttribute('data-category');
        if (category === '' || productCategory === category) {
            product.style.display = '';
        } else {
            product.style.display = 'none';
        }
    });
    
    // Sort visible products
    const visibleProducts = products.filter(p => p.style.display !== 'none');
    const grid = document.getElementById('productsGrid');
    
    visibleProducts.sort((a, b) => {
        switch(sortBy) {
            case 'price_low':
                return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
            case 'price_high':
                return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
            case 'popular':
                return parseInt(b.getAttribute('data-views')) - parseInt(a.getAttribute('data-views'));
            case 'newest':
            default:
                return parseInt(b.getAttribute('data-created')) - parseInt(a.getAttribute('data-created'));
        }
    });
    
    // Reorder
    visibleProducts.forEach(product => {
        grid.appendChild(product);
    });
}
</script>

<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}
</style>
@endsection