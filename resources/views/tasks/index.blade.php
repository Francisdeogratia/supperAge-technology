
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

    <title>task center</title>

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


    
    
    </head>
<body>
<!-- Your msg  navbar content  -->

 @include('layouts.navbar')





@extends('layouts.app')

@section('content')
<div class="container">
    <small class="d-block text-muted" style="color:darkgray;">🏆 Task Center <br>Jetmav-Reward-System,complete any task to earn</small>

    

<!-- this is custom task  -->

@php
$now = \Carbon\Carbon::now();
$activeTasks = $userTasks->filter(fn($task) => \Carbon\Carbon::parse($task->created_at)->addDays($task->duration)->gt($now));
$expiredTasks = $userTasks->filter(fn($task) => \Carbon\Carbon::parse($task->created_at)->addDays($task->duration)->lte($now));
@endphp

@if($userTasks->isNotEmpty())
  <ul class="nav nav-tabs mb-3" id="taskTabs">
    <li class="nav-item">
      <a class="nav-link active" href="#" data-target="#activeTasks">🟢 Active Tasks</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-target="#expiredTasks">🔴 Expired Tasks</a>
    </li>
  </ul>

  <div id="activeTasks">
    @include('partials.task-list', ['tasks' => $activeTasks])
  </div>

  <div id="expiredTasks" style="display:none;">
    @include('partials.task-list', ['tasks' => $expiredTasks, 'expired' => true])
  </div>
@endif

<!-- second and system task  -->

@if($tasks->isEmpty())
        <div class="alert alert-info text-center">
            <strong>No company's tasks available right now.</strong><br>
            Please check back later for new opportunities to earn rewards.
        </div>
    @else
        @foreach($tasks as $category => $categoryTasks)
            <h3 class="mt-4 text-capitalize">{{ $category }} Tasks</h3>
            <div class="list-group">
                @foreach($categoryTasks as $task)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
    <div>
        <strong>{{ $task->title }}</strong><br>
        <small>{{ $task->description }}</small>
    </div>
    <div>
        <span class="badge bg-success">{{ $task->reward_points }} NGN</span>

        @if($task->title === 'Invite a friend')
    <a href="{{ route('invite', ['task' => 'invite']) }}" class="btn btn-sm btn-warning">Invite Friends</a>
@elseif($task->title === 'Share our app for download')
    <a href="{{ route('invite', ['task' => 'app']) }}" class="btn btn-sm btn-warning">Share App</a>
@elseif(!$user || !$user->tasks->contains($task->id))
    <form action="{{ route('tasks.complete', $task->id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-primary">Mark Complete</button>
    </form>
@else
    <span class="badge bg-secondary">Completed</span>
@endif


    </div>
</div>

                    
                @endforeach
            </div>
        @endforeach
    @endif

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

<div id="followSpinnerPopup" style="
    position: fixed;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #28a745;
    color: white;
    padding: 20px 30px;
    border-radius: 10px;
    font-size: 18px;
    font-weight: bold;
    display: none;
    z-index: 9999;
    text-align: center;
">
    ✅ Follow task completed successfully!
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const followForms = document.querySelectorAll('.follow-form');
    const popup = document.getElementById('followSpinnerPopup');

    followForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const taskId = form.querySelector('.follow-btn').dataset.taskId;
            const followBtn = form.querySelector('.follow-btn');
            const spinner = form.nextElementSibling;
            const formData = new FormData(form);

            spinner.style.display = 'block';
            followBtn.disabled = true;
            followBtn.innerText = '⏳ Following...';

            fetch(form.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    followBtn.innerText = '✅ Followed';
                    followBtn.classList.replace('btn-outline-primary', 'btn-success');
                    followBtn.disabled = true;

                    popup.innerText = data.message || '✅ Follow task completed successfully!';
                    popup.style.backgroundColor = '#28a745';
                    popup.style.display = 'block';

                    const audio = new Audio("{{ asset('sounds/mixkit-fantasy-game-success-notification-270.wav') }}");
                    audio.play();

                    setTimeout(() => {
                        popup.style.display = 'none';
                    }, 3000);
                } else {
                    followBtn.disabled = false;
                    followBtn.innerText = '👤 Follow';

                    popup.innerText = data.message || 'Follow failed.';
                    popup.style.backgroundColor = '#dc3545';
                    popup.style.display = 'block';

                    const audio = new Audio("{{ asset('sounds/mixkit-electric-fence-fx-2968.wav') }}");
                    audio.play();

                    setTimeout(() => {
                        popup.style.display = 'none';
                    }, 3000);
                }
            })
            .catch(err => {
                console.error('Follow request failed:', err);
                popup.innerText = 'Something went wrong while trying to follow.';
                popup.style.backgroundColor = '#dc3545';
                popup.style.display = 'block';

                const audio = new Audio("{{ asset('sounds/mixkit-electric-fence-fx-2968.wav') }}");
                audio.play();

                setTimeout(() => {
                    popup.style.display = 'none';
                }, 3000);
            })
            .finally(() => {
                spinner.style.display = 'none';
            });
        });
    });
});



