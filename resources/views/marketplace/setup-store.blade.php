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

    <title>  set up store- SupperAge</title>

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
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center py-3">
                    <h3 class="mb-0"><i class="fas fa-check-circle"></i> Payment Successful!</h3>
                    <p class="mb-0">Now let's set up your store</p>
                </div>
                <div class="card-body p-5">
                    <form id="setupStoreForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Store Basic Info -->
                        <h5 class="mb-3"><i class="fas fa-store"></i> Store Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Store Name *</label>
                                <input type="text" name="store_name" class="form-control" required 
                                       placeholder="e.g., John's Electronics">
                                <small class="text-muted">Choose a unique and memorable name</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Store Description</label>
                                <textarea name="description" class="form-control" rows="3" 
                                          placeholder="Tell customers about your store and what you sell"></textarea>
                            </div>
                        </div>

                        <!-- Contact Details -->
                        <h5 class="mb-3"><i class="fas fa-phone"></i> Contact Details</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number *</label>
                                <input type="text" name="phone" class="form-control" value="{{ $user->phone ?? '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country *</label>
                                <select name="country" class="form-select" required>
                                    <option value="NG" {{ ($user->country ?? 'NG') === 'NG' ? 'selected' : '' }}>🇳🇬 Nigeria</option>
                                    <option value="US" {{ ($user->country ?? '') === 'US' ? 'selected' : '' }}>🇺🇸 United States</option>
                                    <option value="GB" {{ ($user->country ?? '') === 'GB' ? 'selected' : '' }}>🇬🇧 United Kingdom</option>
                                    <option value="GH" {{ ($user->country ?? '') === 'GH' ? 'selected' : '' }}>🇬🇭 Ghana</option>
                                    <option value="KE" {{ ($user->country ?? '') === 'KE' ? 'selected' : '' }}>🇰🇪 Kenya</option>
                                    <option value="ZA" {{ ($user->country ?? '') === 'ZA' ? 'selected' : '' }}>🇿🇦 South Africa</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Business Address *</label>
                                <textarea name="address" class="form-control" rows="2" required 
                                          placeholder="Enter your business address"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State/Region</label>
                                <input type="text" name="state" class="form-control">
                            </div>
                        </div>

                        <!-- Branding -->
                        <h5 class="mb-3"><i class="fas fa-image"></i> Store Branding</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Store Logo</label>
                                <input type="file" name="logo" class="form-control" accept="image/*" onchange="previewImage(this, 'logoPreview')">
                                <small class="text-muted">Square image recommended (e.g., 500x500px)</small>
                                <div id="logoPreview" class="mt-2"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Store Banner</label>
                                <input type="file" name="banner" class="form-control" accept="image/*" onchange="previewImage(this, 'bannerPreview')">
                                <small class="text-muted">Wide image recommended (e.g., 1200x400px)</small>
                                <div id="bannerPreview" class="mt-2"></div>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> What's Next?</h6>
                            <ul class="mb-0">
                                <li>Your store will be created immediately</li>
                                <li>You can start adding products right away</li>
                                <li>Your store will be visible to all marketplace users</li>
                                <li>You'll receive notifications for new orders</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check"></i> Create My Store
                            </button>
                            <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
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
// Image preview function
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail mt-2';
            img.style.maxWidth = '200px';
            preview.appendChild(img);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Form submission
document.getElementById('setupStoreForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Store...';
    
    const formData = new FormData(this);
    
    fetch('{{ route("marketplace.create-store") }}', {
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
            window.location.href = data.redirect;
        } else {
            alert(data.error || 'Failed to create store');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Create My Store';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check"></i> Create My Store';
    });
});
</script>
@endsection