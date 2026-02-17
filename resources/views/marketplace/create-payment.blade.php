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

    <title>Create Your Store - SupperAge</title>

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

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .store-container {
            padding: 20px 15px;
        }

        .store-card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .store-header {
            background: var(--primary-gradient);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .store-header h3 {
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .store-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .store-body {
            padding: 25px 20px;
        }

        .benefits-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #2196f3;
        }

        .benefits-card h5 {
            color: #1565c0;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .benefits-card ul {
            margin: 0;
            padding-left: 20px;
        }

        .benefits-card li {
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
        }

        .pricing-section {
            text-align: center;
            background: white;
            border-radius: 15px;
            padding: 25px 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .pricing-section h4 {
            font-size: 18px;
            color: #555;
            margin-bottom: 15px;
        }

        .pricing-amount {
            font-size: 48px;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .pricing-period {
            color: #666;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .pricing-note {
            color: #999;
            font-size: 13px;
        }

        .payment-methods-title {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .payment-card {
            border-radius: 15px;
            padding: 25px 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            background: white;
        }

        .payment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .payment-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .payment-card h5 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .payment-card p {
            color: #666;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #555;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-select, .form-control {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-select:focus, .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .balance-alert {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        .balance-alert strong {
            display: block;
            margin-bottom: 10px;
            color: #155724;
            font-size: 14px;
        }

        .balance-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .balance-item:last-child {
            border-bottom: none;
        }

        .balance-currency {
            font-weight: 600;
            color: #333;
        }

        .balance-amount {
            color: #28a745;
            font-weight: 700;
        }

        .amount-display {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
        }

        .amount-display strong {
            color: #555;
            font-size: 14px;
        }

        .amount-display span {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-top: 5px;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 14px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 16px;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-success {
            background: var(--success-gradient);
            border: none;
            border-radius: 12px;
            padding: 14px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 16px;
        }

        .btn-success:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(17, 153, 142, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 12px;
            padding: 14px 30px;
            font-weight: 600;
            width: 100%;
            font-size: 16px;
        }

        .insufficient-notice {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 10px;
            padding: 12px 15px;
            margin-top: 15px;
            text-align: center;
        }

        .insufficient-notice small {
            color: #856404;
            font-size: 13px;
        }

        .terms-section {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px solid #f0f0f0;
        }

        .form-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .form-check-label {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #764ba2;
            transform: translateX(-5px);
        }

        /* Mobile Optimizations */
        @media (max-width: 767.98px) {
            .store-container {
                padding: 15px 10px;
            }

            .store-header {
                padding: 25px 15px;
            }

            .store-header h3 {
                font-size: 20px;
            }

            .store-body {
                padding: 20px 15px;
            }

            .pricing-amount {
                font-size: 42px;
            }

            .payment-card {
                padding: 20px 15px;
            }

            .payment-icon {
                font-size: 40px;
            }

            .btn-primary, .btn-success, .btn-secondary {
                padding: 12px 20px;
                font-size: 15px;
            }
        }

        /* Modal Improvements */
        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 20px;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-body h6 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .modal-body ol {
            padding-left: 20px;
        }

        .modal-body li {
            margin-bottom: 10px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    @include('layouts.navbar')

    @extends('layouts.app')
    @section('content')
    <div class="container store-container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card store-card">
                    <div class="store-header">
                        <h3><i class="fas fa-store"></i> Create Your Store</h3>
                        <p>Start selling on our marketplace today!</p>
                    </div>
                    
                    <div class="store-body">
                        <!-- Store Benefits -->
                        <div class="benefits-card">
                            <h5><i class="fas fa-info-circle"></i> What You Get</h5>
                            <ul>
                                <li>Your own customizable storefront</li>
                                <li>Unlimited product listings</li>
                                <li>Order management system</li>
                                <li>Analytics and insights</li>
                                <li>Customer notifications</li>
                            </ul>
                        </div>

                        <!-- Pricing Info -->
                        <div class="pricing-section">
                            <h4>Store Setup Fee</h4>
                            <div class="pricing-amount">₦2,500</div>
                            <div class="pricing-period">Monthly subscription</div>
                            <small class="pricing-note">Auto-renews every 30 days</small>
                        </div>

                        <!-- Payment Options -->
                        <h5 class="payment-methods-title">Choose Payment Method</h5>

                        <!-- Pay with Card/Bank -->
                        <div class="payment-card">
                            <div class="text-center">
                                <i class="fas fa-credit-card payment-icon" style="color: #667eea;"></i>
                                <h5>Pay with Card/Bank</h5>
                                <p>Secure payment via Flutterwave</p>
                            </div>
                            
                            <form method="POST" action="{{ route('marketplace.process-payment') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Select Currency</label>
                                    <select name="currency" class="form-select" id="cardCurrency" onchange="updateCardAmount()">
                                        <option value="NGN" selected>🇳🇬 Nigerian Naira (NGN)</option>
                                        <option value="USD">🇺🇸 US Dollar (USD)</option>
                                        <option value="GBP">🇬🇧 British Pound (GBP)</option>
                                        <option value="EUR">🇪🇺 Euro (EUR)</option>
                                    </select>
                                </div>
                                <div class="amount-display">
                                    <strong>Amount:</strong>
                                    <span id="cardAmount">₦2,500.00</span>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-credit-card"></i> Pay Now
                                </button>
                            </form>
                        </div>

                        <!-- Pay from Wallet -->
                        <div class="payment-card">
                            <div class="text-center">
                                <i class="fas fa-wallet payment-icon" style="color: #11998e;"></i>
                                <h5>Pay from Wallet</h5>
                                <p>Use your account balance</p>
                            </div>
                            
                            <div class="balance-alert">
                                <strong>Your Wallet Balances:</strong>
                                @if(isset($balances) && count($balances) > 0)
                                    @foreach($balances as $currency => $amount)
                                        <div class="balance-item">
                                            <span class="balance-currency">{{ $currency }}</span>
                                            <span class="balance-amount">{{ number_format($amount, 2) }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center" style="padding: 10px 0; color: #666;">
                                        No balance available
                                    </div>
                                @endif
                            </div>
                            
                            <form method="POST" action="{{ route('marketplace.pay-from-wallet') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Select Currency</label>
                                    <select name="currency" class="form-select" id="walletCurrency">
                                        @if(isset($balances) && count($balances) > 0)
                                            @foreach($balances as $currency => $amount)
                                                <option value="{{ $currency }}">{{ $currency }} ({{ number_format($amount, 2) }})</option>
                                            @endforeach
                                        @else
                                            <option value="NGN">NGN (No balance)</option>
                                        @endif
                                    </select>
                                </div>
                                
                                @php
                                    $hasAnySufficientBalance = false;
                                    if(isset($balances)) {
                                        foreach($balances as $curr => $amt) {
                                            if($amt >= 2500) {
                                                $hasAnySufficientBalance = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                
                                @if($hasAnySufficientBalance)
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-wallet"></i> Pay from Wallet
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary" disabled>
                                        <i class="fas fa-times"></i> Insufficient Balance
                                    </button>
                                    <div class="insufficient-notice">
                                        <small>
                                            <i class="fas fa-exclamation-circle"></i>
                                            You need at least ₦2,500 or equivalent
                                        </small>
                                    </div>
                                @endif
                            </form>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="terms-section">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                </label>
                            </div>
                        </div>

                        <!-- Back Link -->
                        <div class="text-center">
                            <a href="{{ route('marketplace.index') }}" class="back-link">
                                <i class="fas fa-arrow-left"></i> Back to Marketplace
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Store Subscription Agreement</h6>
                    <ol>
                        <li>The store setup fee of ₦2,500 is charged monthly.</li>
                        <li>Subscription auto-renews every 30 days unless cancelled.</li>
                        <li>You will receive reminders 7, 3, and 1 day before expiry.</li>
                        <li>Expired subscriptions result in store suspension.</li>
                        <li>You can renew your subscription at any time.</li>
                        <li>All sales are subject to our marketplace policies.</li>
                        <li>You are responsible for product descriptions and fulfillment.</li>
                        <li>We reserve the right to suspend stores that violate policies.</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
// Improved terms agreement handling
const agreeTerms = document.getElementById('agreeTerms');
const payButtons = document.querySelectorAll('button[type="submit"]');

// Function to update button states
function updateButtonStates() {
    payButtons.forEach(button => {
        // Skip buttons that are already disabled for other reasons (insufficient balance)
        if (button.classList.contains('btn-secondary')) {
            return; // Keep these disabled
        }
        
        // Enable/disable based on terms checkbox
        button.disabled = !agreeTerms.checked;
    });
}

// Listen for checkbox changes
agreeTerms.addEventListener('change', updateButtonStates);

// Initial state
updateButtonStates();

// Add form submit validation
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (!agreeTerms.checked) {
            e.preventDefault();
            alert('Please agree to the Terms and Conditions before proceeding.');
            agreeTerms.focus();
            return false;
        }
    });
});

// Update card payment amount based on currency
function updateCardAmount() {
    const currency = document.getElementById('cardCurrency').value;
    const amountDisplay = document.getElementById('cardAmount');
    
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
        })
        .catch(error => {
            console.error('Error fetching conversion rate:', error);
        });
}
</script>
    @endsection
</body>
</html>