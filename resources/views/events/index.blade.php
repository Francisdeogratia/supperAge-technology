<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Events - Supperage</title>
    
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
        .events-container {
            background: #f5f7fa;
            min-height: 100vh;
            padding: 20px 0;
        }

        .events-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            margin-bottom: 30px;
            border-radius: 0 0 20px 20px;
        }

        .events-header h1 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .events-header p {
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .btn-create-event {
            background: white;
            color: #667eea;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-create-event:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #667eea;
        }

        .event-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 20px;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .event-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .event-content {
            padding: 20px;
        }

        .event-category {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .event-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .event-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .event-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .event-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 14px;
        }

        .event-meta-item i {
            color: #667eea;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .event-creator {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .creator-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }

        .creator-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .event-attendees {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 14px;
        }

        .btn-view-event {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-view-event:hover {
            background: #5568d3;
            transform: scale(1.05);
        }

        .event-type-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }

        .event-type-online {
            color: #667eea;
        }

        .event-type-physical {
            color: #28a745;
        }

        .event-type-hybrid {
            color: #ff9800;
        }

        .no-events {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .no-events i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .events-header h1 {
                font-size: 24px;
            }

            .event-card {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Your msg  navbar content  -->

 @include('layouts.navbar')
    @extends('layouts.app')
    @section('seo_title', 'Events - SupperAge | Discover & Create Events')
    @section('seo_description', 'Discover upcoming events or create your own on SupperAge. Connect through meetups, workshops, and community events.')
    @section('content')

    <div class="events-container">
        <!-- Events Header -->
        <div class="events-header">
            <div class="container">
                <h1><i class="fas fa-calendar-alt"></i> Events</h1>
                <p>Discover and join amazing events happening around you</p>
                <a href="{{ route('events.create') }}" class="btn-create-event">
                    <i class="fas fa-plus"></i> Create Event
                </a>
            </div>
        </div>

        <div class="container">
            <!-- My Events -->
            @if($myEvents->count() > 0)
            <div class="mb-5">
                <h3 class="section-title">
                    <i class="fas fa-star"></i> My Events
                </h3>
                <div class="row">
                    @foreach($myEvents as $event)
                    <div class="col-md-4">
                        <div class="event-card">
                            <div class="event-image" style="position: relative;">
                                @if($event->event_image)
                                    <img src="{{ $event->event_image }}" alt="{{ $event->title }}">
                                @endif
                                <span class="event-type-badge event-type-{{ $event->event_type }}">
                                    @if($event->event_type === 'online')
                                        <i class="fas fa-globe"></i> Online
                                    @elseif($event->event_type === 'physical')
                                        <i class="fas fa-map-marker-alt"></i> Physical
                                    @else
                                        <i class="fas fa-layer-group"></i> Hybrid
                                    @endif
                                </span>
                            </div>
                            <div class="event-content">
                                <span class="event-category">{{ ucfirst($event->category) }}</span>
                                <h4 class="event-title">{{ $event->title }}</h4>
                                <p class="event-description">{{ $event->description }}</p>
                                
                                <div class="event-meta">
                                    <div class="event-meta-item">
                                        <i class="far fa-calendar"></i>
                                        <span>{{ $event->event_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="event-meta-item">
                                        <i class="far fa-clock"></i>
                                        <span>{{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}</span>
                                    </div>
                                    @if($event->location)
                                    <div class="event-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ Str::limit($event->location, 30) }}</span>
                                    </div>
                                    @endif
                                </div>

                                <div class="event-footer">
                                    <div class="event-attendees">
                                        <i class="fas fa-users"></i>
                                        <span>{{ $event->attendee_count }} attending</span>
                                    </div>
                                    <a href="{{ route('events.show', $event->id) }}" class="btn-view-event">
                                        View Event
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Events I'm Attending -->
            @if($attendingEvents->count() > 0)
            <div class="mb-5">
                <h3 class="section-title">
                    <i class="fas fa-check-circle"></i> Events I'm Attending
                </h3>
                <div class="row">
                    @foreach($attendingEvents as $event)
                    <div class="col-md-4">
                        <div class="event-card">
                            <div class="event-image" style="position: relative;">
                                @if($event->event_image)
                                    <img src="{{ $event->event_image }}" alt="{{ $event->title }}">
                                @endif
                                <span class="event-type-badge event-type-{{ $event->event_type }}">
                                    @if($event->event_type === 'online')
                                        <i class="fas fa-globe"></i> Online
                                    @elseif($event->event_type === 'physical')
                                        <i class="fas fa-map-marker-alt"></i> Physical
                                    @else
                                        <i class="fas fa-layer-group"></i> Hybrid
                                    @endif
                                </span>
                            </div>
                            <div class="event-content">
                                <span class="event-category">{{ ucfirst($event->category) }}</span>
                                <h4 class="event-title">{{ $event->title }}</h4>
                                <p class="event-description">{{ $event->description }}</p>
                                
                                <div class="event-meta">
                                    <div class="event-meta-item">
                                        <i class="far fa-calendar"></i>
                                        <span>{{ $event->event_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="event-meta-item">
                                        <i class="far fa-clock"></i>
                                        <span>{{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}</span>
                                    </div>
                                </div>

                                <div class="event-footer">
                                    <div class="event-creator">
                                        <img src="{{ $event->creator->profileimg ?? asset('images/best3.png') }}" 
                                             alt="{{ $event->creator->name }}" 
                                             class="creator-avatar">
                                        <span class="creator-name">{{ $event->creator->name }}</span>
                                    </div>
                                    <a href="{{ route('events.show', $event->id) }}" class="btn-view-event">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Upcoming Events -->
            <div class="mb-5">
                <h3 class="section-title">
                    <i class="fas fa-fire"></i> Upcoming Events
                </h3>
                
                @if($upcomingEvents->count() > 0)
                <div class="row">
                    @foreach($upcomingEvents as $event)
                    <div class="col-md-4">
                        <div class="event-card">
                            <div class="event-image" style="position: relative;">
                                @if($event->event_image)
                                    <img src="{{ $event->event_image }}" alt="{{ $event->title }}">
                                @endif
                                <span class="event-type-badge event-type-{{ $event->event_type }}">
                                    @if($event->event_type === 'online')
                                        <i class="fas fa-globe"></i> Online
                                    @elseif($event->event_type === 'physical')
                                        <i class="fas fa-map-marker-alt"></i> Physical
                                    @else
                                        <i class="fas fa-layer-group"></i> Hybrid
                                    @endif
                                </span>
                            </div>
                            <div class="event-content">
                                <span class="event-category">{{ ucfirst($event->category) }}</span>
                                <h4 class="event-title">{{ $event->title }}</h4>
                                <p class="event-description">{{ $event->description }}</p>
                                
                                <div class="event-meta">
                                    <div class="event-meta-item">
                                        <i class="far fa-calendar"></i>
                                        <span>{{ $event->event_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="event-meta-item">
                                        <i class="far fa-clock"></i>
                                        <span>{{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}</span>
                                    </div>
                                    @if($event->location)
                                    <div class="event-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ Str::limit($event->location, 30) }}</span>
                                    </div>
                                    @endif
                                </div>

                                <div class="event-footer">
                                    <div class="event-creator">
                                        <img src="{{ $event->creator->profileimg ?? asset('images/best3.png') }}" 
                                             alt="{{ $event->creator->name }}" 
                                             class="creator-avatar">
                                        <span class="creator-name">{{ $event->creator->name }}</span>
                                    </div>
                                    <a href="{{ route('events.show', $event->id) }}" class="btn-view-event">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $upcomingEvents->links() }}
                </div>
                @else
                <div class="no-events">
                    <i class="fas fa-calendar-times"></i>
                    <h4>No upcoming events</h4>
                    <p>Be the first to create an event!</p>
                    <a href="{{ route('events.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus"></i> Create Event
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="{{ asset('myjs/allpost.js') }}"></script>
    <script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->
    <script src="{{ asset('myjs/tales.js') }}"></script>
    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script>



    @endsection
</body>
</html>