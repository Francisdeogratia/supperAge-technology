
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

    <title>Add task center</title>

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
<div class="">
    <h3 class="mb-4">🎯 Task Center</h3>
    <div class="alert alert-info">
    <strong>💰 Your Wallet Balance:</strong>
    <ul class="mb-0">
        @forelse($balances as $currency => $amount)
            <li>{{ $currency }}: {{ number_format($amount, 2) }}</li>
        @empty
            <li>No funds available.</li>
        @endforelse
    </ul>
</div>


@php
  $loginSession = $user->lastLoginSession ?? null;
  $isOnline = $loginSession && $loginSession->logout_at === null;
  $lastSeen = $loginSession && $loginSession->logout_at
      ? \Carbon\Carbon::parse($loginSession->logout_at)->diffForHumans()
      : 'Online now';
@endphp

    
    <!-- Section 1: Available Tasks -->
<!-- Section 1: Available Tasks -->
<div class="card mb-4">
    <div class="card-header bg-info text-white">Available Tasks</div>
    <div class="card-body">

        <!-- Follow Me Task -->
<h5>Follow Me Task</h5>

<div class="d-flex align-items-center mb-3">
    <img src="{{ str_replace('/upload/', '/upload/w_60,h_60,c_fill,r_max,q_70/', $user->profileimg) }}"
         alt="Profile Image"
         class="rounded-circle me-3"
         style="width: 60px; height: 60px;">
    <h6 class="mb-0 ml-2">{{ $user->name }}</h6>
    &nbsp;<small style="font-size: small;font-weight:bold;color:blue;">{{ $isOnline ? 'Online now' : 'Last seen : ' . $lastSeen }}</small>
</div>

<form method="POST" action="{{ route('task.store') }}">
    @csrf
    <input type="hidden" name="task_type" value="follow_me">
    <input type="hidden" name="task_content" value="Follow me on SupperAge">

    <div class="row">
        <div class="col-md-3">
            <label>Price per follower</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label>Currency</label>
            <select name="currency" class="form-control">
                <option value="NGN">Nigerian Naira (NGN)</option>
                <option value="USD">US Dollar (USD)</option>
                <option value="GBP">British Pound Sterling (GBP)</option>
                <option value="EUR">Euro (EUR)</option>
                <option value="GHS">Ghanaian Cedi (GHS)</option>
                <option value="KES">Kenyan Shilling (KES)</option>
                <option value="TZS">Tanzanian Shilling (TZS)</option>
                <option value="UGX">Ugandan Shilling (UGX)</option>
                <option value="ZAR">South African Rand (ZAR)</option>
                <option value="XAF">Central African CFA Franc (XAF)</option>
                <option value="XOF">West African CFA Franc (XOF)</option>
                <option value="CAD">Canadian Dollar (CAD)</option>
                <option value="CLP">Chilean Peso (CLP)</option>
                <option value="COP">Colombian Peso (COP)</option>
                <option value="EGP">Egyptian Pound (EGP)</option>
                <option value="GNF">Guinean Franc (GNF)</option>
                <option value="MWK">Malawian Kwacha (MWK)</option>
                <option value="MAD">Moroccan Dirham (MAD)</option>
                <option value="RWF">Rwandan Franc (RWF)</option>
                <option value="SLL">Sierra Leonean Leone (SLL)</option>
                <option value="STD">São Tomé and Príncipe Dobra (STD)</option>
                <option value="ZMW">Zambian Kwacha (ZMW)</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Target Count</label>
            <input type="number" name="target" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label>Duration (days)</label>
            <input type="number" name="duration" class="form-control" required>
        </div>
    </div>
    <div class="mt-2">
        <label>Countries, Gender, Age</label>
        <input type="text" name="audience" class="form-control" placeholder="e.g. Nigeria, Male, 18-35">
    </div>
    <button type="submit" class="btn btn-primary mt-3">Add Follow Me Task</button>
</form>


        <hr>

        <!-- Post Promotion -->
<h5>Post Promotion</h5>
@foreach($posts as $post)
    <form method="POST" action="{{ route('task.store') }}" class="mb-3">
        @csrf
        <input type="hidden" name="task_type" value="post_promotion">
<input type="hidden" name="task_content" value="{{ $post->post_content }}">
<input type="hidden" name="post_id" value="{{ $post->id }}">


       @php
    $fileArray = json_decode($post->file_path, true);
    $fileUrl = is_array($fileArray) && count($fileArray) ? $fileArray[0] : null;
    $isVideo = $fileUrl && Str::endsWith($fileUrl, ['.mp4', '.mov', '.webm']);
@endphp

@if($fileUrl)
    <div class="mb-2">
        @if($isVideo)
            <video controls style="max-width: 100%; border-radius: 10px;">
                <source src="{{ $fileUrl }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <img src="{{ str_replace('/upload/', '/upload/w_400,h_250,c_fill,q_auto,f_auto/', $fileUrl) }}" alt="Post Image" class="img-fluid mb-2" style="max-width: 100%; border-radius: 10px;">
        @endif
    </div>
@endif

<strong>{{ $post->post_content }}</strong>
        @include('partials.task_form_fields', ['button' => 'Add Promotion Task', 'color' => 'primary'])
    </form>
    <hr>
@endforeach

<hr>

