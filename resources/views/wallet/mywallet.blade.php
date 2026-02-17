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

    <title>My Wallet - SupperAge</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

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
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --card-hover-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Profile Card */
        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            text-align: center;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover-shadow);
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 5px solid #667eea;
            padding: 3px;
            margin-bottom: 15px;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }

        .online-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        .online-status.offline {
            background: #fce4ec;
            color: #c2185b;
        }

        .online-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #4caf50;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Wallet Balance Card */
        .balance-card {
            background: var(--primary-gradient);
            border-radius: 20px;
            padding: 30px;
            color: white;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .balance-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover-shadow);
        }

        .balance-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .balance-card h3 {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .currency-item {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .currency-item:hover {
            background: rgba(255,255,255,0.25);
            transform: translateX(5px);
        }

        .currency-amount {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
        }

        .currency-code {
            font-size: 14px;
            opacity: 0.9;
        }

        /* Action Buttons */
        .action-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 5px;
            position: relative;
            z-index: 1;
        }

        .action-btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Verification Card */
        .verification-card {
            background: var(--info-gradient);
            border-radius: 20px;
            padding: 20px;
            color: white;
            box-shadow: var(--card-shadow);
            text-align: center;
            transition: all 0.3s ease;
        }

        .verification-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-hover-shadow);
        }

        .verification-card i {
            font-size: 40px;
            margin-bottom: 10px;
        }

        /* Funding Form Card */
        .funding-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .funding-card:hover {
            box-shadow: var(--card-hover-shadow);
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-process {
            background: var(--success-gradient);
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-process:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(17, 153, 142, 0.3);
        }

        /* Success Alert */
        .success-alert {
            background: var(--success-gradient);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--card-shadow);
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Transaction History Section */
        .transactions-header {
            background: white;
            border-radius: 20px 20px 0 0;
            padding: 20px 30px;
            box-shadow: var(--card-shadow);
            margin-top: 30px;
        }

        .filter-btn-group {
            background: #f5f5f5;
            border-radius: 12px;
            padding: 5px;
            display: inline-flex;
            gap: 5px;
        }

        .filter-btn {
            border: none;
            background: transparent;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: #666;
        }

        .filter-btn.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }

        .currency-filter {
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .currency-filter:focus {
            border-color: #667eea;
            outline: none;
        }

        /* Table Styling */
        .table-container {
            background: white;
            border-radius: 0 0 20px 20px;
            padding: 20px;
            box-shadow: var(--card-shadow);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f0f0f0;
        }

        .badge-credit {
            background: var(--success-gradient);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        .badge-debit {
            background: var(--danger-gradient);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        /* Load More Button */
        .load-more-btn {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 12px 40px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .load-more-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }

        /* Mobile Responsive */
        @media (max-width: 767.98px) {
            .profile-avatar {
                width: 80px;
                height: 80px;
            }

            .currency-amount {
                font-size: 22px;
            }

            .action-btn {
                padding: 8px 15px;
                font-size: 13px;
            }

            table.table thead {
                display: none;
            }

            table.table tbody tr {
                display: block;
                margin-bottom: 15px;
                border: 2px solid #e0e0e0;
                border-radius: 15px;
                padding: 15px;
                background: white;
            }

            table.table tbody td {
                display: flex;
                justify-content: space-between;
                padding: 10px 0;
                border: none;
                border-bottom: 1px solid #f0f0f0;
            }

            table.table tbody td:last-child {
                border-bottom: none;
            }

            table.table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #667eea;
                flex-basis: 50%;
            }
        }

        /* Page Title */
        .page-title {
            text-align: center;
            margin: 20px 0;
            color: #2c3e50;
            font-weight: 700;
            font-size: 28px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        /* Icon Animations */
        .icon-bounce {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }







        .bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
    </style>
</head>
<body>
    @include('layouts.navbar')

    @if(session('payment_success'))
        <div class="container-fluid mt-3">
            <div class="success-alert">
                <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                <strong>Wallet Funding Successful!</strong><br>
                Transaction ID: {{ session('transactionId') }}<br>
                Reference: {{ session('txRef') }}<br>
                Amount Paid: ₦{{ number_format(session('amount'), 2) }}
            </div>
        </div>
    @endif

    @php
      $loginSession = $user->lastLoginSession ?? null;
      $isOnline = $loginSession && $loginSession->logout_at === null;
      $lastSeen = $loginSession && $loginSession->logout_at
          ? \Carbon\Carbon::parse($loginSession->logout_at)->diffForHumans()
          : 'Online now';
    @endphp

    <div class="container-fluid px-4">
        <h1 class="page-title"><i class="fas fa-wallet icon-bounce"></i> My Wallet</h1>
        
        <!-- Profile Card -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-4">
                <div class="profile-card">
                    <img src="{{ $user->profileimg ?? asset('images/default-avatar.png') }}" 
                         alt="Profile" 
                         class="profile-avatar">
                    
                    <h4 class="mb-2">
                        {{ $user->name }}
                        @if($user->badge_status)
                            <img src="{{ asset($user->badge_status) }}" 
                                 alt="Verified" 
                                 title="Verified User" 
                                 style="width:20px;height:20px;margin-left:5px;">
                        @endif
                    </h4>
                    
                    <span class="online-status {{ $isOnline ? '' : 'offline' }}">
                        @if($isOnline)
                            <span class="online-dot"></span> Online now
                        @else
                            <i class="fas fa-clock"></i> {{ $lastSeen }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Balance & Actions -->
            <div class="col-lg-6 mb-4">
                <!-- Wallet Balance -->
                <div class="balance-card mb-4">
                    <h3><i class="fas fa-coins"></i> Wallet Balances</h3>
                    @forelse($balances as $code => $amount)
                        <div class="currency-item">
                            <div>
                                <div class="currency-amount">{{ number_format($amount, 2) }}</div>
                                <div class="currency-code">{{ $currencies[$code] ?? $code }}</div>
                            </div>
                            <i class="fas fa-money-bill-wave" style="font-size: 30px; opacity: 0.5;"></i>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-inbox" style="font-size: 50px; opacity: 0.5;"></i>
                            <p class="mt-3">No funds available</p>
                        </div>
                    @endforelse

                    <div class="text-center mt-4">
                        <button class="action-btn" onclick="copyWalletId({{ $user->id }})">
                            <i class="fas fa-copy"></i> Copy Wallet ID
                        </button>
                        <a href="{{ route('wallet.withdraw') }}" class="action-btn">
                            <i class="fas fa-money-bill-wave"></i> Withdraw Cash
                        </a>
                        <a href="{{ route('wallet.transfer.page') }}" class="action-btn">
                            <i class="fas fa-exchange-alt"></i> Transfer Funds
                        </a>
                    </div>
                </div>

                <!-- Verification Card -->
                <div class="verification-card">
                    <i class="fas fa-shield-alt"></i>
                    <p class="mb-0">Apply or renew your blue badge verification from your wallet balance</p>

                    <!-- Payment Application Button -->
<div class="card mb-4 bg-gradient-primary text-white">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="card-title mb-2">
                    <i class="fas fa-hand-holding-usd"></i> Withdraw Your Earnings
                </h5>
                <p class="card-text mb-0">
                    Have funds in your wallet? Apply to withdraw your money to your bank account or PayPal.
                </p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('payment.apply') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-file-invoice-dollar"></i> Apply for Payment
                </a>
            </div>
        </div>
    </div>
</div>









                </div>
            </div>

            <!-- Right Column: Funding Form -->
            <div class="col-lg-6 mb-4">
                <div class="funding-card">
                    <h4 class="mb-4"><i class="fas fa-credit-card"></i> Fund Your Wallet</h4>
                    
                    <form action="{{ route('wallet.process') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="currency"><i class="fas fa-globe"></i> Select Currency</label>
                            <select name="currency" id="currency" class="form-control" required>
                                @foreach($currencies as $code => $name)
                                    <option value="{{ $code }}">{{ $name }} ({{ $code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="amount"><i class="fas fa-dollar-sign"></i> Enter Amount</label>
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   class="form-control" 
                                   placeholder="Enter amount to fund" 
                                   required 
                                   min="1" 
                                   step="0.01">
                        </div>

                        <button type="submit" class="btn-process">
                            <i class="fas fa-rocket"></i> Process Payment Now
                        </button>
                    </form>

                    <div class="mt-4 p-3" style="background: #f8f9fa; border-radius: 12px;">
                        <h6><i class="fas fa-info-circle"></i> Current Balances:</h6>
                        <ul style="list-style: none; padding-left: 0; margin-bottom: 0;">
                            @forelse($balances as $code => $amount)
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <strong>{{ number_format($amount, 2) }}</strong> {{ $currencies[$code] ?? $code }}
                                </li>
                            @empty
                                <li>No funds available</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="transactions-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="fas fa-history"></i> Transaction History</h4>
            </div>

            <!-- Search Bar -->
            <div class="mb-3">
                <div style="position:relative;">
                    <i class="fas fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#999;"></i>
                    <input type="text" id="txn-search" class="form-control"
                           placeholder="Search by transaction ID, username, date..."
                           style="padding-left:40px;border-radius:12px;border:2px solid #e0e0e0;">
                </div>
            </div>

            <!-- Filters Row -->
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div class="filter-btn-group">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="received">Received</button>
                    <button class="filter-btn" data-filter="sent">Sent</button>
                </div>

                <select id="currency-filter" class="currency-filter">
                    <option value="all">All Currencies</option>
                    @foreach($currencies as $code => $name)
                        <option value="{{ $code }}">{{ $name }} ({{ $code }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-container">
            <div id="txn-loading" style="display:none;text-align:center;padding:30px;">
                <i class="fas fa-spinner fa-spin" style="font-size:24px;color:#667eea;"></i>
                <p style="margin-top:10px;color:#888;">Loading transactions...</p>
            </div>

            <div id="txn-table-wrap">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar"></i> Date</th>
                                <th><i class="fas fa-hashtag"></i> Transaction ID</th>
                                <th><i class="fas fa-file-alt"></i> Reference</th>
                                <th><i class="fas fa-money-bill"></i> Amount</th>
                                <th><i class="fas fa-arrow-down text-success"></i> Credit</th>
                                <th><i class="fas fa-arrow-up text-danger"></i> Debit</th>
                                <th><i class="fas fa-user"></i> Payer</th>
                                <th><i class="fas fa-user"></i> Receiver</th>
                                <th><i class="fas fa-info-circle"></i> Description</th>
                            </tr>
                        </thead>
                        <tbody id="transactions-container">
                            @include('wallet.partials.transactions_list', ['transactions' => $transactions, 'user' => $user])
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-center" id="load-more-wrap">
                <button id="load-more-btn" class="load-more-btn" style="{{ $transactions->hasMorePages() ? '' : 'display:none;' }}">
                    <i class="fas fa-sync-alt"></i> Load More Transactions
                </button>
            </div>

            <div id="txn-empty" style="display:none;text-align:center;padding:40px 20px;color:#999;">
                <i class="fas fa-receipt" style="font-size:40px;margin-bottom:10px;display:block;"></i>
                <p>No transactions found.</p>
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
        function copyWalletId(id) {
            navigator.clipboard.writeText(id).then(function() {
                // Create a temporary success message
                const tempMsg = document.createElement('div');
                tempMsg.textContent = 'Wallet ID copied: ' + id;
                tempMsg.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #4caf50; color: white; padding: 15px 25px; border-radius: 10px; z-index: 9999; box-shadow: 0 5px 20px rgba(0,0,0,0.2); animation: slideIn 0.3s ease;';
                document.body.appendChild(tempMsg);
                setTimeout(() => tempMsg.remove(), 3000);
            }, function() {
                alert('Failed to copy Wallet ID');
            });
        }

        // === Transaction History Logic ===
        let currentFilter = 'all';
        let currentCurrency = 'all';
        let currentSearch = '';
        let nextPage = 2;
        let isLoading = false;
        let searchTimeout = null;

        const txnContainer = document.getElementById('transactions-container');
        const txnLoading = document.getElementById('txn-loading');
        const txnTableWrap = document.getElementById('txn-table-wrap');
        const txnEmpty = document.getElementById('txn-empty');
        const loadMoreBtn = document.getElementById('load-more-btn');

        function loadTransactions(page, append) {
            if (isLoading) return;
            isLoading = true;

            if (!append) {
                txnLoading.style.display = 'block';
                txnTableWrap.style.opacity = '0.4';
                txnEmpty.style.display = 'none';
            }
            if (loadMoreBtn) {
                loadMoreBtn.disabled = true;
                loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            }

            const params = new URLSearchParams({
                filter: currentFilter,
                currency: currentCurrency,
                search: currentSearch,
                page: page
            });

            fetch(`{{ route('wallet.filter') }}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(res) { return res.text(); })
            .then(function(html) {
                var trimmed = html.trim();

                if (append) {
                    txnContainer.insertAdjacentHTML('beforeend', trimmed);
                } else {
                    txnContainer.innerHTML = trimmed;
                    txnLoading.style.display = 'none';
                    txnTableWrap.style.opacity = '1';
                }

                nextPage = page + 1;

                // Check if we got data
                if (!trimmed || trimmed.length < 20) {
                    loadMoreBtn.style.display = 'none';
                    if (!append && !trimmed) {
                        txnEmpty.style.display = 'block';
                    }
                } else {
                    loadMoreBtn.style.display = '';
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Load More Transactions';
                }

                isLoading = false;
            })
            .catch(function() {
                txnLoading.style.display = 'none';
                txnTableWrap.style.opacity = '1';
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Load More Transactions';
                isLoading = false;
            });
        }

        // Filter buttons (All / Received / Sent)
        document.querySelectorAll('.filter-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(function(b) { b.classList.remove('active'); });
                btn.classList.add('active');
                currentFilter = btn.getAttribute('data-filter');
                nextPage = 2;
                loadTransactions(1, false);
            });
        });

        // Currency filter
        var currencySelect = document.getElementById('currency-filter');
        if (currencySelect) {
            currencySelect.addEventListener('change', function() {
                currentCurrency = this.value;
                nextPage = 2;
                loadTransactions(1, false);
            });
        }

        // Search input with debounce
        var searchInput = document.getElementById('txn-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                var val = this.value.trim();
                searchTimeout = setTimeout(function() {
                    currentSearch = val;
                    nextPage = 2;
                    loadTransactions(1, false);
                }, 500);
            });
        }

        // Load More button
        loadMoreBtn.addEventListener('click', function() {
            loadTransactions(nextPage, true);
        });
    </script>
</body>
</html>