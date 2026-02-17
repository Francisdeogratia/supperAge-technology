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

    <title>Wallet - SupperAge</title>

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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
    <link rel="stylesheet" href="{{ asset('css/wallet.css') }}"> <!-- NEW: Add the wallet styles -->
</head>

<body class="wallet-page">
    @include('layouts.navbar')

    @extends('layouts.app')

    @section('content')
    <div class="container wallet-container">

        {{-- No badge at all --}}
        @if(!$user->badge_status)
            <div class="verification-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Account Verification Required</h3>
                <p>You need to verify your account before withdrawing.</p>
                <form action="{{ route('badge.verify.wallet') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-info mt-2">
                        <i class="fas fa-check-circle"></i> Apply Now – NGN 4000 (From Wallet)
                    </button>
                </form>
            </div>

        {{-- Badge exists but expired --}}
        @elseif($user->badge_expired)
            <div class="verification-card">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Badge Expired</h3>
                <p>Your verification badge has expired.</p>
                <form action="{{ route('badge.verify.wallet') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning mt-2">
                        <i class="fas fa-redo"></i> Renew Badge – NGN 4000 (From Wallet)
                    </button>
                </form>
            </div>

        {{-- Badge exists and is valid → show withdraw form --}}
        @else
            <div class="withdraw-card">
                <h3>Withdraw to Bank Account</h3>

                {{-- Profile header --}}
                <div class="profile-section">
                    <img src="{{ $user->profileimg ?? asset('images/best2.png') }}"
                         alt="Profile"
                         class="rounded-circle">
                    <p>
                        <strong>{{ $user->name }}</strong>
                        <span class="badge-icon">
                            <img src="{{ asset($user->badge_status) }}"
                                 alt="Verified"
                                 title="Verified User">
                        </span>
                    </p>
                </div>

                {{-- Show all balances --}}
                <div class="text-center balance-header">
                    <strong>Wallet Balances</strong>
                </div>

                <ul class="balance-grid">
                    @forelse($balances as $code => $amount)
                        <li class="balance-item">
                            <b>{{ number_format($amount, 2) }}</b>
                            <span class="currency-label">{{ $currencies[$code] ?? $code }} ({{ $code }})</span>
                        </li>
                    @empty
                        <li class="balance-item">
                            <span class="currency-label">No funds available</span>
                        </li>
                    @endforelse
                </ul>

                {{-- Net available balance (updates dynamically) --}}
                <div id="netAvailableBalance" class="net-available">
                    <i class="fas fa-info-circle"></i> Net Available (60% rule): –
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> {{ implode(', ', $errors->all()) }}
                    </div>
                @endif

                <form action="{{ route('wallet.withdraw.process') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label><i class="fas fa-university"></i> Bank</label>
                        <select name="bank_code" id="bankSelect" class="form-control" required>
                            <option value="">-- Select Bank --</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank['code'] }}">{{ $bank['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-globe-africa"></i> Country</label>
                        <select name="country" id="countrySelect" class="form-control">
                            @foreach($countries as $code => $name)
                                <option value="{{ $code }}" {{ $selectedCountry == $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-coins"></i> Currency</label>
                        <select name="currency" id="currencySelect" class="form-control" required>
                            @foreach($balances as $code => $amount)
                                <option value="{{ $code }}">{{ $currencies[$code] ?? $code }} ({{ $code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> Account Number</label>
                        <input type="text" name="account_number" class="form-control" placeholder="Enter account number" required>
                    </div>

                    <div class="form-group mb-3">
                        <label><i class="fas fa-money-bill-wave"></i> Amount</label>
                        <div class="input-group">
                            <span class="input-group-text" id="amountSymbol">₦</span>
                            <input type="number" name="amount" class="form-control" min="1" placeholder="Enter amount" required>
                        </div>
                        <div class="progress mt-2">
                            <div id="amountProgress" class="progress-bar" role="progressbar"
                                 style="width: 0%; background-color: green;"></div>
                        </div>
                    </div>

                    <button type="submit" id="withdrawBtn" class="btn btn-block btn-primary" disabled>
                        <i class="fas fa-paper-plane"></i> Withdraw
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Load jQuery first -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    
    <script src="{{ asset('myjs/bar.js') }}"></script>
    <script src="{{ asset('myjs/more_lesstext.js') }}"></script>
    <script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script>

    <script>
    $(document).ready(function() {
        // Initialize Select2
        $('#bankSelect').select2({
            placeholder: "Search for a bank",
            allowClear: true
        });

        // Data from Laravel
        const balances = @json($balances);
        const exchangeRates = @json($exchangeRates);

        const symbols = {
            'NGN':'₦','USD':'$','EUR':'€','GBP':'£','GHS':'₵','KES':'KSh','ZAR':'R',
            'UGX':'USh','TZS':'TSh','XAF':'FCFA','XOF':'CFA','ZMW':'ZK','MWK':'MK','SLL':'Le'
        };

        function getSymbol(code){ return symbols[code] || code; }

        function formatCurrency(amount, code){
            const zeroDecimals = ['UGX','TZS','XAF','XOF','SLL','MWK'];
            let opts;
            if(zeroDecimals.includes(code)){
                opts = {minimumFractionDigits:0, maximumFractionDigits:0};
            } else {
                opts = {minimumFractionDigits:2, maximumFractionDigits:2};
            }
            return amount.toLocaleString(undefined, opts);
        }

        function updateProgressBar(){
            const code = $('#currencySelect').val();
            const balance = balances[code] || 0;
            const net = balance * 0.60;
            const input = parseFloat($('input[name="amount"]').val());

            const bar = $('#amountProgress');
            if(isNaN(input) || input <= 0){
                bar.css('width', '0%').css('background-color', 'green');
                return;
            }

            let percent = (input / net) * 100;
            if(percent > 100) percent = 100;

            bar.css('width', percent + '%');

            if(input <= net){
                bar.css('background-color', 'green');
            } else {
                bar.css('background-color', 'red');
            }
        }

        function updateNetAvailable(){
            const code = $('#currencySelect').val();
            const balance = balances[code] || 0;
            const net = balance * 0.60;
            $('#netAvailableBalance').html(
                `<i class="fas fa-info-circle"></i> Net Available (60% rule): ${getSymbol(code)} ${formatCurrency(net, code)}`
            );
        }

        function validateWithdrawAmount(){
            const code = $('#currencySelect').val();
            const balance = balances[code] || 0;
            const net = balance * 0.60;
            const input = parseFloat($('input[name="amount"]').val());
            $('#amountWarning').remove();
            if(!isNaN(input) && input>0 && input<=net){
                $('#withdrawBtn').prop('disabled', false);
            } else {
                $('#withdrawBtn').prop('disabled', true);
                if(!isNaN(input) && input>net){
                    $('input[name="amount"]').after(
                        `<div id="amountWarning" class="text-danger mt-1">
                            <i class="fas fa-exclamation-triangle"></i> Amount exceeds your Net Available balance (${getSymbol(code)} ${formatCurrency(net, code)})
                        </div>`
                    );
                }
            }
        }

        function updateBreakdown(){
            const code = $('#currencySelect').val();
            const input = parseFloat($('input[name="amount"]').val());
            $('#withdrawBreakdown').remove();
            if(!isNaN(input) && input > 0){
                const receive = input*0.60, trash = input*0.40;
                $('input[name="amount"]').after(
                    `<div id="withdrawBreakdown" class="form-text mt-1 p-2">
                        <strong>You entered:</strong> ${getSymbol(code)} ${formatCurrency(input, code)} <br>
                        <strong>You will receive (60%):</strong> ${getSymbol(code)} ${formatCurrency(receive, code)} <br>
                        <strong>System charge (40%):</strong> ${getSymbol(code)} ${formatCurrency(trash, code)}
                    </div>`
                );
            }
        }

        function updateNgnEquivalent(){
            const code = $('#currencySelect').val();
            const input = parseFloat($('input[name="amount"]').val());
            $('#ngnEquivalent').remove();
            $('#remainingBalance').remove();
            if(!isNaN(input) && input > 0 && code && typeof exchangeRates[code] !== 'undefined'){
                const rate = exchangeRates[code];
                const ngnValue = input / rate;
                const ngnReceive = (input*0.60) / rate;

                const ngnBalance = balances['NGN'] || 0;
                const ngnRemaining = ngnBalance - ngnValue;
                const balanceInCurrency = balances[code] || 0;
                const remainingCurrency = balanceInCurrency - input;

                $('input[name="amount"]').after(
                    `<small id="ngnEquivalent" class="form-text">
                        ≈ ₦${formatCurrency(ngnValue, 'NGN')} will be deducted from your wallet <br>
                        ≈ ₦${formatCurrency(ngnReceive, 'NGN')} will actually be paid out (60%)
                    </small>
                    <small id="remainingBalance" class="form-text">
                        <strong>Remaining balance after withdrawal:</strong> ${getSymbol(code)} ${formatCurrency(remainingCurrency, code)} 
                        (≈ ₦${formatCurrency(ngnRemaining, 'NGN')})
                    </small>`
                );
            }
        }

        // Event bindings
        $('#currencySelect').on('change', function(){
            $('#amountSymbol').text(getSymbol($(this).val()));
            updateNetAvailable();
            validateWithdrawAmount();
            updateBreakdown();
            updateNgnEquivalent();
            updateProgressBar();
        });

        $('input[name="amount"]').on('input', function(){
            validateWithdrawAmount();
            updateBreakdown();
            updateNgnEquivalent();
            updateProgressBar();
        });

        // Initial trigger
        if($('#currencySelect').length > 0) {
            $('#currencySelect').trigger('change');
        }
    });
    </script>

    @endsection
</body>
</html>