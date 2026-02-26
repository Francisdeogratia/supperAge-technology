<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />

    {{-- SEO Title --}}
    <title>@yield('seo_title', 'SupperAge - Chat, Share, Earn & Shop | Social-Financial Platform')</title>

    {{-- SEO Description --}}
    <meta name="description" content="@yield('seo_description', 'SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money. A complete ecosystem combining social networking with financial services.')">

    {{-- SEO Keywords --}}
    <meta name="keywords" content="@yield('seo_keywords', 'SupperAge, social financial app, earn money online, chat and earn, online marketplace, digital wallet, social networking, e-commerce platform, fund wallet, withdraw money')">

    <meta name="author" content="SupperAge">
    <meta name="robots" content="@yield('seo_robots', 'index, follow')">
    <link rel="canonical" href="@yield('seo_canonical', url()->current())">

    {{-- Open Graph (Facebook, LinkedIn) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="SupperAge">
    <meta property="og:title" content="@yield('seo_title', 'SupperAge - Chat, Share, Earn & Shop | Social-Financial Platform')">
    <meta property="og:description" content="@yield('seo_description', 'SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money.')">
    <meta property="og:url" content="@yield('seo_canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/apple-touch-icon.png'))">
    <meta property="og:locale" content="en_US">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('seo_title', 'SupperAge - Chat, Share, Earn & Shop | Social-Financial Platform')">
    <meta name="twitter:description" content="@yield('seo_description', 'SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/apple-touch-icon.png'))">

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php $__echoLoaded = true; @endphp

    @yield('head')
</head>
<body>
    <div class="container">
        @yield('content')
   <!-- #region -->
    @if(session('success'))
    <div class="alert alert-success text-center mt-2">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger text-center">{{ session('error') }}</div>
@endif
@if(session('reward'))
    <div class="alert alert-info text-center">{{ session('reward') }}</div>
@endif


    </div>


   <!-- @if(Session::get('role') === 'admin')
    <a href="{{ route('admin.dashboard.now') }}" class="btn btn-danger">
        <i class="fas fa-shield-alt"></i> Admin Panel
    </a> -->
@endif

@include('partials.global-calls')

</body>
</html>
