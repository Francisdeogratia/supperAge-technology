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

    <title>complete post engagement task </title>

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
<!-- Your msg  navbar content  -->

 @include('layouts.navbar')


@extends('layouts.app')

@section('content')
<div class="container">
  <h4>Engage with Post</h4>
  <p>{{ $post->post_content }}</p>

  @php
    $fileArray = json_decode($post->file_path ?? '[]', true);
    $fileUrl = is_array($fileArray) && count($fileArray) ? $fileArray[0] : null;
    $isVideo = $fileUrl && \Illuminate\Support\Str::endsWith($fileUrl, ['.mp4', '.mov', '.webm']);
  @endphp

  @if($fileUrl)
    <div class="mb-3">
      @if($isVideo)
        <video controls style="max-width: 100%; border-radius: 10px;">
          <source src="{{ $fileUrl }}" type="video/mp4">
        </video>
      @else
        <img src="{{ str_replace('/upload/', '/upload/w_400,h_250,c_fill,q_auto,f_auto/', $fileUrl) }}"
             class="img-fluid rounded">
      @endif
    </div>
  @endif

  <!-- <form method="POST" action="{{ route('tasks.engage.complete', $task->id) }}">
    @csrf
    <button type="submit" class="btn btn-success">✅ I’ve Liked, Commented & Shared</button>
  </form> -->

 <form method="POST" action="{{ route('engagement.track', $task->id) }}">
  @csrf
  <input type="hidden" name="post_id" value="{{ $post->id }}">

  <div class="mb-3">
    <label><input type="checkbox" name="liked" value="1"> 👍 I liked this post</label><br>
    <label><input type="checkbox" name="commented" value="1"> 💬 I commented</label><br>
    <label><input type="checkbox" name="shared" value="1"> 🔗 I shared it</label>
    <div class="mb-3">
  <label for="shared_with">👥 Select people to share with:</label>
  <select name="shared_with[]" id="shared_with" class="form-control" multiple>
    @foreach(\App\Models\UserRecord::where('id', '!=', $user->id)->get() as $shareUser)
      <option value="{{ $shareUser->id }}">{{ $shareUser->username }}</option>
    @endforeach
  </select>
  <small class="text-muted">Hold Ctrl (or Cmd) to select multiple people.</small>
</div>
 
  </div>

  <div class="mb-3">
    <textarea name="comment_text" class="form-control" placeholder="Optional: leave a comment"></textarea>
  </div>

  <button type="submit" class="btn btn-success">✅ Submit Engagement</button>
</form>

<div class="mt-4">
  <h6>👍 Liked by:</h6>
  <ul>
    @foreach(DB::table('engage_likes')->where('post_id', $post->id)->get() as $like)
      @php $liker = \App\Models\UserRecord::find($like->user_id); @endphp
      <li>{{ $liker->username }}</li>
    @endforeach
  </ul>

  <h6>💬 Commented by:</h6>
  <ul>
    @foreach(DB::table('engage_comments')->where('post_id', $post->id)->get() as $comment)
      @php $commenter = \App\Models\UserRecord::find($comment->user_id); @endphp
      <li><strong>{{ $commenter->username }}:</strong> {{ $comment->content }}</li>
    @endforeach
  </ul>

  <h6>🔗 Shared by:</h6>
<ul>
  @foreach(DB::table('post_engagements')->where('task_id', $task->id)->where('shared', true)->get() as $engage)
    @php $sharer = \App\Models\UserRecord::find($engage->user_id); @endphp
    <li>
      {{ $sharer->username }} shared
      @if(property_exists($engage, 'shared_to') && $engage->shared_to)
        @php $sharedTo = \App\Models\UserRecord::find($engage->shared_to); @endphp
        with {{ $sharedTo ? $sharedTo->username : 'unknown' }}
      @else
        with unknown recipient
      @endif
    </li>
  @endforeach
</ul>

</div>


</div>

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
   

<script src="{{ asset('myjs/allpost.js') }}"></script>

<script src="{{ asset('myjs/tales.js') }}"></script>

<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>

<script src="{{ asset('myjs/searchuser.js') }}"></script>
@endsection





</body>
</html>