</script>




<script>
// document.addEventListener('DOMContentLoaded', function () {
//     const countdownBadges = document.querySelectorAll('.countdown-badge');

//     countdownBadges.forEach(badge => {
//         const expiry = new Date(badge.dataset.expiry).getTime();
//         const taskId = badge.id.replace('countdown-', '');
//         const buttons = document.querySelectorAll(`.task-action-btn[data-task-id="${taskId}"]`);

//         function updateCountdown() {
//             const now = new Date().getTime();
//             const diff = expiry - now;

//             if (diff <= 0) {
//                 badge.innerText = "⏳ Task expired";
//                 badge.classList.remove('bg-danger');
//                 badge.classList.add('bg-secondary');

//                 buttons.forEach(button => {
//                     button.innerText = "Task has expired";
//                     button.classList.remove('btn-primary', 'btn-warning', 'btn-success', 'btn-info');
//                     button.classList.add('btn-secondary');
//                     button.setAttribute('title', 'This task is no longer available.');
//                     button.style.pointerEvents = 'none';
//                     button.removeAttribute('href');
//                     button.disabled = true;
//                 });

//                 return;
//             }

//             const days = Math.floor(diff / (1000 * 60 * 60 * 24));
//             const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
//             const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
//             const seconds = Math.floor((diff % (1000 * 60)) / 1000);

//             badge.innerText = `⏳ Expires in: ${days}d ${hours}h ${minutes}m ${seconds}s`;
//         }

//         updateCountdown();
//         setInterval(updateCountdown, 1000);
//     });
// });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const tabs = document.querySelectorAll('#taskTabs .nav-link');

  tabs.forEach(tab => {
    tab.addEventListener('click', function (e) {
      e.preventDefault();
      tabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');

      const targetId = this.dataset.target;
      document.querySelectorAll('#activeTasks, #expiredTasks').forEach(section => {
        section.style.display = 'none';
      });
      document.querySelector(targetId).style.display = 'block';
    });
  });

  const countdownBadges = document.querySelectorAll('.countdown-badge');

  countdownBadges.forEach(badge => {
    const expiry = new Date(badge.dataset.expiry).getTime();
    const taskId = badge.id.replace('countdown-', '');
    const buttons = document.querySelectorAll(`.task-action-btn[data-task-id="${taskId}"]`);

    function updateCountdown() {
      const now = new Date().getTime();
      const diff = expiry - now;

      if (diff <= 0) {
        badge.innerText = "⏳ Task expired";
        badge.classList.remove('bg-danger');
        badge.classList.add('bg-secondary');

        buttons.forEach(button => {
          button.innerText = "Task has expired";
          button.classList.remove('btn-primary', 'btn-warning', 'btn-success', 'btn-info');
          button.classList.add('btn-secondary');
          button.setAttribute('title', 'This task is no longer available.');
          button.style.pointerEvents = 'none';
          button.removeAttribute('href');
          button.disabled = true;
        });

        return;
      }

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((diff % (1000 * 60)) / 1000);

      badge.innerText = `⏳ Expires in: ${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
  });
});
</script>




</body>
</html>