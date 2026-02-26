@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp


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

    <title>Invite people</title>

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

    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">

    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
    

    <!-- Scripts -->
    <style>

        </style>
    
</head>
<body>
<!-- Your notify  navbar content  -->
    @include('layouts.navbar')

@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h3>Share SupperAge</h3>
    <a href="{{ route('referral.my') }}" class="btn btn-outline-primary btn-sm mb-3">
        <i class="fas fa-users"></i> View My Referrals & Earnings
    </a>

    @php
        $taskType = request()->get('task'); // 'invite' or 'app'
    @endphp

    @if($taskType === 'app')
    <p>Share this link with 5 friends and ask them to download SupperAge from Play Store or App Store. You’ll earn <strong>1,500 NGN</strong> once 5 installs are confirmed.</p>
    <p class="text-muted">You’ve earned {{ $installCount }} out of 5 downloads so far.</p>
    <div class="progress mb-3" style="height: 25px;">
        <div class="progress-bar bg-success" role="progressbar"
             style="width: {{ min($installCount * 20, 100) }}%;"
             aria-valuenow="{{ $installCount }}"
             aria-valuemin="0" aria-valuemax="5">
            {{ $installCount }}/5
        </div>
    </div>
    {{-- Show Android and iOS links separately --}}
    <div class="mb-3">
        <strong>Android:</strong>
        <input type="text" value="{{ $inviteLink['android'] }}" readonly class="form-control mb-2">
        <a href="https://wa.me/?text={{ urlencode('Download SupperAge on Android: ' . $inviteLink['android']) }}"
           target="_blank" class="btn btn-success mb-2">
            <i class="fab fa-whatsapp"></i> Share Android Link
        </a>
        <hr />
        <strong>iOS:</strong>
        <input type="text" value="{{ $inviteLink['ios'] }}" readonly class="form-control mb-2">
        <a href="https://wa.me/?text={{ urlencode('Download SupperAge on iPhone: ' . $inviteLink['ios']) }}"
           target="_blank" class="btn btn-success">
            <i class="fab fa-whatsapp"></i> Share iOS Link
        </a>
    </div>
    <h5 class="mt-4">QR Code for Android</h5>
    <h5 class="mt-4">Scan to Download SupperAge</h5>
<div class="mb-3">
    <img src="{{ asset('images/favicon-32x32.png') }}" alt="SupperAge Logo" style="max-width: 150px;">
</div>
{!! QrCode::size(250)->generate($clickTrackLink) !!}

<hr />
<p class="text-muted mt-2">Tap or right-click to download your QR code</p>
<a href="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(250)->generate($clickTrackLink)) }}"
   download="supperage-referral.svg">
   {!! QrCode::format('svg')->size(250)->generate($clickTrackLink) !!}
</a>

<p class="text-muted mt-2">Tap or right-click to download your QR code and share QR</p>

<div class="card p-4 mt-4" style="max-width: 600px; margin: auto; align-text:center;">
    <h4 class="mb-3">🎉 Invite Friends to SupperAge</h4>
    <p>Scan the QR code or share your referral link to earn rewards!</p>

    <img src="{{ asset('images/best2.png') }}" alt="SupperAge Logo" style="max-width: 120px;" class="mb-3">

    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(250)->generate($clickTrackLink)
) }}"
         alt="SupperAge QR Code" style="max-width: 250px;">

    <p class="mt-3"><strong>Your Referral Link:</strong></p>
    <input type="text" id="2inviteLink" value="{{ $clickTrackLink }}" readonly class="form-control mb-2">
    <button onclick="copyLink()" class="btn btn-primary">Copy Link</button>
</div>




<h5 class="mt-4">Click-Through Stats</h5>
<ul class="list-group">
    @foreach($clicks as $click)
        <li class="list-group-item">
            IP: {{ $click->ip_address ?? 'Unknown' }} |
            Device: {{ Str::limit($click->user_agent, 40) }} |
            Time: {{ \Carbon\Carbon::parse($click->clicked_at)->format('M j, Y H:i') }}
        </li>
    @endforeach
</ul>

@else
    <p>Share this link with your friends to earn rewards: from NGN 500 and above when they register.</p>
    <p class="text-muted">When your friend registers using this link, you’ll earn <strong>500 NGN</strong>.</p>
    {{-- Only use inviteLink when it's a string --}}
    <div class="input-group mb-3">
        <input type="text" id="finviteLink" value="{{ $inviteLink }}" readonly class="form-control">
        <button onclick="fcopyLink()" class="btn btn-primary">Copy</button>
    </div>
    <a href="https://wa.me/?text={{ urlencode('Register on SupperAge and earn rewards! Use my link: ' . $inviteLink) }}"
       target="_blank" class="btn btn-success">
        <i class="fab fa-whatsapp"></i> Share via WhatsApp
    </a>
@endif
</div>

 @if($taskType === 'app' && $installs->count())
    <h5 class="mt-4">Your App Downloads</h5>
    <ul class="list-group">
        @foreach($installs as $install)
            <li class="list-group-item">
                Device: {{ $install->device_id ?? 'Unknown' }} |
                Platform: {{ ucfirst($install->platform ?? 'N/A') }} |
                Date: {{ \Carbon\Carbon::parse($install->created_at)->format('M j, Y') }}
            </li>
        @endforeach
    </ul>
@endif 

<script>
function fcopyLink() {
    let fcopyText = document.getElementById("finviteLink");
    fcopyText.select();
    fcopyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(fcopyText.value);
    alert("Referral link copied!");
}
</script>

<div id="copySuccess" class="alert alert-success d-none mt-2">Link copied!</div>

<script>
function copyLink() {
    let copyText = document.getElementById("2inviteLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
    navigator.clipboard.writeText(copyText.value)
        .then(() => alert("Referral link copied!"))
        .catch(() => alert("Failed to copy link."));
}
</script>


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
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>


<script>
// $.ajaxSetup({
//   headers: {
//     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//   }
// });

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

</script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>




