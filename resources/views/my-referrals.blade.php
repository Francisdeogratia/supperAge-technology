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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Referrals – SupperAge</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

    <style>
        .referral-page { max-width: 800px; margin: 30px auto; padding: 0 16px 60px; font-family: 'Segoe UI', sans-serif; }
        .referral-summary { display: flex; gap: 16px; margin-bottom: 28px; flex-wrap: wrap; }
        .summary-card {
            flex: 1; min-width: 140px;
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .summary-card .value { font-size: 2em; font-weight: 700; color: #1877f2; }
        .summary-card .label { font-size: 0.85em; color: #65676b; margin-top: 4px; }
        .invite-link-box {
            background: #f0f2f5;
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }
        .invite-link-box input {
            flex: 1; border: none; background: transparent;
            font-size: 0.9em; color: #333; outline: none;
            min-width: 0;
        }
        .invite-link-box button {
            background: #1877f2; color: #fff; border: none;
            border-radius: 8px; padding: 8px 16px; font-size: 0.85em;
            cursor: pointer; white-space: nowrap;
        }
        .invite-link-box button:hover { background: #1558b0; }
        .section-title { font-size: 1.1em; font-weight: 700; color: #050505; margin-bottom: 14px; }
        .referral-list { background: #fff; border-radius: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden; }
        .referral-item {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 18px;
            border-bottom: 1px solid #f0f2f5;
            transition: background 0.15s;
        }
        .referral-item:last-child { border-bottom: none; }
        .referral-item:hover { background: #f7f8fa; }
        .referral-avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
        .referral-avatar-placeholder { width: 44px; height: 44px; border-radius: 50%; background: #e4e6eb; display: flex; align-items: center; justify-content: center; color: #65676b; font-size: 1.2em; flex-shrink: 0; }
        .referral-info { flex: 1; min-width: 0; }
        .referral-name { font-weight: 600; color: #050505; font-size: 0.95em; }
        .referral-username { color: #65676b; font-size: 0.82em; }
        .referral-date { color: #8a8d91; font-size: 0.8em; margin-top: 2px; }
        .referral-earning { font-weight: 700; color: #27ae60; font-size: 0.95em; white-space: nowrap; }
        .empty-state { text-align: center; padding: 50px 20px; color: #65676b; }
        .empty-state i { font-size: 3em; margin-bottom: 14px; color: #bcc0c4; }
        .empty-state p { font-size: 1em; margin-bottom: 16px; }
        .page-back { display: inline-flex; align-items: center; gap: 8px; color: #1877f2; font-size: 0.9em; font-weight: 600; text-decoration: none; margin-bottom: 20px; }
        .page-back:hover { text-decoration: underline; color: #1558b0; }

        body.dark-mode .referral-page { color: #e4e6eb; }
        body.dark-mode .summary-card { background: #242526; box-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        body.dark-mode .summary-card .label { color: #b0b3b8; }
        body.dark-mode .invite-link-box { background: #3a3b3c; }
        body.dark-mode .invite-link-box input { color: #e4e6eb; }
        body.dark-mode .referral-list { background: #242526; box-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        body.dark-mode .referral-item { border-bottom-color: #3e4042; }
        body.dark-mode .referral-item:hover { background: #3a3b3c; }
        body.dark-mode .referral-name { color: #e4e6eb; }
        body.dark-mode .section-title { color: #e4e6eb; }

        @media (max-width: 576px) {
            .referral-summary { gap: 10px; }
            .summary-card { padding: 14px 10px; }
            .summary-card .value { font-size: 1.6em; }
        }
    </style>
</head>
<body>

@include('layouts.navbar')

<div class="referral-page">

    <a href="{{ route('invite') }}" class="page-back">
        <i class="fas fa-arrow-left"></i> Back to Invite
    </a>

    <h4 style="font-weight:700;margin-bottom:20px;">My Referral Stats</h4>

    {{-- Summary Cards --}}
    <div class="referral-summary">
        <div class="summary-card">
            <div class="value">{{ $totalInvited }}</div>
            <div class="label">People Invited</div>
        </div>
        <div class="summary-card">
            <div class="value">₦{{ number_format($totalEarned) }}</div>
            <div class="label">Total Earned</div>
        </div>
        <div class="summary-card">
            <div class="value">₦{{ number_format($perUserBonus) }}</div>
            <div class="label">Per Invite</div>
        </div>
    </div>

    {{-- Invite Link --}}
    <div class="invite-link-box">
        <i class="fas fa-link" style="color:#1877f2;"></i>
        <input type="text" id="myInviteLink" readonly value="{{ url('/register?ref=' . $user->id) }}">
        <button onclick="copyInviteLink()"><i class="fas fa-copy"></i> Copy Link</button>
    </div>

    {{-- Invited Users List --}}
    <div class="section-title">
        <i class="fas fa-users" style="color:#1877f2;margin-right:6px;"></i>
        People You've Invited
    </div>

    <div class="referral-list">
        @forelse($invitedUsers as $invited)
            <div class="referral-item">
                @if($invited->profileimg)
                    <img src="{{ $invited->profileimg }}" alt="{{ $invited->name }}" class="referral-avatar">
                @else
                    <div class="referral-avatar-placeholder"><i class="fa fa-user"></i></div>
                @endif

                <div class="referral-info">
                    <div class="referral-name">{{ $invited->name }}</div>
                    <div class="referral-username">@{{ $invited->username }}</div>
                    <div class="referral-date">
                        <i class="fas fa-calendar-alt" style="font-size:0.75em;"></i>
                        Joined {{ $invited->created_at ? $invited->created_at->format('M d, Y') : 'N/A' }}
                    </div>
                </div>

                <div class="referral-earning">
                    +₦{{ number_format($perUserBonus) }}
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-user-plus"></i>
                <p>You haven't invited anyone yet.</p>
                <a href="{{ route('invite') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-share-alt"></i> Share Your Invite Link
                </a>
            </div>
        @endforelse
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
function copyInviteLink() {
    var input = document.getElementById('myInviteLink');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function() {
        var btn = event.target.closest('button');
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        setTimeout(function() {
            btn.innerHTML = '<i class="fas fa-copy"></i> Copy Link';
        }, 2000);
    });
}
</script>

</body>
</html>
