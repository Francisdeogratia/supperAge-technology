<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Go Live - Supperage</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/post.css') }}">

    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">

    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">


    
    <style>
        .live-container {
            background: #f5f7fa;
            min-height: 100vh;
            padding: 20px 0;
        }

        .live-header {
            background: linear-gradient(135deg, #FF0000 0%, #FF6B6B 100%);
            color: white;
            padding: 40px 20px;
            margin-bottom: 30px;
            border-radius: 0 0 20px 20px;
            text-align: center;
        }

        .live-header h1 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .live-pulse {
            display: inline-block;
            width: 12px;
            height: 12px;
            background: white;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
            margin-right: 8px;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.7; }
        }

        .btn-go-live {
            background: white;
            color: #FF0000;
            border: none;
            padding: 15px 40px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 18px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
        }

        .btn-go-live:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-go-live i {
            margin-right: 10px;
        }

        .reward-banner {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .reward-banner h4 {
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .reward-amount {
            font-size: 36px;
            font-weight: bold;
            color: #FF0000;
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .live-badge {
            background: #FF0000;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .stream-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 20px;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .stream-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stream-thumbnail {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stream-thumbnail i {
            font-size: 64px;
            color: rgba(255, 255, 255, 0.5);
        }

        .stream-live-badge {
            position: absolute;
            top: 15px;
            left: 15px;
        }

        .stream-viewers {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: bold;
        }

        .stream-content {
            padding: 20px;
        }

        .stream-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .stream-creator {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .creator-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .creator-name {
            font-weight: 600;
            color: #666;
        }

        .btn-watch {
            width: 100%;
            background: linear-gradient(135deg, #FF0000 0%, #FF6B6B 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-watch:hover {
            transform: scale(1.02);
        }

        .no-streams {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .no-streams i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .live-header h1 {
                font-size: 24px;
            }

            .reward-amount {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <!-- Your msg  navbar content  -->

 @include('layouts.navbar')
    @extends('layouts.app')
    @section('seo_title', 'Live Streaming - SupperAge | Go Live')
    @section('seo_description', 'Watch live streams or go live on SupperAge. Share moments in real-time with your community and earn while streaming.')
    @section('content')

    <div class="live-container">
        <div class="live-header">
            <h1>
                <span class="live-pulse"></span>
                Go Live on Supperage
            </h1>
            <p>Share your moments with the world and earn rewards!</p>
            
            @if($userActiveStream)
                <a href="{{ route('live.stream', $userActiveStream->id) }}" class="btn-go-live">
                    <i class="fas fa-broadcast-tower"></i> Return to Your Live Stream
                </a>
            @else
                <button class="btn-go-live" onclick="showGoLiveModal()">
                    <i class="fas fa-video"></i> Start Live Stream
                </button>
            @endif
        </div>

        <div class="container">
            <!-- Reward Banner -->
            <div class="reward-banner">
                <h4><i class="fas fa-trophy"></i> Earn Big Rewards!</h4>
                <p class="mb-2">Reach <strong>1,000 live views</strong> and instantly earn:</p>
                <div class="reward-amount">₦15,000</div>
                <small>Credited directly to your wallet!</small>
            </div>

            <!-- Active Live Streams -->
            @if($liveStreams->count() > 0)
            <div class="mb-5">
                <h3 class="section-title">
                    <i class="fas fa-fire" style="color: #FF0000;"></i> Live Now
                </h3>
                
                <div class="row">
                    @foreach($liveStreams as $stream)
                    <div class="col-md-4 col-sm-6">
                        <div class="stream-card">
                            <div class="stream-thumbnail">
                                <i class="fas fa-play-circle"></i>
                                <span class="stream-live-badge live-badge">
                                    <span class="live-pulse"></span> LIVE
                                </span>
                                <span class="stream-viewers">
                                    <i class="fas fa-eye"></i> {{ $stream->viewer_count }}
                                </span>
                            </div>
                            
                            <div class="stream-content">
                                <h4 class="stream-title">{{ $stream->title }}</h4>
                                
                                <div class="stream-creator">
                                    <img src="{{ $stream->creator->profileimg ?? asset('images/best3.png') }}" 
                                         alt="{{ $stream->creator->name }}" 
                                         class="creator-avatar">
                                    <div>
                                        <div class="creator-name">{{ $stream->creator->name }}</div>
                                        <small class="text-muted">Started {{ $stream->started_at->diffForHumans() }}</small>
                                    </div>
                                </div>

                                @if($stream->description)
                                <p class="text-muted mb-3" style="font-size: 14px;">
                                    {{ Str::limit($stream->description, 80) }}
                                </p>
                                @endif

                                <a href="{{ route('live.stream', $stream->id) }}" class="btn btn-watch">
                                    <i class="fas fa-play"></i> Watch Now
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="no-streams">
                <i class="fas fa-video-slash"></i>
                <h4>No Live Streams Right Now</h4>
                <p>Be the first to go live and share your moment!</p>
                <button class="btn btn-primary mt-3" onclick="showGoLiveModal()">
                    <i class="fas fa-video"></i> Start Broadcasting
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Go Live Modal -->
    <div class="modal fade" id="goLiveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #FF0000 0%, #FF6B6B 100%); color: white; border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title">
                        <i class="fas fa-broadcast-tower"></i> Start Live Stream
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 30px;">
                    <form id="goLiveForm">
                        @csrf
                        <div class="form-group">
                            <label style="font-weight: 600;">Stream Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="title" 
                                   placeholder="What's your stream about?" required
                                   style="border-radius: 10px; padding: 12px;">
                        </div>
                        
                        <div class="form-group">
                            <label style="font-weight: 600;">Description (Optional)</label>
                            <textarea class="form-control" name="description" id="description" 
                                      rows="3" placeholder="Tell viewers what to expect..."
                                      style="border-radius: 10px; padding: 12px;"></textarea>
                        </div>

                        <div class="alert alert-info" style="border-radius: 10px;">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Tip:</strong> Engage with your viewers to keep them watching!
                        </div>

                        <button type="submit" class="btn btn-block" id="startLiveBtn"
                                style="background: linear-gradient(135deg, #FF0000 0%, #FF6B6B 100%); color: white; padding: 12px; font-weight: bold; border-radius: 10px; border: none;">
                            <i class="fas fa-video"></i> Go Live Now
                        </button>
                    </form>
                </div>
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
    <script>
        const csrfToken = '{{ csrf_token() }}';

        function showGoLiveModal() {
            $('#goLiveModal').modal('show');
        }

        $('#goLiveForm').on('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = $('#startLiveBtn');
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Starting...');

            $.ajax({
                url: '{{ route("live.start") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.stream_url;
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html('<i class="fas fa-video"></i> Go Live Now');
                    
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        alert(xhr.responseJSON.error);
                    } else {
                        alert('Failed to start stream. Please try again.');
                    }
                }
            });
        });
    </script>

    @endsection
</body>
</html>