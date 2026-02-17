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

    <title>  products - SupperAge</title>

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
            <li class="breadcrumb-item"><a href="{{ route('marketplace.view-store', $product->store->store_slug) }}">{{ $product->store->store_name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    @if($product->images && count($product->images) > 0)
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($product->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset($image) }}" class="d-block w-100" style="max-height: 500px; object-fit: contain;" alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>
                            @if(count($product->images) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-5 bg-light">
                            <i class="fas fa-image fa-5x text-muted"></i>
                            <p class="text-muted mt-3">No images available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Stats -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="mb-0">{{ $product->views }}</h5>
                            <small class="text-muted">Views</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">{{ $product->orders }}</h5>
                            <small class="text-muted">Orders</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0">{{ $product->type === 'product' ? $product->stock : '∞' }}</h5>
                            <small class="text-muted">{{ $product->type === 'product' ? 'In Stock' : 'Available' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="fw-bold mb-3">{{ $product->name }}</h2>
                    
                    <div class="d-flex align-items-center mb-3">
                        <h3 class="text-primary fw-bold mb-0">{{ $product->currency }} {{ number_format($product->price, 2) }}</h3>
                        <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }} ms-3">
                            {{ ucfirst($product->status) }}
                        </span>
                        <span class="badge bg-info ms-2">{{ ucfirst($product->type) }}</span>
                    </div>

                    @if($product->category)
                        <p class="text-muted mb-3">
                            <i class="fas fa-tag"></i> Category: <strong>{{ $product->category }}</strong>
                        </p>
                    @endif

                    <hr>

                    <h5 class="fw-bold mb-3">Description</h5>
                    <p class="text-secondary">{{ $product->description ?: 'No description available.' }}</p>

                    <hr>

                    <!-- Store Info -->
                    <div class="d-flex align-items-center mb-4">
                        @if($product->store->logo)
                            <img src="{{ asset($product->store->logo) }}" class="rounded-circle me-3" 
                                 style="width: 50px; height: 50px; object-fit: cover;" alt="{{ $product->store->store_name }}">
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-store text-white"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0">{{ $product->store->store_name }}</h6>
                            <small class="text-muted">by {{ $product->store->owner->name }}</small>
                        </div>
                        <a href="{{ route('marketplace.view-store', $product->store->store_slug) }}" class="btn btn-sm btn-outline-primary ms-auto">
                            Visit Store
                        </a>
                    </div>

                    <!-- Order Button -->
                    @if($product->status === 'active')
                        @if($product->type === 'product' && $product->stock <= 0)
                            <button class="btn btn-secondary btn-lg w-100" disabled>
                                <i class="fas fa-times"></i> Out of Stock
                            </button>
                        @else
                            <button class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#orderModal">
                                <i class="fas fa-shopping-cart"></i> Place Order
                            </button>
                        @endif
                    @else
                        <button class="btn btn-secondary btn-lg w-100" disabled>
                            Product Not Available
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h4 class="fw-bold mb-4">More from {{ $product->store->store_name }}</h4>
            <div class="row g-3">
                @foreach($relatedProducts as $related)
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100 hover-shadow">
                            @if($related->images && count($related->images) > 0)
                                <img src="{{ asset($related->images[0]) }}" class="card-img-top" 
                                     style="height: 200px; object-fit: cover;" alt="{{ $related->name }}">
                            @else
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="fas fa-image fa-2x text-white"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ Str::limit($related->name, 40) }}</h6>
                                <p class="text-primary fw-bold mb-2">{{ $related->currency }} {{ number_format($related->price, 2) }}</p>
                                <a href="{{ route('marketplace.view-product', $related->slug) }}" class="btn btn-sm btn-outline-primary w-100">
                                    View Product
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Place Order: {{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="orderForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Price:</strong> {{ $product->currency }} {{ number_format($product->price, 2) }}
                        @if($product->type === 'product')
                            <br><strong>Available Stock:</strong> {{ $product->stock }}
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity *</label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" 
                                   max="{{ $product->type === 'product' ? $product->stock : 999 }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Amount</label>
                            <input type="text" class="form-control" id="totalAmount" readonly 
                                   value="{{ $product->currency }} {{ number_format($product->price, 2) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Your Name *</label>
                            <input type="text" name="buyer_name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="buyer_email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone *</label>
                            <input type="text" name="buyer_phone" class="form-control" value="{{ $user->phone ?? '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country *</label>
                            <input type="text" name="buyer_country" class="form-control" value="{{ $user->country ?? 'NG' }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Delivery Address *</label>
                            <textarea name="buyer_address" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="buyer_city" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" name="buyer_state" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Confirm Order
                    </button>
                </div>
            </form>
        </div>
    </div>
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
// Calculate total amount
document.querySelector('input[name="quantity"]').addEventListener('input', function() {
    const quantity = parseInt(this.value) || 1;
    const price = {{ $product->price }};
    const total = quantity * price;
    document.getElementById('totalAmount').value = '{{ $product->currency }} ' + total.toFixed(2);
});

// Submit order
document.getElementById('orderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('{{ route("marketplace.place-order") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(data.message + '\nOrder Number: ' + data.order_number);
            window.location.href = '{{ route("marketplace.my-orders") }}';
        } else {
            alert(data.error || 'Failed to place order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
});
</script>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
</style>










<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Then other jQuery-based scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.1/dist/emoji-button.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/js/all.min.js"></script> -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

@endsection