<!-- Engagement Post -->
<h5>Engagement Post</h5>
@foreach($posts as $post)
    <form method="POST" action="{{ route('task.store') }}" class="mb-3">
        @csrf
        <input type="hidden" name="task_type" value="engagement_post">
        <input type="hidden" name="task_content" value="{{ $post->post_content }}">
        <input type="hidden" name="post_id" value="{{ $post->id }}">


        @php
    $fileArray = json_decode($post->file_path, true);
    $fileUrl = is_array($fileArray) && count($fileArray) ? $fileArray[0] : null;
    $isVideo = $fileUrl && Str::endsWith($fileUrl, ['.mp4', '.mov', '.webm']);
@endphp

@if($fileUrl)
    <div class="mb-2">
        @if($isVideo)
            <video controls style="max-width: 100%; border-radius: 10px;">
                <source src="{{ $fileUrl }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <img src="{{ str_replace('/upload/', '/upload/w_400,h_250,c_fill,q_auto,f_auto/', $fileUrl) }}" alt="Post Image" class="img-fluid mb-2" style="max-width: 100%; border-radius: 10px;">
        @endif
    </div>
@endif

<strong>{{ $post->post_content }}</strong>

        @include('partials.task_form_fields', ['button' => 'Add Engagement Task', 'color' => 'success'])
    </form>
    <hr>
@endforeach

<hr>

<!-- Tales Sharing -->
<h5>Tales Sharing</h5>
@foreach($tales as $tale)
    <form method="POST" action="{{ route('task.store') }}" class="mb-3">
        @csrf
        <input type="hidden" name="task_type" value="tales_sharing">
        <input type="hidden" name="task_content" value="{{ $tale->tales_content }}">

        @php
            $fileUrl = $tale->files_talesexten;
            $isVideo = Str::endsWith($fileUrl, ['.mp4', '.mov', '.webm']);
        @endphp

        <div class="mb-2">
            @if($isVideo)
                <video controls style="max-width: 100%; border-radius: 10px;">
                    <source src="{{ $fileUrl }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @else
                <img src="{{ str_replace('/upload/', '/upload/w_400,h_250,c_fill,q_auto,f_auto/', $fileUrl) }}" alt="Tale Image" class="img-fluid mb-2" style=" border-radius: 10px;">
            @endif
            <strong>{{ Str::limit($tale->tales_content, 100) }}</strong>
        </div>

        @include('partials.task_form_fields', ['button' => 'Add Tales Task', 'color' => 'warning'])
    </form>
    <hr>
@endforeach
</div>
</div>
<!-- Section 2: Add New Task -->
<div class="card">
    <div class="card-header bg-warning text-dark">Add New Task</div>
    <div class="card-body">
        <form method="POST" action="{{ route('task.store') }}">
            @csrf
            <div class="mb-3">
    <label>Task Type</label>
    <select name="task_type" class="form-control" required>
        <option value="visit_site">Visit Site</option>
        <option value="install_app">Install App</option>
        <option value="watch_video">Watch Video</option>
        <option value="learn_more">Learn More</option>
        <option value="new_post">New Post Task</option>
    </select>
</div>

            <div class="mb-3">
                <label>URL or Content</label>
                <input type="text" name="task_content" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Price per Completion</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Currency</label>
<select name="currency" class="form-control">
    <option value="NGN">Nigerian Naira (NGN)</option>
    <option value="USD">US Dollar (USD)</option>
    <option value="GBP">British Pound Sterling (GBP)</option>
    <option value="EUR">Euro (EUR)</option>
    <option value="GHS">Ghanaian Cedi (GHS)</option>
    <option value="KES">Kenyan Shilling (KES)</option>
    <option value="TZS">Tanzanian Shilling (TZS)</option>
    <option value="UGX">Ugandan Shilling (UGX)</option>
    <option value="ZAR">South African Rand (ZAR)</option>
    <option value="XAF">Central African CFA Franc (XAF)</option>
    <option value="XOF">West African CFA Franc (XOF)</option>
    <option value="CAD">Canadian Dollar (CAD)</option>
    <option value="CLP">Chilean Peso (CLP)</option>
    <option value="COP">Colombian Peso (COP)</option>
    <option value="EGP">Egyptian Pound (EGP)</option>
    <option value="GNF">Guinean Franc (GNF)</option>
    <option value="MWK">Malawian Kwacha (MWK)</option>
    <option value="MAD">Moroccan Dirham (MAD)</option>
    <option value="RWF">Rwandan Franc (RWF)</option>
    <option value="SLL">Sierra Leonean Leone (SLL)</option>
    <option value="STD">São Tomé and Príncipe Dobra (STD)</option>
    <option value="ZMW">Zambian Kwacha (ZMW)</option>
</select>

            </div>
            <div class="mb-3">
                <label>Target Audience</label>
                <input type="text" name="target" class="form-control" placeholder="e.g. Nigeria, Male, 18-35">
            </div>
            <div class="mb-3">
                <label>Duration (days)</label>
                <input type="number" name="duration" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Add Task</button>
        </form>
    </div>
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
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/js/all.min.js"></script> -->
   
    
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
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





<div id="taskSuccessPopup" style="
    position: fixed;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #007bff;
    color: white;
    padding: 20px 30px;
    border-radius: 10px;
    font-size: 18px;
    font-weight: bold;
    display: none;
    z-index: 9999;
    text-align: center;
">
    🎉 Task created and full budget deducted.
</div>
@if(Session::has('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('taskSuccessPopup');
    popup.innerText = @json(Session::get('success'));
    popup.style.display = 'block';

    const audio = new Audio("{{ asset('sounds/mixkit-fantasy-game-success-notification-270.wav') }}");
    audio.play();

    setTimeout(() => {
        popup.style.display = 'none';
    }, 4000);
});
</script>
@endif




</body>
</html>