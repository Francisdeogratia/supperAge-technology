
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

    <title>People You May Follow - SupperAge</title>

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

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

    <style>
    .suggestions-page {
        max-width: 680px;
        margin: 0 auto;
        padding: 20px 15px 80px;
    }

    .suggestions-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .suggestions-header i {
        font-size: 22px;
        color: #1877f2;
    }

    .suggestions-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #333;
    }

    .suggestions-header span {
        font-size: 13px;
        color: #65676b;
        margin-left: auto;
    }

    .suggestion-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: box-shadow 0.2s ease;
    }

    .suggestion-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    }

    .suggestion-avatar {
        position: relative;
        flex-shrink: 0;
    }

    .suggestion-avatar img.avatar-img {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e4e6eb;
    }

    .suggestion-avatar .avatar-placeholder {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #e4e6eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #65676b;
    }

    .suggestion-avatar .badge-icon {
        position: absolute;
        bottom: 0;
        right: -2px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #fff;
    }

    .suggestion-info {
        flex: 1;
        min-width: 0;
    }

    .suggestion-name {
        font-size: 15px;
        font-weight: 600;
        color: #050505;
        margin: 0 0 2px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .suggestion-name a {
        color: inherit;
        text-decoration: none;
    }

    .suggestion-name a:hover {
        text-decoration: underline;
    }

    .suggestion-followers {
        font-size: 13px;
        color: #65676b;
        margin: 0;
    }

    .suggestion-followers strong {
        color: #1877f2;
        font-weight: 600;
    }

    .follow-btn {
        flex-shrink: 0;
        padding: 8px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #1877f2;
        color: #fff;
    }

    .follow-btn:hover {
        background: #1565c0;
    }

    .follow-btn:active {
        transform: scale(0.96);
    }

    .follow-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .follow-btn.following {
        background: #e4e6eb;
        color: #050505;
    }

    .follow-btn.following:hover {
        background: #d8dadf;
    }

    .suggestion-empty {
        text-align: center;
        padding: 60px 20px;
        color: #65676b;
    }

    .suggestion-empty i {
        font-size: 48px;
        color: #bcc0c4;
        margin-bottom: 16px;
        display: block;
    }

    .suggestion-empty p {
        font-size: 16px;
        margin: 0;
    }

    /* Toast notification */
    .follow-toast {
        position: fixed;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        z-index: 9999;
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .follow-toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    .follow-toast.success {
        background: #00a400;
        color: #fff;
    }

    .follow-toast.error {
        background: #e41e3f;
        color: #fff;
    }

    /* Dark mode overrides */
    body.dark-mode .suggestions-header h5 {
        color: #E4E6EB;
    }

    body.dark-mode .suggestions-header span {
        color: #B0B3B8;
    }

    body.dark-mode .suggestion-card {
        background: #242526;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    body.dark-mode .suggestion-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    body.dark-mode .suggestion-avatar img.avatar-img {
        border-color: #3A3B3C;
    }

    body.dark-mode .suggestion-avatar .avatar-placeholder {
        background: #3A3B3C;
        color: #B0B3B8;
    }

    body.dark-mode .suggestion-avatar .badge-icon {
        border-color: #242526;
    }

    body.dark-mode .suggestion-name {
        color: #E4E6EB;
    }

    body.dark-mode .suggestion-name a {
        color: #E4E6EB;
    }

    body.dark-mode .suggestion-followers {
        color: #B0B3B8;
    }

    body.dark-mode .suggestion-followers strong {
        color: #2D88FF;
    }

    body.dark-mode .follow-btn.following {
        background: #3A3B3C;
        color: #E4E6EB;
    }

    body.dark-mode .follow-btn.following:hover {
        background: #4E4F50;
    }

    body.dark-mode .suggestion-empty {
        color: #B0B3B8;
    }

    body.dark-mode .suggestion-empty i {
        color: #4E4F50;
    }

    @media (max-width: 600px) {
        .suggestions-page {
            padding: 12px 10px 80px;
        }

        .suggestion-card {
            padding: 12px;
            gap: 10px;
        }

        .suggestion-avatar img.avatar-img,
        .suggestion-avatar .avatar-placeholder {
            width: 48px;
            height: 48px;
        }

        .suggestion-name {
            font-size: 14px;
        }

        .follow-btn {
            padding: 7px 16px;
            font-size: 13px;
        }
    }
    </style>

</head>
<body>
 @include('layouts.navbar')

<div class="suggestions-page">
    <div class="suggestions-header">
        <i class="fas fa-user-plus"></i>
        <h5>People You May Want to Follow</h5>
        @if(isset($otherUsers) && count($otherUsers) > 0)
            <span>{{ count($otherUsers) }} suggestions</span>
        @endif
    </div>

    @forelse($otherUsers as $person)
        <div class="suggestion-card" id="suggestion-{{ $person->id }}">
            <a href="{{ route('profile.show', $person->id) }}" class="suggestion-avatar">
                @if($person->profileimg)
                    <img src="{{ str_replace('/upload/', '/upload/w_56,h_56,c_fill,r_max,q_70/', $person->profileimg) }}" alt="{{ $person->name }}" class="avatar-img">
                @else
                    <div class="avatar-placeholder">
                        <i class="fa fa-user"></i>
                    </div>
                @endif
                @if($person->badge_status)
                    <img src="{{ asset($person->badge_status) }}" alt="Verified" title="Verified User" class="badge-icon">
                @endif
            </a>

            <div class="suggestion-info">
                <p class="suggestion-name">
                    <a href="{{ route('profile.show', $person->id) }}">{{ $person->name }}</a>
                </p>
                <p class="suggestion-followers">
                    <strong id="count-{{ $person->id }}">{{ $person->followers_count }}</strong> {{ Str::plural('Follower', $person->followers_count) }}
                </p>
            </div>

            <button class="follow-btn"
                    data-user-id="{{ $person->id }}"
                    data-url="{{ route('follow', $person->id) }}"
                    onclick="followUser(this)">
                Follow
            </button>
        </div>
    @empty
        <div class="suggestion-empty">
            <i class="fas fa-users"></i>
            <p>No new people to follow right now.</p>
        </div>
    @endforelse
</div>

<!-- Toast notification element -->
<div class="follow-toast" id="followToast"></div>

<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Then other jQuery-based scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>

<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

function showToast(message, type) {
    var toast = document.getElementById('followToast');
    toast.textContent = message;
    toast.className = 'follow-toast ' + type;
    // Force reflow then show
    toast.offsetHeight;
    toast.classList.add('show');
    setTimeout(function() {
        toast.classList.remove('show');
    }, 2500);
}

function followUser(btn) {
    var url = btn.getAttribute('data-url');
    var userId = btn.getAttribute('data-user-id');

    btn.disabled = true;
    btn.textContent = 'Following...';

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            btn.textContent = 'Following';
            btn.classList.add('following');
            btn.disabled = true;

            // Update follower count
            var countEl = document.getElementById('count-' + userId);
            if (countEl && data.followers_count !== undefined) {
                countEl.textContent = data.followers_count;
            }

            showToast(data.message || 'Followed successfully!', 'success');
        },
        error: function(xhr) {
            btn.disabled = false;
            btn.textContent = 'Follow';

            var msg = 'Something went wrong. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }

            // If already following, update the button state
            if (msg.indexOf('already following') !== -1) {
                btn.textContent = 'Following';
                btn.classList.add('following');
                btn.disabled = true;
            }

            showToast(msg, 'error');
        }
    });
}
</script>

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>

</body>
</html>
