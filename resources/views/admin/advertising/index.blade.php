<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <title>Admin - Ad Approvals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Your Custom CSS -->
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

</head>
<body>
    @include('layouts.navbar')
    @extends('layouts.app')
    @section('content')
    
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-shield-alt text-primary"></i> Ad Approval Panel</h2>
            <a href="{{ route('advertising.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Ads
            </a>
        </div>

        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link {{ $status == 'pending' ? 'active' : '' }}" 
                   href="{{ route('admin.advertising.index', ['status' => 'pending']) }}">
                    Pending ({{ $stats['pending'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'active' ? 'active' : '' }}" 
                   href="{{ route('admin.advertising.index', ['status' => 'active']) }}">
                    Active ({{ $stats['active'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'rejected' ? 'active' : '' }}" 
                   href="{{ route('admin.advertising.index', ['status' => 'rejected']) }}">
                    Rejected ({{ $stats['rejected'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'paused' ? 'active' : '' }}" 
                   href="{{ route('admin.advertising.index', ['status' => 'paused']) }}">
                    Paused ({{ $stats['paused'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" 
                   href="{{ route('admin.advertising.index', ['status' => 'all']) }}">
                    All Ads
                </a>
            </li>
        </ul>

        @if($ads->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No ads found for this status.
            </div>
        @else
            <div class="row">
                @foreach($ads as $ad)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="mb-1">{{ $ad->title }}</h5>
                                        <small class="text-muted">
                                            By: <strong>{{ $ad->user->name }}</strong> 
                                            {{ '@'.  $ad->user->username }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $ad->status == 'pending' ? 'warning' : ($ad->status == 'active' ? 'success' : 'danger') }}">
                                        {{ ucfirst($ad->status) }}
                                    </span>
                                </div>

                                <!-- Ad Preview -->
                                @if($ad->media_url)
                                    @if($ad->media_type == 'image')
                                        <img src="{{ $ad->media_url }}" class="img-fluid rounded mb-3" alt="Ad Banner">
                                    @elseif($ad->media_type == 'video')
                                        <video controls class="w-100 rounded mb-3">
                                            <source src="{{ $ad->media_url }}" type="video/mp4">
                                        </video>
                                    @endif
                                @endif

                                <p class="mb-2">{{ Str::limit($ad->description, 100) }}</p>

                                <!-- Ad Details -->
                                <div class="mb-3">
                                    <small class="d-block"><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $ad->ad_type)) }}</small>
                                    <small class="d-block"><strong>Budget:</strong> ₦{{ number_format($ad->budget, 2) }}</small>
                                    <small class="d-block"><strong>Spent:</strong> ₦{{ number_format($ad->spent, 2) }}</small>
                                    <small class="d-block"><strong>Impressions:</strong> {{ number_format($ad->impressions) }}</small>
                                    <small class="d-block"><strong>Clicks:</strong> {{ number_format($ad->clicks) }}</small>
                                    <small class="d-block"><strong>Target:</strong>
                                        @if($ad->target_countries)
                                            {{ implode(', ', is_array($ad->target_countries) ? $ad->target_countries : json_decode($ad->target_countries, true)) }}
                                        @else
                                            All Countries
                                        @endif
                                        @if($ad->target_gender)
                                            , {{ implode(', ', is_array($ad->target_gender) ? $ad->target_gender : json_decode($ad->target_gender, true)) }}
                                        @endif
                                        @if($ad->target_age_range)
                                            @php
                                                $ageRange = is_array($ad->target_age_range) ? $ad->target_age_range : json_decode($ad->target_age_range, true);
                                            @endphp
                                            , Ages {{ $ageRange['min'] ?? '13' }}-{{ $ageRange['max'] ?? '100+' }}
                                        @endif
                                    </small>
                                    <small class="d-block"><strong>Duration:</strong> 
                                        {{ \Carbon\Carbon::parse($ad->start_date)->format('M d') }} - 
                                        {{ \Carbon\Carbon::parse($ad->end_date)->format('M d, Y') }}
                                    </small>
                                    @if($ad->rejection_reason)
                                        <small class="d-block text-danger"><strong>Rejection Reason:</strong> {{ $ad->rejection_reason }}</small>
                                    @endif
                                </div>

                                <!-- Admin Actions -->
                                @if($ad->status == 'pending')
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-success flex-fill" onclick="approveAd({{ $ad->id }})">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        
                                        <button class="btn btn-danger flex-fill" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $ad->id }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $ad->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Ad</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Why are you rejecting this ad?</p>
                                                    <textarea id="rejectReason{{ $ad->id }}" class="form-control" rows="3" required 
                                                              placeholder="Provide a reason..."></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-danger" onclick="rejectAd({{ $ad->id }})">Reject Ad</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($ad->status == 'active')
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-warning flex-fill" onclick="pauseAd({{ $ad->id }})">
                                            <i class="fas fa-pause"></i> Pause
                                        </button>
                                        <a href="{{ route('admin.advertising.show', $ad->id) }}" class="btn btn-primary flex-fill">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('admin.advertising.show', $ad->id) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $ads->appends(['status' => $status])->links() }}
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script>

    <script>
        const csrfToken = '{{ csrf_token() }}';

        function approveAd(adId) {
            if (confirm('Approve this ad?')) {
                fetch(`/admin/advertising/${adId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                })
                .catch(err => {
                    alert('Failed to approve ad');
                    console.error(err);
                });
            }
        }

        function rejectAd(adId) {
            const reason = document.getElementById(`rejectReason${adId}`).value;
            
            if (!reason.trim()) {
                alert('Please enter a rejection reason');
                return;
            }

            fetch(`/admin/advertising/${adId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(err => {
                alert('Failed to reject ad');
                console.error(err);
            });
        }

        function pauseAd(adId) {
            if (confirm('Pause this ad?')) {
                fetch(`/admin/advertising/${adId}/pause`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                })
                .catch(err => {
                    alert('Failed to pause ad');
                    console.error(err);
                });
            }
        }
    </script>
    
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif
    
    @endsection
</body>
</html>