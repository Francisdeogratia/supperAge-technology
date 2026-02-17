<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $event->title }} - Supperage Events</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/post.css') }}">

    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">

    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">


    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    
    <!-- google ads -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2892124627300694"
     crossorigin="anonymous"></script>
     
    <style>
        .event-detail-container {
            background: #f5f7fa;
            min-height: 100vh;
            padding: 20px 0;
        }

        .event-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .event-hero-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            opacity: 0.9;
        }

        .event-hero-content {
            padding: 40px;
            color: white;
        }

        .event-category-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.3);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .event-title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .event-meta-line {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 20px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
        }

        .meta-item i {
            font-size: 20px;
        }

        .content-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #667eea;
        }

        .creator-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8f9ff;
            border-radius: 10px;
        }

        .creator-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .creator-details h5 {
            margin: 0;
            font-weight: bold;
        }

        .creator-details p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }

        .attendees-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .attendee-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #f8f9ff;
            border-radius: 10px;
        }

        .attendee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .attendee-name {
            font-weight: 600;
            font-size: 14px;
        }

        .action-buttons {
            position: sticky;
            top: 20px;
        }

        .btn-rsvp {
            width: 100%;
            padding: 15px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 18px;
            border: none;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .btn-rsvp-attending {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-rsvp-attend {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-rsvp:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .info-box {
            background: #f8f9ff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .info-box-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #667eea;
        }

        .info-box-content {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
        }

        .map-container {
            width: 100%;
            height: 300px;
            border-radius: 10px;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 15px;
        }

        @media (max-width: 768px) {
            .event-title {
                font-size: 26px;
            }

            .event-hero-content {
                padding: 20px;
            }

            .content-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Your msg  navbar content  -->

 @include('layouts.navbar')
    @extends('layouts.app')
    @section('content')

    <div class="event-detail-container">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Event Hero -->
                    <div class="event-hero">
                        @if($event->event_image)
                        <img src="{{ $event->event_image }}" alt="{{ $event->title }}" class="event-hero-image">
                        @endif
                        <div class="event-hero-content">
                            <span class="event-category-badge">
                                <i class="fas fa-tag"></i> {{ ucfirst($event->category) }}
                            </span>
                            <h1 class="event-title">{{ $event->title }}</h1>
                            
                            <div class="event-meta-line">
                                <div class="meta-item">
                                    <i class="far fa-calendar"></i>
                                    <span>{{ $event->event_date->format('l, F d, Y') }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="far fa-clock"></i>
                                    <span>{{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}</span>
                                </div>
                                <div class="meta-item">
                                    @if($event->event_type === 'online')
                                        <i class="fas fa-globe"></i>
                                        <span>Online Event</span>
                                    @elseif($event->event_type === 'physical')
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Physical Event</span>
                                    @else
                                        <i class="fas fa-layer-group"></i>
                                        <span>Hybrid Event</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="content-section">
                        <h3 class="section-title">
                            <i class="fas fa-info-circle"></i> About This Event
                        </h3>
                        <p style="white-space: pre-wrap; line-height: 1.8;">{{ $event->description }}</p>
                    </div>

                    <!-- Location -->
                    @if($event->location)
                    <div class="content-section">
                        <h3 class="section-title">
                            <i class="fas fa-map-marker-alt"></i> Location
                        </h3>
                        <div class="info-box-content">
                            <i class="fas fa-map-pin" style="color: #dc3545;"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                        <div class="map-container">
                            <i class="fas fa-map fa-3x text-muted"></i>
                        </div>
                    </div>
                    @endif

                    <!-- Host -->
                    <div class="content-section">
                        <h3 class="section-title">
                            <i class="fas fa-user-tie"></i> Event Host
                        </h3>
                        <div class="creator-info">
                            <img src="{{ $event->creator->profileimg ?? asset('images/best3.png') }}" 
                                 alt="{{ $event->creator->name }}" 
                                 class="creator-avatar">
                            <div class="creator-details">
                                <h5>{{ $event->creator->name }}</h5>
                                <p>{{ '@'.$event->creator->username }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Attendees -->
                    <div class="content-section">
                        <h3 class="section-title">
                            <i class="fas fa-users"></i> Attendees ({{ $event->attendee_count }})
                        </h3>
                        
                        @if($event->attendees->where('status', 'attending')->count() > 0)
                        <div class="attendees-grid">
                            @foreach($event->attendees->where('status', 'attending') as $attendee)
                            <div class="attendee-card">
                                <img src="{{ $attendee->user->profileimg ?? asset('images/best3.png') }}" 
                                     alt="{{ $attendee->user->name }}" 
                                     class="attendee-avatar">
                                <div>
                                    <div class="attendee-name">{{ $attendee->user->name }}</div>
                                    <small class="text-muted">{{ '@'.$attendee->user->username }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted">No one has RSVP'd yet. Be the first!</p>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="action-buttons">
                        @if($isAttending)
                        <button class="btn btn-rsvp btn-rsvp-attending" onclick="cancelRsvp()">
                            <i class="fas fa-check-circle"></i> You're Attending
                        </button>
                        @else
                        <button class="btn btn-rsvp btn-rsvp-attend" onclick="attendEvent()">
                            <i class="fas fa-calendar-check"></i> Attend Event
                        </button>
                        @endif

                        @if($isCreator)
                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning btn-block mb-2" style="padding: 10px; border-radius: 10px;">
                            <i class="fas fa-edit"></i> Edit Event
                        </a>
                        <button class="btn btn-danger btn-block" style="padding: 10px; border-radius: 10px;" onclick="deleteEvent()">
                            <i class="fas fa-trash"></i> Delete Event
                        </button>
                        @endif

                        <!-- Event Details -->
                        <div class="info-box mt-4">
                            <div class="info-box-title">
                                <i class="fas fa-users"></i> Capacity
                            </div>
                            <div class="info-box-content">
                                <span>
                                    @if($event->max_attendees)
                                        {{ $event->attendee_count }} / {{ $event->max_attendees }} attending
                                    @else
                                        {{ $event->attendee_count }} attending (Unlimited)
                                    @endif
                                </span>
                            </div>
                        </div>

                        @if($event->meeting_link)
                        <div class="info-box">
                            <div class="info-box-title">
                                <i class="fas fa-video"></i> Meeting Link
                            </div>
                            @if($isAttending || $isCreator)
                            <a href="{{ $event->meeting_link }}" target="_blank" class="btn btn-primary btn-block">
                                <i class="fas fa-external-link-alt"></i> Join Meeting
                            </a>
                            @else
                            <p class="text-muted">RSVP to view meeting link</p>
                            @endif
                        </div>
                        @endif

                        <div class="info-box">
                            <div class="info-box-title">
                                <i class="fas fa-share-alt"></i> Share Event
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-primary flex-fill" onclick="shareEvent('facebook')">
                                    <i class="fab fa-facebook"></i>
                                </button>
                                <button class="btn btn-sm btn-info flex-fill" onclick="shareEvent('twitter')">
                                    <i class="fab fa-twitter"></i>
                                </button>
                                <button class="btn btn-sm btn-success flex-fill" onclick="shareEvent('whatsapp')">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary flex-fill" onclick="copyLink()">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('myjs/allpost.js') }}"></script>

    <script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->

    <script src="{{ asset('myjs/tales.js') }}"></script>

    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>

    <script src="{{ asset('myjs/searchuser.js') }}"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';
        const eventId = {{ $event->id }};

        function attendEvent() {
            if (confirm('RSVP for this event?')) {
                $.post('{{ route("events.rsvp", $event->id) }}', { _token: csrfToken }, function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    }
                }).fail(function(xhr) {
                    alert(xhr.responseJSON?.error || 'Failed to RSVP');
                });
            }
        }

        function cancelRsvp() {
            if (confirm('Cancel your RSVP?')) {
                $.post('{{ route("events.cancelRsvp", $event->id) }}', { _token: csrfToken }, function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    }
                }).fail(function(xhr) {
                    alert('Failed to cancel RSVP');
                });
            }
        }

        function deleteEvent() {
            if (confirm('Are you sure you want to delete this event? This cannot be undone.')) {
                $.ajax({
                    url: '{{ route("events.destroy", $event->id) }}',
                    method: 'DELETE',
                    data: { _token: csrfToken },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            window.location.href = '{{ route("events.index") }}';
                        }
                    }
                });
            }
        }

        function shareEvent(platform) {
            const url = window.location.href;
            const title = '{{ $event->title }}';
            
            let shareUrl = '';
            
            if (platform === 'facebook') {
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            } else if (platform === 'twitter') {
                shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
            } else if (platform === 'whatsapp') {
                shareUrl = `https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`;
            }
            
            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        }

        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Event link copied to clipboard!');
            });
        }
    </script>

    @endsection
</body>
</html>