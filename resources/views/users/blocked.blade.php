

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

    <title>Blocked people</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

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


<style>

</style>
    
</head>
<body>

 @include('layouts.navbar')

<div class="container" style="margin-top:30px; max-width:700px;">
    <div class="d-flex align-items-center mb-3">
        <a href="javascript:history.back()" class="btn btn-sm btn-light mr-3">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h4 class="mb-0 text-danger">
            🚫 Blocked Users
            <span class="badge badge-danger ml-2">{{ $blockedPeople->count() }}</span>
        </h4>
    </div>
    <p class="text-muted">These users cannot send you messages or view your activity.</p>

    @if($blockedPeople->isEmpty())
        <div class="alert alert-info">You haven't blocked any users yet.</div>
    @else
        <div class="list-group" id="blocked-list">
            @foreach($blockedPeople as $person)
                <div class="list-group-item d-flex justify-content-between align-items-center" id="blocked-row-{{ $person->id }}">
                    <div class="d-flex align-items-center">
                        <img src="{{ $person->profileimg ? (filter_var($person->profileimg, FILTER_VALIDATE_URL) ? $person->profileimg : asset($person->profileimg)) : asset('images/best3.png') }}"
                             class="rounded-circle mr-3"
                             style="width:46px;height:46px;object-fit:cover;"
                             onerror="this.src='{{ asset('images/best3.png') }}'">
                        <div>
                            <h6 class="mb-0">{{ $person->name }}</h6>
                            <small class="text-muted">@{{ $person->username }}</small>
                            <small class="d-block text-secondary">
                                Blocked since: {{ \Carbon\Carbon::parse($person->blocked_at)->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary"
                            onclick="unblockUser({{ $person->id }}, this)">
                        Unblock
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function unblockUser(blockedId, btn) {
        if (!confirm('Unblock this user?')) return;
        btn.disabled = true;
        btn.textContent = 'Unblocking…';
        fetch('/users/unblock', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ blocked_user_id: blockedId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById('blocked-row-' + blockedId);
                if (row) row.remove();
                // Update count badge
                const remaining = document.querySelectorAll('[id^="blocked-row-"]').length;
                const badge = document.querySelector('.badge-danger');
                if (badge) badge.textContent = remaining;
                if (remaining === 0) {
                    document.getElementById('blocked-list').innerHTML =
                        '<div class="alert alert-info">You haven\'t blocked any users yet.</div>';
                }
            } else {
                btn.disabled = false;
                btn.textContent = 'Unblock';
                alert('Failed to unblock. Please try again.');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = 'Unblock';
            alert('Network error. Please try again.');
        });
    }
</script>

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
   <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

    
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script>

</body>
</html>