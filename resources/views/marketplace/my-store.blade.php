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

    <title> Your Store - SupperAge</title>

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
<style>
/* Edit Store Button Positioning */
.store-header-wrapper {
    position: relative;
}

.edit-store-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    background: white;
    color: #764ba2;
}

.edit-store-btn:hover {
    background: #0d6efd;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    transition: all 0.3s ease;
}

/* Mobile Responsive */


/* Tablet */
@media (min-width: 769px) and (max-width: 1024px) {
    .edit-store-btn {
        top: 10px;
        right: 10px;
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
        color: gray;
    }
}
</style>
    @include('layouts.navbar')
@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <!-- Subscription Alert -->
    @if($store->isSubscriptionExpired())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Subscription Expired!</h5>
            <p>Your store subscription has expired. Your store is currently suspended and you cannot add or manage products.</p>
            <hr>
            <a href="{{ route('marketplace.renew-subscription') }}" class="btn btn-danger">
                <i class="fas fa-sync"></i> Renew Subscription Now
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @elseif($store->daysUntilExpiry() !== null && $store->daysUntilExpiry() <= 7 && $store->daysUntilExpiry() >= 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-clock"></i> Subscription Expiring Soon!</h5>
            <p>Your store subscription expires in <strong>{{ $store->daysUntilExpiry() }} day(s)</strong> on {{ $store->subscription_expires_at->format('M d, Y') }}.</p>
            <a href="{{ route('marketplace.renew-subscription') }}" class="btn btn-warning">
                <i class="fas fa-sync"></i> Renew Now
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Store Header -->
    
<div class="card mb-4 store-header-wrapper">
    <div class="position-relative" style="height: 200px; overflow: hidden;">
        @if($store->banner)
            <img src="{{ asset($store->banner) }}" class="w-100 h-100" style="object-fit: cover;" alt="Store Banner">
        @else
            <div class="w-100 h-100 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
        @endif
        
        <!-- Edit Button - Desktop Position (Floating over banner) -->
        <button class="btn btn-sm btn-primary edit-store-btn d-none d-md-block" 
                data-bs-toggle="modal" 
                data-bs-target="#editStoreModal">
            <i class="fas fa-edit"></i> Edit Store
        </button>
    </div>
    
    <div class="card-body">
        <!-- Edit Button - Mobile Position (Below banner, full width) -->
        <button class="btn btn-sm btn-primary edit-store-btn d-md-none mb-3" 
                data-bs-toggle="modal" 
                data-bs-target="#editStoreModal">
            <i class="fas fa-edit"></i> Edit Store
        </button>
        
        <div class="row align-items-center store-info-wrapper">
            <div class="col-md-2 text-center">
                @if($store->logo)
                    <img src="{{ asset($store->logo) }}" class="rounded-circle border border-4" 
                         style="width: 120px; height: 120px; object-fit: cover; margin-top: -60px;" alt="Store Logo">
                @else
                    <div class="rounded-circle bg-white border border-4 d-inline-flex align-items-center justify-content-center" 
                         style="width: 120px; height: 120px; margin-top: -60px;">
                        <i class="fas fa-store fa-3x text-primary"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-7">
                <h3 class="fw-bold mb-1">{{ $store->store_name }}</h3>
                <p class="text-muted mb-2">{{ $store->email }} | {{ $store->phone }}</p>
                <p class="mb-2">{{ $store->description }}</p>
                <p class="text-muted small mb-0">
                    <i class="fas fa-map-marker-alt"></i> {{ $store->address }}, {{ $store->city }}, {{ $store->state }}, {{ $store->country }}
                </p>
            </div>
            <div class="col-md-3 text-end">
                <span class="badge bg-{{ $store->subscription_status === 'active' ? 'success' : 'danger' }} mb-2">
                    {{ ucfirst($store->subscription_status) }}
                </span>
                @if($store->subscription_expires_at)
                    <p class="small text-muted mb-0">Expires: {{ $store->subscription_expires_at->format('M d, Y') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center h-100 border-primary">
                <div class="card-body">
                    <i class="fas fa-box fa-2x text-primary mb-2"></i>
                    <h3 class="fw-bold mb-0">{{ $totalProducts }}</h3>
                    <p class="text-muted mb-0">Total Products</p>
                    <small class="text-success">{{ $activeProducts }} Active</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100 border-warning">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-2x text-warning mb-2"></i>
                    <h3 class="fw-bold mb-0">{{ $totalOrders }}</h3>
                    <p class="text-muted mb-0">Total Orders</p>
                    <small class="text-warning">{{ $pendingOrders }} Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100 border-success">
                <div class="card-body">
                    <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                    <h3 class="fw-bold mb-0">{{ $store->currency ?? 'NGN' }} {{ number_format($totalRevenue, 2) }}</h3>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100 border-info">
                <div class="card-body">
                    <i class="fas fa-eye fa-2x text-info mb-2"></i>
                    <h3 class="fw-bold mb-0">{{ $totalViews }}</h3>
                    <p class="text-muted mb-0">Total Views</p>
                    <a href="{{ route('marketplace.analytics') }}" class="btn btn-sm btn-outline-info mt-2">View Analytics</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#products">
                <i class="fas fa-box"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#orders">
                <i class="fas fa-shopping-cart"></i> Orders
                @if($unreadOrders > 0)
                    <span class="badge bg-danger">{{ $unreadOrders }}</span>
                @endif
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Products Tab -->
        <div id="products" class="tab-pane fade show active">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">My Products</h5>
                @if(!$store->isSubscriptionExpired())
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                @endif
            </div>

            @if($products->count() > 0)
                <div class="row g-3">
                    @foreach($products as $product)
                        <div class="col-md-4 col-sm-6">
                            <div class="card h-100">
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ asset($product->images[0]) }}" class="card-img-top" 
                                         style="height: 200px; object-fit: cover;" alt="{{ $product->name }}">
                                @else
                                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-white"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body">
                                    <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                                    <p class="text-primary fw-bold mb-2">{{ $product->currency }} {{ number_format($product->price, 2) }}</p>
                                    <p class="small text-muted mb-2">{{ Str::limit($product->description, 80) }}</p>
                                    
                                    <div class="d-flex justify-content-between small text-muted mb-3">
                                        <span><i class="fas fa-eye"></i> {{ $product->views }}</span>
                                        <span><i class="fas fa-shopping-cart"></i> {{ $product->orders }}</span>
                                        <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="btn-group w-100">
                                        <a href="{{ route('marketplace.view-product', $product->slug) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button class="btn btn-sm btn-outline-warning" onclick="editProduct({{ $product->id }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct({{ $product->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No products yet</h5>
                    @if(!$store->isSubscriptionExpired())
                        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus"></i> Add Your First Product
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Orders Tab -->
        <div id="orders" class="tab-pane fade">
            <h5 class="mb-3">Recent Orders</h5>
            
            @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->product->name ?? 'N/A' }}</td>
                                    <td>{{ $order->buyer_name }}</td>
                                    <td>{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $order->status === 'pending' ? 'warning' : 
                                            ($order->status === 'delivered' ? 'success' : 'info') 
                                        }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('marketplace.order-details', $order->order_number) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No orders yet</h5>
                    <p class="text-secondary">Orders will appear here when customers make purchases</p>
                </div>
            @endif
        </div>
    </div>
</div>


<!-- Add Modal at bottom of file -->
<!-- Edit Store Modal - FULL STORE EDITING -->
<div class="modal fade" id="editStoreModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Store Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateStoreForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Store Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Store Name *</label>
                            <input type="text" name="store_name" class="form-control" 
                                   value="{{ $store->store_name }}" required>
                        </div>
                        
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ $store->email }}" required>
                        </div>
                        
                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone *</label>
                            <input type="text" name="phone" class="form-control" 
                                   value="{{ $store->phone }}" required>
                        </div>
                        
                        <!-- Currency -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Default Currency</label>
                            <select name="currency" class="form-select">
                                <option value="NGN" {{ $store->currency === 'NGN' ? 'selected' : '' }}>NGN - Nigerian Naira</option>
                                <option value="USD" {{ $store->currency === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="GBP" {{ $store->currency === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="EUR" {{ $store->currency === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            </select>
                        </div>
                        
                        <!-- Description -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $store->description }}</textarea>
                        </div>
                        
                        <!-- Address -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Address *</label>
                            <input type="text" name="address" class="form-control" 
                                   value="{{ $store->address }}" required>
                        </div>
                        
                        <!-- City -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" 
                                   value="{{ $store->city }}">
                        </div>
                        
                        <!-- State -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control" 
                                   value="{{ $store->state }}">
                        </div>
                        
                        <!-- Country -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Country *</label>
                            <input type="text" name="country" class="form-control" 
                                   value="{{ $store->country }}" required>
                        </div>
                        
                        <!-- Current Images Display -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Current Images</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="small text-muted mb-1">Current Logo:</p>
                                    @if($store->logo)
                                        <img src="{{ asset($store->logo) }}" alt="Logo" 
                                             style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px;">
                                    @else
                                        <p class="text-muted small">No logo uploaded</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p class="small text-muted mb-1">Current Banner:</p>
                                    @if($store->banner)
                                        <img src="{{ asset($store->banner) }}" alt="Banner" 
                                             style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px;">
                                    @else
                                        <p class="text-muted small">No banner uploaded</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- New Logo Upload -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Update Logo (optional)</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep current logo</small>
                        </div>
                        
                        <!-- New Banner Upload -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Update Banner (optional)</label>
                            <input type="file" name="banner" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep current banner</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Store
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addProductForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type *</label>
                            <select name="type" class="form-select" required>
                                <option value="product">Physical Product</option>
                                <option value="service">Service</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price *</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Currency *</label>
                            <select name="currency" class="form-select" required>
                                <option value="NGN">NGN</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Images</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">You can select multiple images</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Add this EDIT PRODUCT MODAL after your Add Product Modal -->

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProductForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit_product_id" name="product_id">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type *</label>
                            <select name="type" id="edit_type" class="form-select" required>
                                <option value="product">Physical Product</option>
                                <option value="service">Service</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price *</label>
                            <input type="number" name="price" id="edit_price" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Currency *</label>
                            <select name="currency" id="edit_currency" class="form-select" required>
                                <option value="NGN">NGN</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" id="edit_stock" class="form-control" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" id="edit_category" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="draft">Draft</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Update Images (optional)</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Leave empty to keep existing images</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- 1. jQuery FIRST (with fallback) -->
<script 
    src="https://code.jquery.com/jquery-3.7.1.min.js" 
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
    crossorigin="anonymous"
    onerror="this.onerror=null; this.src='{{ asset('js/jquery-3.7.1.min.js') }}'">
</script>

<!-- Verify jQuery loaded -->
<script>
if (typeof jQuery === 'undefined') {
    console.error('❌ jQuery failed to load!');
    document.write('<script src="{{ asset('js/jquery-3.7.1.min.js') }}"><\/script>');
} else {
    console.log('✅ jQuery loaded successfully');
}
</script>

<!-- 2. jQuery Plugins (AFTER jQuery) -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script>

<!-- 3. Bootstrap & Other Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

<!-- 4. YOUR Custom Scripts (LAST - they depend on jQuery) -->
<script src="{{ asset('myjs/bar.js') }}"></script>
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>


<script>
// Store products data for editing
const productsData = @json($products->items());

// Add Product
document.getElementById('addProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    submitBtn.disabled = true;
    
    fetch('{{ route("marketplace.add-product") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.error || 'Failed to add product');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Edit Product - Open Modal with Data
 function editProduct (productId) {
    // Find product data
    const product = productsData.find(p => p.id === productId);
    
    if (!product) {
        alert('Product not found');
        return;
    }
    
    // Populate form
    document.getElementById('edit_product_id').value = product.id;
    document.getElementById('edit_name').value = product.name;
    document.getElementById('edit_type').value = product.type;
    document.getElementById('edit_price').value = product.price;
    document.getElementById('edit_currency').value = product.currency;
    document.getElementById('edit_stock').value = product.stock || 0;
    document.getElementById('edit_category').value = product.category || '';
    document.getElementById('edit_status').value = product.status;
    document.getElementById('edit_description').value = product.description || '';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
    modal.show();
}

// Edit Product - Submit Form
document.getElementById('editProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const productId = document.getElementById('edit_product_id').value;
    const formData = new FormData(this);
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    submitBtn.disabled = true;
    
    fetch(`/marketplace/update-product/${productId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.error || 'Failed to update product');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Delete Product
function deleteProduct(productId) {
    if(!confirm('Are you sure you want to delete this product?')) return;
    
    fetch(`/marketplace/delete-product/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.error || 'Failed to delete product');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
</script>


   <script>
document.getElementById('updateStoreForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    submitBtn.disabled = true;
    
    fetch('{{ route("marketplace.update-store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.error || 'Failed to update store');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>


@endsection
</body>
</html>
