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

    <title>Transfer Funds - SupperAge</title>

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
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}"> -->

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --card-hover-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            padding: 30px 0;
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: #2c3e50;
            font-weight: 700;
            font-size: 32px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .page-header .icon-bounce {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Balance Display */
        .balance-display {
            background: var(--primary-gradient);
            border-radius: 20px;
            padding: 25px;
            color: white;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .balance-display::before {
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

        .balance-display h5 {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .balance-item {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .balance-item:hover {
            background: rgba(255,255,255,0.25);
            transform: translateX(5px);
        }

        .balance-amount {
            font-size: 20px;
            font-weight: 700;
        }

        /* Global Settings Card */
        .settings-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .settings-card:hover {
            box-shadow: var(--card-hover-shadow);
            transform: translateY(-2px);
        }

        .settings-card h5 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .settings-card h5 i {
            color: #667eea;
            font-size: 24px;
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }

        /* Buttons */
        .btn-apply {
            background: var(--info-gradient);
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(79, 172, 254, 0.3);
            color: white;
        }

        .btn-send {
            background: var(--success-gradient);
            border: none;
            border-radius: 12px;
            padding: 15px 40px;
            color: white;
            font-weight: 700;
            font-size: 18px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-send:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(17, 153, 142, 0.3);
            color: white;
        }

        .btn-send i {
            margin-right: 10px;
        }

        /* Desktop Table */
        .table-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
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
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border: none;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #667eea;
            object-fit: cover;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .online-badge {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 10px;
            background: #e8f5e9;
            color: #2e7d32;
            font-weight: 600;
        }

        .offline-badge {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 10px;
            background: #fce4ec;
            color: #c2185b;
            font-weight: 600;
        }

        /* Checkbox Styling */
        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #667eea;
        }

        /* Select All Card */
        .select-all-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: var(--card-shadow);
        }

        .select-all-card label {
            color: white;
            font-weight: 600;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        /* Mobile User Cards */
        .user-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .user-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .user-card-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid #667eea;
            object-fit: cover;
        }

        .user-card-info {
            flex: 1;
        }

        .user-card-name {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 3px;
            font-size: 16px;
        }

        /* Mobile Sticky Button */
        .sticky-transfer-btn {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            background: white;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.15);
            z-index: 999;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }
            to {
                transform: translateY(0);
            }
        }

        .btn-safe {
            background: var(--success-gradient);
        }

        .btn-warning-balance {
            background: var(--danger-gradient);
        }

        .pb-mobile-safe {
            padding-bottom: 100px;
        }

        /* Responsive */
        @media (max-width: 767.98px) {
            .page-header h1 {
                font-size: 24px;
            }

            .settings-card {
                padding: 20px;
            }

            .balance-display {
                padding: 20px;
            }

            .form-control, .form-select {
                font-size: 14px;
            }
        }

        /* Loading Animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .loading {
            animation: pulse 1.5s infinite;
        }
    </style>
</head>
<body>
   

    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-exchange-alt icon-bounce"></i> Transfer from My Wallet</h1>
        </div>

        <!-- Search Users -->
        <div class="search-users-card" style="background:#fff;border-radius:16px;padding:16px 20px;box-shadow:0 4px 15px rgba(0,0,0,0.06);margin-bottom:24px;">
            <div style="position:relative;">
                <i class="fas fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#999;"></i>
                <input type="text" id="userSearch" class="form-control" placeholder="Search users by name..."
                       style="padding-left:40px;border-radius:12px;border:2px solid #e0e0e0;">
            </div>
            <div id="searchResultCount" style="font-size:12px;color:#888;margin-top:8px;display:none;"></div>
        </div>

        <!-- Balance Display -->
        <div class="balance-display">
            <h5><i class="fas fa-wallet"></i> Available Balances</h5>
            @forelse($balances as $code => $amount)
                <div class="balance-item">
                    <div>
                        <div class="balance-amount">{{ number_format($amount, 2) }}</div>
                        <div style="font-size: 12px; opacity: 0.9;">{{ $currencies[$code] ?? $code }}</div>
                    </div>
                    <i class="fas fa-coins" style="font-size: 28px; opacity: 0.5;"></i>
                </div>
            @empty
                <div class="text-center py-3">
                    <i class="fas fa-inbox" style="font-size: 40px; opacity: 0.5;"></i>
                    <p class="mt-2 mb-0">No funds available</p>
                </div>
            @endforelse
        </div>

        <!-- Global Settings -->
        <div class="settings-card">
            <h5><i class="fas fa-cog"></i> Global Transfer Settings</h5>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label"><i class="fas fa-dollar-sign"></i> Same Amount for All</label>
                    <input type="number" id="globalAmount" class="form-control" step="0.01" min="0" placeholder="Enter amount">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label"><i class="fas fa-globe"></i> Same Currency for All</label>
                    <select id="globalCurrency" class="form-control">
                        <option value="">-- Select Currency --</option>
                        @foreach($currencies as $code => $name)
                            <option value="{{ $code }}">{{ $name }} ({{ $code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4 d-flex align-items-end">
                    <button type="button" class="btn-apply" id="applyGlobal">
                        <i class="fas fa-check-circle"></i> Apply to Selected
                    </button>
                </div>
            </div>
        </div>

        <!-- Transfer Form -->
        <form action="{{ route('wallet.transfer.process') }}" method="POST" class="pb-mobile-safe">
            @csrf

            <!-- Desktop Table Layout -->
            <div id="desktopLayout" class="d-none d-md-block">
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">
                                        <input type="checkbox" id="selectAllDesktop">
                                    </th>
                                    <th><i class="fas fa-user"></i> User</th>
                                    <th><i class="fas fa-dollar-sign"></i> Amount</th>
                                    <th><i class="fas fa-money-bill"></i> Currency</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allUsers as $u)
                                @php
                                $isOnline = $u->is_online;
                                $lastSeen = $u->last_seen ?? 'Offline';
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="recipients[{{ $u->id }}][selected]" value="1">
                                    </td>
                                    <td>
                                        <div class="user-info">
                                            <img src="{{ $u->profileimg ?? asset('images/default-avatar.png') }}" 
                                                 alt="{{ $u->name }}" 
                                                 class="user-avatar">
                                            <div>
                                                <div class="user-name">
                                                    {{ $u->name }}
                                                    @if($u->badge_status)
                                                        <img src="{{ asset($u->badge_status) }}" 
                                                             alt="Verified" 
                                                             style="width:16px;height:16px;margin-left:3px;">
                                                    @endif
                                                </div>
                                                <span class="{{ $isOnline ? 'online-badge' : 'offline-badge' }}">
                                                    {{ $isOnline ? '● Online' : $lastSeen }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               name="recipients[{{ $u->id }}][amount]"
                                               class="form-control form-control-sm amount-field"
                                               step="0.01" 
                                               min="0"
                                               placeholder="0.00">
                                    </td>
                                    <td>
                                        <select name="recipients[{{ $u->id }}][currency]"
                                                class="form-control form-control-sm currency-field">
                                            @foreach($currencies as $code => $name)
                                                <option value="{{ $code }}">{{ $code }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Mobile Card Layout -->
            <div id="mobileLayout" class="d-block d-md-none">
                <!-- Select All -->
                <div class="select-all-card">
                    <label>
                        <input type="checkbox" id="selectAllMobile" style="width: 22px; height: 22px;">
                        <span><i class="fas fa-check-double"></i> Select All Users</span>
                    </label>
                </div>

                @foreach($allUsers as $u)
                @php
                $isOnline = $u->is_online;
                $lastSeen = $u->last_seen ?? 'Offline';
                @endphp
                <div class="user-card">
                    <div class="user-card-header">
                        <input type="checkbox" name="recipients[{{ $u->id }}][selected]" value="1">
                        <img src="{{ $u->profileimg ?? asset('images/default-avatar.png') }}" 
                             alt="{{ $u->name }}" 
                             class="user-card-avatar">
                        <div class="user-card-info">
                            <div class="user-card-name">
                                {{ $u->name }}
                                @if($u->badge_status)
                                    <img src="{{ asset($u->badge_status) }}" 
                                         alt="Verified" 
                                         style="width:18px;height:18px;margin-left:3px;">
                                @endif
                            </div>
                            <span class="{{ $isOnline ? 'online-badge' : 'offline-badge' }}">
                                {{ $isOnline ? '● Online' : $lastSeen }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-dollar-sign"></i> Amount</label>
                        <input type="number"
                               name="recipients[{{ $u->id }}][amount]"
                               class="form-control amount-field"
                               step="0.01" 
                               min="0"
                               placeholder="Enter amount">
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-money-bill"></i> Currency</label>
                        <select name="recipients[{{ $u->id }}][currency]"
                                class="form-control currency-field">
                            @foreach($currencies as $code => $name)
                                <option value="{{ $code }}">{{ $name }} ({{ $code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Desktop Submit Button -->
            <button type="submit" class="btn-send mt-4 d-none d-md-block">
                <i class="fas fa-paper-plane"></i> Send Transfers
            </button>

            <!-- Mobile Sticky Button -->
            <div class="d-block d-md-none">
                <div class="sticky-transfer-btn">
                    <button type="submit" class="btn-send btn-safe" id="mobileSendBtn">
                        <i class="fas fa-paper-plane"></i> Send Transfers (0 Selected)
                    </button>
                </div>
            </div>
        </form>
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

    <!-- <script src="{{ asset('myjs/allpost.js') }}"></script>
    <script src="{{ asset('myjs/bar.js') }}"></script>
    <script src="{{ asset('myjs/tales.js') }}"></script>
    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script> -->

    <!-- Desktop JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const globalAmountInput = document.getElementById('globalAmount');
        const globalCurrencySelect = document.getElementById('globalCurrency');
        const applyGlobalBtn = document.getElementById('applyGlobal');
        const selectAllDesktop = document.getElementById('selectAllDesktop');
        const form = document.querySelector('form');
        const desktopLayout = document.getElementById('desktopLayout');
        const mobileLayout = document.getElementById('mobileLayout');

        function syncLayoutEnabled() {
            const isDesktop = window.matchMedia('(min-width: 768px)').matches;
            toggleContainerInputs(desktopLayout, isDesktop);
            toggleContainerInputs(mobileLayout, !isDesktop);
        }

        function toggleContainerInputs(container, enabled) {
            if (!container) return;
            container.querySelectorAll('input[name^="recipients"], select[name^="recipients"]').forEach(el => {
                el.disabled = !enabled;
            });
        }

        function getDesktopRows() {
            return desktopLayout ? desktopLayout.querySelectorAll('tbody tr') : [];
        }

        if (selectAllDesktop) {
            selectAllDesktop.addEventListener('change', function() {
                getDesktopRows().forEach(row => {
                    const cb = row.querySelector('input[type="checkbox"][name^="recipients"]');
                    if (cb && !cb.disabled) cb.checked = this.checked;
                });
            });
        }

        if (applyGlobalBtn) {
            applyGlobalBtn.addEventListener('click', function() {
                const amount = globalAmountInput.value;
                const currency = globalCurrencySelect.value;
                getDesktopRows().forEach(row => {
                    const cb = row.querySelector('input[type="checkbox"][name^="recipients"]');
                    const amountField = row.querySelector('.amount-field');
                    const currencyField = row.querySelector('.currency-field');
                    if (cb && !cb.disabled && cb.checked) {
                        if (amount) { amountField.value = amount; amountField.readOnly = true; }
                        if (currency) { currencyField.value = currency; }
                    }
                });
            });
        }

        form.addEventListener('submit', function() {
            syncLayoutEnabled();
            getDesktopRows().forEach(row => {
                const amountField = row.querySelector('.amount-field');
                const currencyField = row.querySelector('.currency-field');
                if (amountField) amountField.readOnly = false;
                if (currencyField) currencyField.readOnly = false;
            });
        }, true);

        form.addEventListener('submit', function(e) {
            const isDesktop = window.matchMedia('(min-width: 768px)').matches;
            if (!isDesktop) return;
            let valid = true;
            getDesktopRows().forEach(row => {
                const cb = row.querySelector('input[type="checkbox"][name^="recipients"]');
                const amountField = row.querySelector('.amount-field');
                if (cb && !cb.disabled && cb.checked) {
                    const v = parseFloat(amountField.value);
                    if (!amountField.value || isNaN(v) || v <= 0) valid = false;
                }
            });
            if (!valid) {
                e.preventDefault();
                alert('Please enter an amount for all selected recipients.');
            }
        });

        syncLayoutEnabled();
        window.addEventListener('resize', syncLayoutEnabled);
    });
    </script>

    <!-- Mobile JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const balances = @json($balances);
        const mobileLayout = document.getElementById('mobileLayout');
        const desktopLayout = document.getElementById('desktopLayout');
        const selectAllMobile = document.getElementById('selectAllMobile');
        const mobileBtn = document.getElementById('mobileSendBtn');
        const form = document.querySelector('form');

        function syncLayoutEnabled() {
            const isDesktop = window.matchMedia('(min-width: 768px)').matches;
            toggleContainerInputs(desktopLayout, isDesktop);
            toggleContainerInputs(mobileLayout, !isDesktop);
        }

        function toggleContainerInputs(container, enabled) {
            if (!container) return;
            container.querySelectorAll('input[name^="recipients"], select[name^="recipients"]').forEach(el => {
                el.disabled = !enabled;
            });
        }

        function getMobileCards() {
            if (!mobileLayout) return [];
            return mobileLayout.querySelectorAll('.user-card');
        }

        const globalAmountInput = document.getElementById('globalAmount');
        const globalCurrencySelect = document.getElementById('globalCurrency');
        const applyGlobalBtn = document.getElementById('applyGlobal');

        if (selectAllMobile) {
            selectAllMobile.addEventListener('change', function() {
                getMobileCards().forEach(card => {
                    const cb = card.querySelector('input[type="checkbox"][name^="recipients"]');
                    if (cb && !cb.disabled && card.style.display !== 'none') cb.checked = this.checked;
                });
                updateMobileBtn();
            });
        }

        function updateMobileBtn() {
            if (!mobileBtn) return;
            let count = 0;
            getMobileCards().forEach(card => {
                const cb = card.querySelector('input[type="checkbox"][name^="recipients"]');
                if (cb && cb.checked && !cb.disabled) count++;
            });
            mobileBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Transfers (' + count + ' Selected)';
        }

        // Listen for checkbox changes on mobile
        if (mobileLayout) {
            mobileLayout.addEventListener('change', function(e) {
                if (e.target.type === 'checkbox') updateMobileBtn();
            });
        }

        // Apply global settings on mobile
        if (applyGlobalBtn) {
            applyGlobalBtn.addEventListener('click', function() {
                const amount = globalAmountInput ? globalAmountInput.value : '';
                const currency = globalCurrencySelect ? globalCurrencySelect.value : '';
                getMobileCards().forEach(card => {
                    const cb = card.querySelector('input[type="checkbox"][name^="recipients"]');
                    const amountField = card.querySelector('.amount-field');
                    const currencyField = card.querySelector('.currency-field');
                    if (cb && !cb.disabled && cb.checked) {
                        if (amount && amountField) amountField.value = amount;
                        if (currency && currencyField) currencyField.value = currency;
                    }
                });
            });
        }

        // Mobile form validation
        form.addEventListener('submit', function(e) {
            const isDesktop = window.matchMedia('(min-width: 768px)').matches;
            if (isDesktop) return;
            let valid = true;
            let hasSelected = false;
            getMobileCards().forEach(card => {
                const cb = card.querySelector('input[type="checkbox"][name^="recipients"]');
                const amountField = card.querySelector('.amount-field');
                if (cb && !cb.disabled && cb.checked) {
                    hasSelected = true;
                    const v = parseFloat(amountField ? amountField.value : '');
                    if (!amountField || !amountField.value || isNaN(v) || v <= 0) valid = false;
                }
            });
            if (!hasSelected) {
                e.preventDefault();
                alert('Please select at least one recipient.');
                return;
            }
            if (!valid) {
                e.preventDefault();
                alert('Please enter an amount for all selected recipients.');
            }
        });

        syncLayoutEnabled();
        window.addEventListener('resize', syncLayoutEnabled);
    });
    </script>

    <!-- Search JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('userSearch');
        const resultCount = document.getElementById('searchResultCount');
        if (!searchInput) return;

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visible = 0;
            let total = 0;

            // Filter desktop rows
            const desktopRows = document.querySelectorAll('#desktopLayout tbody tr');
            desktopRows.forEach(row => {
                total++;
                const name = row.querySelector('.user-name')?.textContent?.toLowerCase() || '';
                if (!query || name.includes(query)) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Filter mobile cards
            const mobileCards = document.querySelectorAll('#mobileLayout .user-card');
            mobileCards.forEach(card => {
                const name = card.querySelector('.user-card-name')?.textContent?.toLowerCase() || '';
                if (!query || name.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            if (query) {
                resultCount.style.display = 'block';
                resultCount.textContent = visible + ' of ' + total + ' users found';
            } else {
                resultCount.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>