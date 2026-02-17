<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="author" content="SupperAge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SupperAge - Chat, Share, Earn & Shop | Social-Financial Platform</title>
  <meta name="description" content="SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money. A complete ecosystem combining social networking with financial services and e-commerce.">
  <meta name="keywords" content="SupperAge, social financial app, earn money online, chat and earn, online marketplace, digital wallet, social networking, e-commerce platform, fund wallet, withdraw money, create store online">
  <meta name="robots" content="index, follow">
  <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
  <link rel="canonical" href="{{ url('/') }}">

  {{-- Open Graph --}}
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="SupperAge">
  <meta property="og:title" content="SupperAge - Chat, Share, Earn & Shop | Social-Financial Platform">
  <meta property="og:description" content="SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money.">
  <meta property="og:url" content="{{ url('/') }}">
  <meta property="og:image" content="{{ asset('images/apple-touch-icon.png') }}">
  <meta property="og:locale" content="en_US">

  {{-- Twitter Card --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="SupperAge - Chat, Share, Earn & Shop | Social-Financial Platform">
  <meta name="twitter:description" content="Chat, share, earn, shop, create stores, fund wallets, and withdraw money. All in one app.">
  <meta name="twitter:image" content="{{ asset('images/apple-touch-icon.png') }}">

  {{-- Favicon --}}
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <style>
    body {
      background-color: #0f172a; /* Rich dark theme for depth */
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: white;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      margin: 0;
    }

    /* Modern Logo Container */
    .cen {
      margin: 20px auto;
      border-radius: 50%;
      width: 110px;
      height: 110px;
      background: white;
      padding: 5px;
      box-shadow: 0 0 30px rgba(0, 191, 255, 0.5); /* Glow around the logo */
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .fd-logo {
      border-radius: 50%;
      max-width: 100%;
      height: auto;
      object-fit: contain;
    }

    /* THE ATTRACTIVE BRAND TEXT - RESPONSIVE FIX */
    .supper-age-brand {
      /* Use clamp to make it scale between 40px (mobile) and 80px (desktop) */
      font-size: clamp(40px, 12vw, 80px); 
      font-weight: 800;
      color: #ffffff; 
      letter-spacing: -2px;
      margin-top: 10px;
      margin-bottom: 5px;
      text-transform: capitalize;
      word-wrap: break-word; /* Prevents overflow */
      
      /* Multi-layered White & Skyblue Glow */
      text-shadow: 
        0 0 10px rgba(255, 255, 255, 0.9), 
        0 0 20px rgba(0, 191, 255, 0.7), 
        0 0 40px rgba(0, 191, 255, 0.4);
      
      animation: float 3s ease-in-out infinite;
    }

    /* Extra safety for very small screens */
    @media (max-width: 480px) {
      .supper-age-brand {
        letter-spacing: -1px; /* Tighter letters on small phones */
      }
      .reward-system {
        padding: 20px; /* Reduce padding so text has more room */
        margin: 15px 10px;
      }
    
      
      /* Gentle floating animation */
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    /* Glassmorphism Reward Box */
    .reward-system {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(15px);
      border-radius: 25px;
      padding: 30px;
      margin: 25px auto;
      max-width: 600px;
    }

    .reward-title {
      color: #FFD700; /* Vibrant Gold */
      font-size: 13px;
      letter-spacing: 3px;
      font-weight: 800;
      text-transform: uppercase;
      display: block;
      margin-bottom: 15px;
    }

    .description-text {
      color: #e2e8f0;
      line-height: 1.7;
      font-size: 16px;
      margin: 0;
    }

    /* Attractive Action Button */
    .btn-create {
      background: linear-gradient(135deg, #00bfff, #007bff);
      color: white !important;
      border: none;
      padding: 16px 45px;
      border-radius: 50px;
      font-weight: 700;
      transition: all 0.4s ease;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      box-shadow: 0 10px 25px rgba(0, 191, 255, 0.4);
      text-decoration: none;
      display: inline-block;
    }

    .btn-create:hover {
      transform: scale(1.05);
      box-shadow: 0 15px 35px rgba(0, 191, 255, 0.6);
    }

    hr.brand-divider {
      border: 0;
      height: 1px;
      background: linear-gradient(to right, transparent, rgba(0, 191, 255, 0.5), transparent);
      margin: 50px 0;
    }




    
    

  /* The Sparkling Star */
  .supper-age-brand::before {
    /* Sparkle character */
    content: url("{{ asset('images/favicon-16x16.png') }}");
    color: #00bfff;
    /* margin-right: 1px; */
    font-size: 5px;
    /* Sparkle glow */
    text-shadow: 0 0 8px #fff;
    /* Subtle twinkle animation */
    animation: twinkle 1.5s infinite alternate;
  }

  @keyframes twinkle {
    from { opacity: 0.5; transform: scale(0.8); }
    to { opacity: 1; transform: scale(1.1); }
  }
  </style>
</head>

<body>

<div class="container text-center">
  <div class="row">
    <div class="col-lg-8 offset-lg-2">
      
      <div class="cen">
        <img src="{{ asset('images/best2.png') }}" class="fd-logo" alt="SupperAge Logo">
      </div>

      <h1 class="supper-age-brand">SupperAge</h1>

      <div class="reward-system">
        <span class="reward-title">Welcome to Jetmav Reward System</span>
        <p class="description-text">
          SupperAge is a social and financial platform where you can <strong>Earn</strong>, connect with friends, 
          share updates, go Live, and send or receive money directly into your wallet—no matter where you are in the world.
        </p>
      </div>

      <div class="mt-4">
        <a href="{{ url('account') }}" class="btn-create">
          <i class="fa fa-spinner fa-spin me-2"></i> Create Account
        </a>
      </div>

    </div>
  </div>

  <hr class="brand-divider">

  <div class="pb-5">
    <p class="text-muted mb-1" style="font-size: 13px; letter-spacing: 1px;">Powered and sponsored by</p>
    <strong style="color: #00bfff; font-size: 15px;">SupperAge Technologists</strong><br>
    <small style="color: #64748b; font-weight: 600;">info@supperage.com</small>
    
    <div class="mt-4" style="font-size: 12px; opacity: 0.5;">
      &copy; 2025-{{ date('Y') }} SupperAge. All Rights Reserved.
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>