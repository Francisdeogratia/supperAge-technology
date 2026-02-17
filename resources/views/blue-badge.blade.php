
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

    <title>Blue Badge Verification</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

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
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
    

    <!-- Scripts -->
    <style>

        </style>
    
</head>
<body>
 <!-- Your msg  navbar content  -->

  @include('layouts.navbar')

@extends('layouts.app')

@section('seo_title', 'Blue Badge Verification - SupperAge')
@section('seo_description', 'Get verified on SupperAge with the blue badge. Build trust and credibility with your audience.')

@section('content')
<style>
    .badge-container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
        font-family: sans-serif;
    }
    h1 {
        text-align: center;
        font-size: 1.8em;
        margin-bottom: 15px;
    }
    h2 {
        font-size: 1.3em;
        margin-top: 25px;
        color: #ff9800;
    }
    ul {
        padding-left: 18px;
    }
    .pay-btn {
        display: block;
        background: linear-gradient(90deg, #ff9800, #ffca28);
        color: #fff;
        text-align: center;
        padding: 12px;
        border-radius: 8px;
        font-weight: bold;
        margin-top: 25px;
        text-decoration: none;
    }
    .pay-btn:hover {
        transform: scale(1.05);
    }
    @media(max-width:600px){
        .badge-container { padding: 15px; }
        h1 { font-size: 1.5em; }
    }
</style>

<div class="badge-container">
    <h1>✅ Blue Badge Verification</h1>
     @auth
    <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-bell crc"></i>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
        @endif
    </a>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="width:300px; max-height:400px; overflow-y:auto;">
        @forelse(auth()->user()->notifications->take(10) as $notification)
            <li class="dropdown-item {{ $notification->read_at ? '' : 'fw-bold' }}">
                {{ $notification->data['message'] ?? 'Notification' }}
                <br>
                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
            </li>
        @empty
            <li class="dropdown-item text-muted">No notifications</li>
        @endforelse
    </ul>
@endauth

    <h2>Benefits & Importance</h2>
    <ul>
        <li>Boosts your credibility and trustworthiness on the platform</li>
        <li>Signals authenticity to other users</li>
        <li>Increases profile visibility and reach</li>
        <li>Prevents impersonation or fake accounts using your identity</li>
    </ul>

    <h2>Requirements</h2>
    <ul>
        <li>Valid government-issued ID (Passport, NIN, Driver’s License, etc.)</li>
        <li>Active profile with accurate personal information</li>
        <li>Recent clear profile picture</li>
        <li>No violations of community guidelines in the last 90 days</li>
    </ul>

    <h2>Cost & Renewal</h2>
    <p>
    The Blue Badge verification costs 
    <strong>{{ $selectedCurrency ?? 'NGN' }} {{ number_format($convertedAmount ?? 4000, 2) }}</strong> per month.  
    Renewal is automatic every 30 days upon confirmation of payment.
</p>

    
{{-- Badge logic --}}
@if(!$user || !$user->badge_expires_at)
    {{-- Never applied --}}
   <form action="{{ route('payment.start') }}" method="GET" id="badgeForm">
    <label for="currency">Choose Currency</label>
    <select name="currency" id="currencySelect" class="form-control mb-2">
        <option value="NGN" {{ $defaultCurrency == 'NGN' ? 'selected' : '' }}>NGN - Nigerian Naira</option>
        <option value="USD" {{ $defaultCurrency == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
        <option value="EUR" {{ $defaultCurrency == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
        <option value="GBP" {{ $defaultCurrency == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
        <option value="CAD" {{ $defaultCurrency == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
        <option value="GHS" {{ $defaultCurrency == 'GHS' ? 'selected' : '' }}>GHS - Ghanaian Cedi</option>
        <option value="KES" {{ $defaultCurrency == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
        <option value="ZAR" {{ $defaultCurrency == 'ZAR' ? 'selected' : '' }}>ZAR - South African Rand</option>
        <option value="TZS" {{ $defaultCurrency == 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
        <option value="UGX" {{ $defaultCurrency == 'UGX' ? 'selected' : '' }}>UGX - Ugandan Shilling</option>
        <option value="XAF" {{ $defaultCurrency == 'XAF' ? 'selected' : '' }}>XAF - Central African CFA</option>
        <option value="XOF" {{ $defaultCurrency == 'XOF' ? 'selected' : '' }}>XOF - West African CFA</option>
        <option value="SLL" {{ $defaultCurrency == 'SLL' ? 'selected' : '' }}>SLL - Sierra Leonean Leone</option>
        <option value="ZMW" {{ $defaultCurrency == 'ZMW' ? 'selected' : '' }}>ZMW - Zambian Kwacha</option>
        <option value="MWK" {{ $defaultCurrency == 'MWK' ? 'selected' : '' }}>MWK - Malawian Kwacha</option>
        <option value="MAD" {{ $defaultCurrency == 'MAD' ? 'selected' : '' }}>MAD - Moroccan Dirham</option>
        <option value="EGP" {{ $defaultCurrency == 'EGP' ? 'selected' : '' }}>EGP - Egyptian Pound</option>
        <option value="RWF" {{ $defaultCurrency == 'RWF' ? 'selected' : '' }}>RWF - Rwandan Franc</option>
    </select>

    <p id="feePreview" class="text-info">
        Loading fee...
    </p>

    <button type="submit" class="pay-btn mb-3 ">
        Apply Now – Pay Online
    </button>
</form>




    @if($walletBalance >= 3500)
    <form action="{{ route('badge.verify.wallet') }}" method="POST" class="renew-form">
    @csrf
    <select name="currency" class="form-control mb-2">
        <option value="NGN">Pay from NGN Wallet</option>
        <option value="USD">Pay from USD Wallet</option>
        <option value="EUR">Pay from EUR Wallet</option>
        <option value="GBP">Pay from GBP Wallet</option>
        <option value="CAD">Pay from CAD Wallet</option>
        <option value="GHS">Pay from GHS Wallet</option>
        <option value="KES">Pay from KES Wallet</option>
        <option value="TZS">Pay from TZS Wallet</option>
        <option value="UGX">Pay from UGX Wallet</option>
        <option value="ZAR">Pay from ZAR Wallet</option>
        <option value="XAF">Pay from XAF Wallet</option>
        <option value="XOF">Pay from XOF Wallet</option>
        <option value="SLL">Pay from SLL Wallet</option>
        <option value="ZMW">Pay from ZMW Wallet</option>
        <option value="MWK">Pay from MWK Wallet</option>
        <option value="MAD">Pay from MAD Wallet</option>
        <option value="EGP">Pay from EGP Wallet</option>
        <option value="RWF">Pay from RWF Wallet</option>
        <option value="CLP">Pay from CLP Wallet</option>
        <option value="COP">Pay from COP Wallet</option>
        <option value="GNF">Pay from GNF Wallet</option>
        <option value="STD">Pay from STD Wallet</option>
    </select>
    <button type="submit" class="btn btn-info mt-2">
        Apply Now – Pay From Wallet
    </button>
</form>

@endif
@elseif(now()->gte($user->badge_expires_at))
    {{-- Expired --}}
    <form action="{{ route('payment.start') }}" method="GET" class="renew-form">
        <select name="currency" class="form-control mb-2">
            <option value="NGN">NGN - Nigerian Naira</option>
            <option value="USD">USD - US Dollar</option>
            <option value="EUR">EUR - Euro</option>
            <option value="GBP">GBP - British Pound</option>
            <option value="CAD">CAD - Canadian Dollar</option>
            <option value="GHS">GHS - Ghanaian Cedi</option>
            <option value="KES">KES - Kenyan Shilling</option>
            <option value="ZAR">ZAR - South African Rand</option>
            <option value="TZS">TZS - Tanzanian Shilling</option>
            <option value="UGX">UGX - Ugandan Shilling</option>
            <option value="XAF">XAF - Central African CFA</option>
            <option value="XOF">XOF - West African CFA</option>
            <option value="SLL">SLL - Sierra Leonean Leone</option>
            <option value="ZMW">ZMW - Zambian Kwacha</option>
            <option value="MWK">MWK - Malawian Kwacha</option>
            <option value="MAD">MAD - Moroccan Dirham</option>
            <option value="EGP">EGP - Egyptian Pound</option>
            <option value="RWF">RWF - Rwandan Franc</option>
        </select>
        <p id="renewFeePreview" class="text-info">Loading fee...</p>
    <button type="submit" class="btn btn-warning">
        Renew Badge – Pay Online
    </button>
    </form>

    @if($walletBalance >= 3500)
        <form action="{{ route('badge.verify.wallet') }}" method="POST" style="display:inline;">
    @csrf
    <select name="currency" class="form-control mb-2">
        <option value="NGN">Pay from NGN Wallet</option>
        <option value="USD">Pay from USD Wallet</option>
        <option value="EUR">Pay from EUR Wallet</option>
        <option value="GBP">Pay from GBP Wallet</option>
        <option value="CAD">Pay from CAD Wallet</option>
        <option value="GHS">Pay from GHS Wallet</option>
        <option value="KES">Pay from KES Wallet</option>
        <option value="TZS">Pay from TZS Wallet</option>
        <option value="UGX">Pay from UGX Wallet</option>
        <option value="ZAR">Pay from ZAR Wallet</option>
        <option value="XAF">Pay from XAF Wallet</option>
        <option value="XOF">Pay from XOF Wallet</option>
        <option value="SLL">Pay from SLL Wallet</option>
        <option value="ZMW">Pay from ZMW Wallet</option>
        <option value="MWK">Pay from MWK Wallet</option>
        <option value="MAD">Pay from MAD Wallet</option>
        <option value="EGP">Pay from EGP Wallet</option>
        <option value="RWF">Pay from RWF Wallet</option>
        <option value="CLP">Pay from CLP Wallet</option>
        <option value="COP">Pay from COP Wallet</option>
        <option value="GNF">Pay from GNF Wallet</option>
        <option value="STD">Pay from STD Wallet</option>
    </select>
    <button type="submit" class="btn btn-info mt-2">
        Renew Badge – Pay From Wallet
    </button>
</form>

    @endif

@else
    {{-- Active --}}
    <img src="{{ asset($user->badge_status) }}" alt="Verified Badge">
    <p style="text-align:center;color:green;font-size:50px;">{{ $user->status_one }}</p>
@endif


</div>
@endsection

<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Then other jQuery-based scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->
<!-- <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.1/dist/emoji-button.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/js/all.min.js"></script> -->
   
    
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
document.getElementById('notificationsDropdown').addEventListener('click', function() {
    fetch("{{ route('notifications.read') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
});
</script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function updateFeePreview(currency) {
        fetch(`/api/get-rate?currency=${currency}`)
            .then(res => res.json())
            .then(data => {
                const feeEl = document.getElementById('feePreview');
                if (!feeEl) return;
                feeEl.innerText = data.success
                    ? `≈ ${currency} ${data.convertedAmount}`
                    : 'Unable to fetch rate, defaulting to NGN 1500';
            })
            .catch(() => {
                const feeEl = document.getElementById('feePreview');
                if (feeEl) feeEl.innerText = 'Error fetching rate';
            });
    }

    const selectEl = document.getElementById('currencySelect');
    if (selectEl) {
        selectEl.addEventListener('change', function() {
            updateFeePreview(this.value);
        });
        updateFeePreview(selectEl.value);
    }

    // --- Renew form ---
    function updateRenewFee(currency) {
        fetch(`/api/get-rate?currency=${currency}`)
            .then(res => res.json())
            .then(data => {
                const renewEl = document.getElementById('renewFeePreview');
                if (!renewEl) return;
                renewEl.innerText = data.success
                    ? `≈ ${currency} ${data.convertedAmount}`
                    : 'Unable to fetch rate, defaulting to NGN 1500';
            })
            .catch(() => {
                const renewEl = document.getElementById('renewFeePreview');
                if (renewEl) renewEl.innerText = 'Error fetching rate';
            });
    }

    const renewSelect = document.querySelector('form.renew-form select[name="currency"]');
    if (renewSelect) {
        renewSelect.addEventListener('change', function() {
            updateRenewFee(this.value);
        });
        updateRenewFee(renewSelect.value);
    }
});

</script>



</body>

</html>