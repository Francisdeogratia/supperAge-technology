<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ad Manager - Supperage</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Your Custom CSS -->

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/post.css') }}">

    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">

    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

<!-- google ads -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2892124627300694"
     crossorigin="anonymous"></script>
    
    <style>
        .ad-manager-container {
            background: #f5f7fa;
            min-height: 100vh;
            padding: 20px 0;
        }

        .ad-manager-header {
            background: linear-gradient(135deg, #8A2BE2 0%, #9370DB 100%);
            color: white;
            padding: 40px 20px;
            margin-bottom: 30px;
            border-radius: 15px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
        }

        .ad-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }

        .ad-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .ad-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
        }

        .ad-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-paused {
            background: #f8d7da;
            color: #721c24;
        }

        .status-completed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .ad-metrics {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }

        .metric-item {
            text-align: center;
        }

        .metric-value {
            font-size: 20px;
            font-weight: bold;
            color: #8A2BE2;
        }

        .metric-label {
            font-size: 12px;
            color: #666;
        }

        .progress-custom {
            height: 8px;
            border-radius: 10px;
            background: #e0e0e0;
        }

        .progress-bar-custom {
            background: linear-gradient(90deg, #8A2BE2 0%, #9370DB 100%);
            border-radius: 10px;
        }

        .btn-create-ad {
            background: linear-gradient(135deg, #8A2BE2 0%, #9370DB 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            border: none;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Your msg  navbar content  -->

 @include('layouts.navbar')

    @extends('layouts.app')
    @section('seo_title', 'Ad Manager - SupperAge | Promote Your Business')
    @section('seo_description', 'Create and manage ads on SupperAge. Reach millions of users and grow your brand with targeted advertising.')
    @section('content')

    
    <div class="ad-manager-container">
        <div class="container">
            <!-- Header -->
            <div class="ad-manager-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1><i class="fas fa-ad"></i> Ad Manager</h1>
                        <p class="mb-0">Manage and track your advertising campaigns</p>
                    </div>
                    <a href="{{ route('advertising.create') }}" class="btn-create-ad">
                        <i class="fas fa-plus"></i> Create New Ad
                    </a>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-value">{{ $totalAds }}</div>
                        <div class="stat-label">Total Campaigns</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #e3f2fd; color: #1565c0;">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="stat-value">{{ $activeAds }}</div>
                        <div class="stat-label">Active Ads</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #fce4ec; color: #c2185b;">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-value">{{ number_format($totalImpressions) }}</div>
                        <div class="stat-label">Total Impressions</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #fff3e0; color: #ef6c00;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-value">₦{{ number_format($totalSpent, 2) }}</div>
                        <div class="stat-label">Total Spent</div>
                    </div>
                </div>
            </div>

            <!-- Ads List -->
            <div class="mt-4">
                <h4 class="mb-4">Your Campaigns</h4>

                @if($ads->count() > 0)
    @foreach($ads as $ad)
    <div class="ad-card">
        <div class="row align-items-center">
            {{-- Media: 2 cols --}}
            <div class="col-md-2 col-sm-3">
                @if($ad->media_url)
                    @if($ad->media_type === 'video')
                        <video class="ad-image" controls style="width: 120px; height: 120px; object-fit: cover; border-radius: 10px;">
                            <source src="{{ $ad->media_url }}" type="video/mp4">
                        </video>
                    @else
                        <img src="{{ $ad->media_url }}" alt="{{ $ad->title }}" class="ad-image">
                    @endif
                @else
                    <div class="ad-image d-flex align-items-center justify-content-center bg-light">
                        <i class="fas fa-image fa-2x text-muted"></i>
                    </div>
                @endif
            </div>

            {{-- Content: 5 cols (reduced from 6) --}}
            <div class="col-md-5 col-sm-4">
                <div class="d-flex align-items-center mb-2">
                    <h5 class="mb-0 mr-3">{{ $ad->title }}</h5>
                    <span class="ad-status status-{{ $ad->status }}">
                        {{ ucfirst($ad->status) }}
                    </span>
                </div>
                <p class="text-muted mb-2">{{ Str::limit($ad->description, 80) }}</p>
                
                <div class="d-flex align-items-center text-muted small">
                    <span><i class="far fa-calendar"></i> {{ $ad->start_date->format('M d') }} - {{ $ad->end_date->format('M d, Y') }}</span>
                </div>

                <!-- Budget Progress -->
                <div class="mt-2">
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Budget Used</span>
                        <strong>{{ number_format(($ad->spent / $ad->budget) * 100, 1) }}%</strong>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar-custom" style="width: {{ min(($ad->spent / $ad->budget) * 100, 100) }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Metrics: 3 cols --}}
            <div class="col-md-3 col-sm-3">
                <div class="ad-metrics">
                    <div class="metric-item">
                        <div class="metric-value">{{ number_format($ad->impressions) }}</div>
                        <div class="metric-label">Impressions</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-value">{{ number_format($ad->clicks) }}</div>
                        <div class="metric-label">Clicks</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-value">{{ $ad->impressions > 0 ? number_format(($ad->clicks / $ad->impressions) * 100, 1) : 0 }}%</div>
                        <div class="metric-label">CTR</div>
                    </div>
                </div>
            </div>

            {{-- Actions: 2 cols --}}
            <div class="col-md-2 col-sm-2 text-center">
                <a href="{{ route('advertising.show', $ad->id) }}" class="btn btn-sm btn-outline-primary btn-block mb-2">
                    <i class="fas fa-chart-line"></i> View
                </a>
                
                <div class=" d-inline-block mt-4">
                    <button class="btn btn-sm btn-outline-secondary btn-block dropdown-toggle" 
                            type="button" 
                            data-toggle="dropdown">
                        <i class="fas fa-cog"></i> Actions
                    </button>
                    <div class="dropdown-menu">
                        @if($ad->status !== 'completed' && $ad->status !== 'paused')
                        <button class="dropdown-item" onclick="pauseAd({{ $ad->id }})">
                            <i class="fas fa-pause"></i> Pause
                        </button>
                        @endif
                        @if($ad->status === 'paused')
                        <button class="dropdown-item" onclick="resumeAd({{ $ad->id }})">
                            <i class="fas fa-play"></i> Resume
                        </button>
                        @endif
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item text-danger" onclick="deleteAd({{ $ad->id }})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endforeach

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $ads->links() }}
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-ad"></i>
        <h4>No ads yet</h4>
        <p class="text-muted">Create your first ad to start reaching thousands of users</p>
        <a href="{{ route('advertising.create') }}" class="btn-create-ad mt-3">
            <i class="fas fa-plus"></i> Create Your First Ad
        </a>
    </div>
@endif



            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->
<script src="{{ asset('myjs/allpost.js') }}"></script>

<script src="{{ asset('myjs/tales.js') }}"></script>

<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>

<script src="{{ asset('myjs/searchuser.js') }}"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';

        function pauseAd(adId) {
            if (confirm('Pause this ad campaign?')) {
                $.ajax({
                    url: `/advertising/${adId}`,
                    method: 'PUT',
                    data: {
                        _token: csrfToken,
                        status: 'paused'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Ad paused successfully');
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('Failed to pause ad');
                    }
                });
            }
        }

        function deleteAd(adId) {
            if (confirm('Are you sure you want to delete this ad? This cannot be undone.')) {
                $.ajax({
                    url: `/advertising/${adId}`,
                    method: 'DELETE',
                    data: { _token: csrfToken },
                    success: function(response) {
                        if (response.success) {
                            alert('Ad deleted successfully');
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('Failed to delete ad');
                    }
                });
            }
        }
    </script>

    @endsection
</body>
</html>