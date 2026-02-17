
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

    <title>Create account from referral</title>

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

@extends('layouts.app')

@section('seo_title', 'Create Account - SupperAge | Join the Community')
@section('seo_description', 'Sign up for SupperAge today. Join millions of users chatting, sharing, earning, and shopping on the platform built for you.')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center mb-4">Create an Account</h3>
    <h6>Thanks for accepting the invitation from your own person</h6>
    <small class="muted mb-4">Welcom to Jetmav-Reward-System, where students and gradguate are allowed to earn just to support thier academics and the side-hustle,register and earn also as new user </small>
<hr />
    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Registration form --}}
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
    <label for="name" class="form-label">Full Name</label>
    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
</div>


        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input id="username" type="text" 
                   class="form-control" 
                   name="username" value="{{ old('username') }}" required autofocus>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email" 
                   class="form-control" 
                   name="email" value="{{ old('email') }}" required>
        </div>
          
<div class="mb-3">
    <label for="phone" class="form-label">Phone</label>
    <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
</div>

<div class="mb-3">
    <label for="gender" class="form-label">Gender</label>
    <select id="gender" name="gender" class="form-control" required>
        <option value="">-- Select Gender --</option>
        <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
        <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
    </select>
</div>

<div class="mb-3">
    <label for="dob" class="form-label">Date of Birth</label>
    <input id="dob" type="date" class="form-control" name="dob" value="{{ old('dob') }}" required>
</div>

<div class="mb-3">
    <label for="continent" class="form-label">Continent</label>
    <input id="continent" type="text" class="form-control" name="continent" value="{{ old('continent') }}" required>
</div>

<div class="mb-3">
    <label for="country" class="form-label">Country</label>
    <input id="country" type="text" class="form-control" name="country" value="{{ old('country') }}" required>
</div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" 
                   class="form-control" 
                   name="password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" 
                   class="form-control" 
                   name="password_confirmation" required>
        </div>

        {{-- If user came via referral link, preserve it --}}
        @if(request()->has('ref'))
            <input type="hidden" name="ref" value="{{ request()->get('ref') ?? session('ref') }}">

        @endif

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Register</button>
        </div>
    </form>

    <p class="text-center mt-3">
        Already have an account? <a href="{{ url('/account') }}">Login here</a>
    </p>
</div>


<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>
@endsection
</body>
</html>