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

    <title>Apply for Payment - SupperAge</title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/talemodel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
   
    <style>
        .verification-card {
            text-align: center;
            padding: 40px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .verification-card i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.9;
            animation: pulse 2s infinite;
        }
        .verification-card h3 {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 1.8rem;
        }
        .verification-card p {
            font-size: 1rem;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .verify-btn {
            background: white;
            color: #667eea;
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .verify-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            background: #f0f0f0;
            color: #5568d3;
        }
        .verify-btn i {
            font-size: 1rem;
            margin-right: 8px;
            animation: none;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .verification-card {
                padding: 30px 15px;
            }
            .verification-card i {
                font-size: 3rem;
            }
            .verification-card h3 {
                font-size: 1.5rem;
            }
            .verification-card p {
                font-size: 0.95rem;
            }
            .verify-btn {
                padding: 12px 25px;
                font-size: 1rem;
                width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .verification-card {
                padding: 25px 10px;
            }
            .verification-card i {
                font-size: 2.5rem;
            }
            .verification-card h3 {
                font-size: 1.3rem;
            }
            .verify-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Check if user has badge_status --}}
            @if(!$user->badge_status)
                {{-- No badge at all --}}
                <div class="verification-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Account Verification Required</h3>
                    <p>You need to verify your account before you can withdraw funds.</p>
                    <p class="mb-0"><small>Verification helps protect your account and ensures secure transactions.</small></p>
                    <form action="{{ route('badge.verify.wallet') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="verify-btn">
                            <i class="fas fa-check-circle"></i> Verify Account – NGN 4,000
                        </button>
                        <p class="mt-3 mb-0"><small>Amount will be deducted from your wallet</small></p>
                    </form>
                </div>

            @elseif(isset($badgeExpired) && $badgeExpired)
                {{-- Badge exists but expired --}}
                <div class="verification-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Verification Badge Expired</h3>
                    <p>Your verification badge expired on {{ \Carbon\Carbon::parse($user->badge_expires_at)->format('M d, Y') }}.</p>
                    <p class="mb-0"><small>Renew your badge to continue withdrawing funds.</small></p>
                    <form action="{{ route('badge.verify.wallet') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="verify-btn">
                            <i class="fas fa-redo"></i> Renew Badge – NGN 4,000
                        </button>
                        <p class="mt-3 mb-0"><small>Amount will be deducted from your wallet</small></p>
                    </form>
                </div>

            @else
                {{-- Badge exists and is valid → show withdraw form --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-money-check-alt"></i> Apply for Payment</h4>
                        <span class="badge badge-light">
                            <i class="fas fa-shield-check"></i> Verified
                        </span>
                    </div>
                    <div class="card-body">
    {{-- Check for rejected applications with admin notes --}}
    @php
        $rejectedWithNote = $applications->where('status', 'rejected')
            ->whereNotNull('admin_note')
            ->sortByDesc('created_at')
            ->first();
    @endphp
    
    @if($rejectedWithNote)
        <div class="alert alert-danger alert-dismissible fade show">
            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Application Rejected</h6>
            <p class="mb-0">
                Your payment application from {{ \Carbon\Carbon::parse($rejectedWithNote->created_at)->format('M d, Y') }} 
                for {{ $rejectedWithNote->currency }} {{ number_format($rejectedWithNote->amount_requested, 2) }} was rejected.
            </p>
            <hr>
            <p class="mb-0"><strong>Reason:</strong> {{ $rejectedWithNote->admin_note }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <!-- Rest of your form -->

                        @if($pendingApp)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> You already have a pending payment application. 
                                Please wait for admin approval.
                            </div>
                        @endif

                        <!-- Important Notice -->
                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Withdrawal Policy</h6>
                            <ul class="mb-0">
                                <li>You can withdraw up to <strong>50% of your wallet balance</strong></li>
                                <li>You will receive <strong>50% of the requested amount</strong></li>
                                <li><strong>50% goes to SupperAge</strong> as platform fee</li>
                            </ul>
                            <hr>
                            <small class="text-muted">Example: If you request NGN 10,000, you'll receive NGN 5,000</small>
                        </div>

                        <!-- Wallet Balance -->
                        <div class="card mb-4 bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Your Wallet Balance</h5>
                                @forelse($balances as $balance)
                                    @php
                                        $maxWithdrawable = $balance->total * 0.50;
                                        $youWillReceive = $maxWithdrawable * 0.50;
                                    @endphp
                                    <div class="mb-3 p-3 bg-white rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>{{ $balance->currency }}:</strong> 
                                                <span class="text-success h5">{{ number_format($balance->total, 2) }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Max Withdrawable (50%):</small><br>
                                                <strong class="text-primary">{{ number_format($maxWithdrawable, 2) }} {{ $balance->currency }}</strong><br>
                                                <small class="text-muted">You'll receive (50% of withdrawal):</small><br>
                                                <strong class="text-success">{{ number_format($youWillReceive, 2) }} {{ $balance->currency }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">No balance available</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Payment Application Form -->
                        <form method="POST" action="{{ route('payment.apply.submit') }}" id="paymentForm">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="">Select payment method</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="flutterwave">Flutterwave</option>
                                </select>
                            </div>

                            <!-- Bank Details (shown for bank_transfer) -->
                            <div id="bank-details" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_name" class="form-control" placeholder="e.g., First Bank">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" name="account_number" class="form-control" 
                                           placeholder="10-digit account number" maxlength="10">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Account Name <span class="text-danger">*</span></label>
                                    <input type="text" name="account_name" class="form-control" 
                                           placeholder="Name as it appears on bank account">
                                </div>
                            </div>

                            <!-- PayPal Details -->
                            <div id="paypal-details" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">PayPal Email <span class="text-danger">*</span></label>
                                    <input type="email" name="paypal_email" class="form-control" 
                                           placeholder="your@paypal.com">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Amount Requested <span class="text-danger">*</span></label>
                                    <input type="number" name="amount_requested" class="form-control" 
                                           step="0.01" min="1" required placeholder="0.00">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                                    <select name="currency" class="form-select" required>
                                        <option value="NGN">NGN - Nigerian Naira</option>
                                        <option value="USD">USD - US Dollar</option>
                                        <option value="GBP">GBP - British Pound</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="GHS">GHS - Ghanaian Cedi</option>
                                        <option value="KES">KES - Kenyan Shilling</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Reason for Payment (Optional)</label>
                                <textarea name="reason" class="form-control" rows="3" 
                                          placeholder="Brief explanation of why you're requesting this payment" 
                                          maxlength="500"></textarea>
                                <small class="text-muted">Max 500 characters</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg" {{ $pendingApp ? 'disabled' : '' }}>
                                    <i class="fas fa-paper-plane"></i> Submit Application
                                </button>
                                <a href="{{ route('mywallet') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Wallet
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Application History -->
                <!-- Application History -->
@if($applications->count() > 0)
<div class="card shadow-sm mt-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Application History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Admin Note</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y') }}</td>
                        <td>{{ $app->currency }} {{ number_format($app->amount_requested, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $app->payment_method)) }}</td>
                        <td>
                            @if($app->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($app->status == 'approved')
                                <span class="badge bg-info">Approved</span>
                            @elseif($app->status == 'paid')
                                <span class="badge bg-success">Paid</span>
                                @if($app->paid_at)
                                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($app->paid_at)->format('M d, Y') }}</small>
                                @endif
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            @if($app->admin_note)
                                <div class="alert alert-{{ $app->status == 'rejected' ? 'danger' : 'info' }} py-2 px-3 mb-0">
                                    <i class="fas fa-{{ $app->status == 'rejected' ? 'exclamation-circle' : 'info-circle' }}"></i>
                                    {{ $app->admin_note }}
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($app->status == 'pending')
                                <button class="btn btn-sm btn-danger" 
                                        onclick="cancelApplication({{ $app->id }})">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

            @endif {{-- ✅ ADD THIS LINE - closes the main @if(!$user->badge_status) --}}

        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
document.getElementById('payment_method')?.addEventListener('change', function() {
    const method = this.value;
    const bankDetails = document.getElementById('bank-details');
    const paypalDetails = document.getElementById('paypal-details');
    
    if (bankDetails && paypalDetails) {
        // Hide all
        bankDetails.style.display = 'none';
        paypalDetails.style.display = 'none';
        
        // Remove required from all inputs first
        bankDetails.querySelectorAll('input').forEach(input => input.required = false);
        paypalDetails.querySelectorAll('input').forEach(input => input.required = false);
        
        // Show relevant fields
        if(method === 'bank_transfer') {
            bankDetails.style.display = 'block';
            bankDetails.querySelectorAll('input').forEach(input => input.required = true);
        } else if(method === 'paypal') {
            paypalDetails.style.display = 'block';
            paypalDetails.querySelector('input').required = true;
        }
    }
});

function cancelApplication(id) {
    if(confirm('Are you sure you want to cancel this application?')) {
        fetch(`/payment/applications/${id}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
}
</script>

@include('partials.global-calls')
</body>
</html>