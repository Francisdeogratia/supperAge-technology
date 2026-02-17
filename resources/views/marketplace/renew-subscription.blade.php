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

    <title>  renew sub- SupperAge</title>

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
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-dark text-center py-4">
                    <h3 class="mb-0"><i class="fas fa-sync"></i> Renew Your Subscription</h3>
                    <p class="mb-0">Keep your store active and visible</p>
                </div>
                <div class="card-body p-5">
                    <!-- Current Subscription Status -->
                    <div class="alert alert-{{ $store->isSubscriptionExpired() ? 'danger' : 'info' }} mb-4">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle"></i> Current Subscription Status
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Store Name:</strong> {{ $store->store_name }}</p>
                                <p class="mb-1"><strong>Status:</strong> 
                                    <span class="badge bg-{{ $store->subscription_status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($store->subscription_status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if($store->subscription_expires_at)
                                    <p class="mb-1"><strong>Current Expiry:</strong> {{ $store->subscription_expires_at->format('M d, Y h:i A') }}</p>
                                    @if($store->isSubscriptionExpired())
                                        <p class="mb-0 text-danger"><strong>Expired {{ $store->subscription_expires_at->diffForHumans() }}</strong></p>
                                    @else
                                        <p class="mb-0"><strong>Days Remaining:</strong> {{ $store->daysUntilExpiry() }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        @if($store->isSubscriptionExpired())
                            <hr>
                            <p class="mb-0 text-danger">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Your store is currently suspended.</strong> Renew now to reactivate.
                            </p>
                        @endif
                    </div>

                    <!-- What Happens After Renewal -->
                    <div class="alert alert-success mb-4">
                        <h6 class="alert-heading"><i class="fas fa-check-circle"></i> After Renewal:</h6>
                        <ul class="mb-0">
                            @if($store->isSubscriptionExpired())
                                <li>Your store will be reactivated immediately</li>
                            @else
                                <li>Your subscription will be extended by 30 days</li>
                            @endif
                            <li>New expiry date: <strong>{{ ($store->subscription_expires_at && $store->subscription_expires_at->isFuture() ? $store->subscription_expires_at->copy()->addDays(30) : now()->addDays(30))->format('M d, Y') }}</strong></li>
                            <li>Continue managing products and orders</li>
                            <li>Remain visible to customers</li>
                        </ul>
                    </div>

                    <!-- Renewal Pricing -->
                    <div class="text-center mb-4">
                        <h4 class="mb-3">Monthly Subscription Fee</h4>
                        <div class="display-4 text-warning fw-bold mb-2">₦2,500</div>
                        <p class="text-muted">Valid for 30 days from renewal</p>
                    </div>

                    <!-- Payment Options -->
                    <div class="row g-3">
                        <div class="col-12">
                            <h5 class="mb-3">Choose Payment Method</h5>
                        </div>

                        <!-- Pay with Card/Bank -->
                        <div class="col-md-6">
                            <div class="card h-100 border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-credit-card fa-3x text-warning mb-3"></i>
                                    <h5 class="card-title">Pay with Card/Bank</h5>
                                    <p class="text-muted small">Secure payment via Flutterwave</p>
                                    
                                    <form method="POST" action="{{ route('marketplace.process-renewal') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Select Currency</label>
                                            <select name="currency" class="form-select" id="renewalCurrency" onchange="updateRenewalAmount()">
                                                <option value="NGN" selected>🇳🇬 Nigerian Naira (NGN)</option>
                                                <option value="USD">🇺🇸 US Dollar (USD)</option>
                                                <option value="GBP">🇬🇧 British Pound (GBP)</option>
                                                <option value="EUR">🇪🇺 Euro (EUR)</option>
                                            </select>
                                        </div>
                                        <div class="alert alert-light">
                                            <strong>Amount:</strong> <span id="renewalAmount">₦2,500.00</span>
                                        </div>
                                        <button type="submit" class="btn btn-warning w-100" id="cardPayBtn">
                                            <i class="fas fa-credit-card"></i> Pay & Renew
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Pay from Wallet -->
                        <div class="col-md-6">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-wallet fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">Pay from Wallet</h5>
                                    <p class="text-muted small">Use your account balance</p>
                                    
                                    <div class="alert alert-success mb-3">
                                        <strong>Your Balance:</strong><br>
                                        <span class="h5">{{ $user->currency ?? 'NGN' }} {{ number_format($user->balance ?? 0, 2) }}</span>
                                    </div>
                                    
                                    <form method="POST" action="{{ route('marketplace.pay-from-wallet') }}">
                                        @csrf
                                        <input type="hidden" name="currency" value="{{ $user->currency ?? 'NGN' }}">
                                        <input type="hidden" name="renewal" value="1">
                                        
                                        @if(($user->balance ?? 0) >= 2500)
                                            <button type="submit" class="btn btn-success w-100" id="walletPayBtn">
                                                <i class="fas fa-wallet"></i> Pay from Wallet
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary w-100" disabled>
                                                <i class="fas fa-times"></i> Insufficient Balance
                                            </button>
                                            <small class="text-danger d-block mt-2">
                                                You need ₦{{ number_format(2500 - ($user->balance ?? 0), 2) }} more
                                            </small>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="mt-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="agreeRenewal" required>
                            <label class="form-check-label small" for="agreeRenewal">
                                I understand that this subscription is for 30 days and will need to be renewed monthly.
                            </label>
                        </div>
                    </div>

                    <!-- Back Link -->
                    <div class="text-center mt-4">
                        <a href="{{ route('marketplace.my-store') }}" class="text-muted">
                            <i class="fas fa-arrow-left"></i> Back to My Store
                        </a>
                    </div>
                </div>
            </div>

            <!-- Subscription History (Optional) -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Subscription History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Started</th>
                                    <th>Expired</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($store->subscription_started_at)
                                    <tr>
                                        <td>{{ $store->subscription_started_at->format('M d, Y') }}</td>
                                        <td>{{ $store->subscription_expires_at ? $store->subscription_expires_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>30 days</td>
                                        <td>
                                            <span class="badge bg-{{ $store->subscription_status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($store->subscription_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No history available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
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
// Disable payment buttons until terms are agreed
const agreeRenewal = document.getElementById('agreeRenewal');
const cardPayBtn = document.getElementById('cardPayBtn');
const walletPayBtn = document.getElementById('walletPayBtn');

agreeRenewal.addEventListener('change', function() {
    if (cardPayBtn) cardPayBtn.disabled = !this.checked;
    if (walletPayBtn) walletPayBtn.disabled = !this.checked;
});

// Disable initially
if (cardPayBtn) cardPayBtn.disabled = true;
if (walletPayBtn) walletPayBtn.disabled = true;

// Update renewal amount based on currency
function updateRenewalAmount() {
    const currency = document.getElementById('renewalCurrency').value;
    const amountDisplay = document.getElementById('renewalAmount');
    
    fetch(`{{ route('marketplace.conversion-rate') }}?currency=${currency}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const symbols = {
                    'NGN': '₦',
                    'USD': '$',
                    'GBP': '£',
                    'EUR': '€'
                };
                amountDisplay.textContent = symbols[currency] + data.convertedAmount.toFixed(2);
            }
        });
}
</script>
@endsection