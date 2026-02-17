<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <title>Ad Details - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    
    <style>
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #8A2BE2;
        }
        .ad-preview-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    @include('layouts.navbar')
    @extends('layouts.app')
    @section('content')
    
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="fas fa-ad"></i> {{ $ad->title }}</h2>
                <small class="text-muted">Campaign ID: #{{ $ad->id }}</small>
            </div>
            <div>
                <span class="badge bg-{{ $ad->status == 'pending' ? 'warning' : ($ad->status == 'active' ? 'success' : 'danger') }} me-2">
                    {{ ucfirst($ad->status) }}
                </span>
                <a href="{{ route('admin.advertising.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">{{ number_format($ad->impressions) }}</div>
                    <div class="text-muted">Impressions</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">{{ number_format($ad->clicks) }}</div>
                    <div class="text-muted">Clicks</div>
                </div>
            </div>

            <div class="col-md-3">
    <div class="metric-card">
        <div class="metric-value">{{ number_format($ad->actions) }}</div>
        <div class="metric-label">Conversions</div>
        <small class="text-muted">₦{{ number_format($ad->actions * 400, 2) }}</small>
    </div>
</div>

<div class="col-md-3">
    <div class="metric-card">
        <div class="metric-value">{{ $ad->getConversionRatecpa() }}%</div>
        <div class="metric-label">Conversion Rate</div>
    </div>
</div>



            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">{{ $ad->getCTR() }}%</div>
                    <div class="text-muted">CTR</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">₦{{ number_format($ad->spent, 2) }}</div>
                    <div class="text-muted">Spent</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <!-- Ad Preview -->
                <div class="ad-preview-card mb-4">
                    <h5 class="mb-3"><i class="fas fa-eye"></i> Ad Preview</h5>
                    
                    @if($ad->media_url)
                        <div class="text-center mb-3">
                            @if($ad->media_type == 'image')
                                <img src="{{ $ad->media_url }}" class="img-fluid rounded" style="max-height: 400px;" alt="Ad Media">
                            @elseif($ad->media_type == 'video')
                                <video controls class="w-100 rounded" style="max-height: 400px;">
                                    <source src="{{ $ad->media_url }}" type="video/mp4">
                                </video>
                            @endif
                        </div>
                    @endif

                    <h5 class="mt-3">{{ $ad->title }}</h5>
                    <p class="text-muted">{{ $ad->description }}</p>
                    
                    <a href="{{ $ad->cta_link }}" target="_blank" class="btn btn-primary">
                        {{ $ad->cta_text }} <i class="fas fa-external-link-alt ms-2"></i>
                    </a>
                </div>

                <!-- Performance Chart -->
                <div class="ad-preview-card">
                    <h5 class="mb-3"><i class="fas fa-chart-line"></i> Performance Over Time</h5>
                    <canvas id="performanceChart" height="80"></canvas>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <!-- Advertiser Info -->
                <div class="ad-preview-card mb-4">
                    <h5 class="mb-3"><i class="fas fa-user"></i> Advertiser</h5>
                    
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $ad->user->profileimg ?? asset('images/default-avatar.png') }}" 
                             class="rounded-circle me-3" 
                             style="width: 50px; height: 50px;">
                        <div>
                            <strong>{{ $ad->user->name }}</strong><br>
                            <small class="text-muted">{{ '@' . $ad->user->username }}</small>
                        </div>
                    </div>
                    
                    <small class="d-block text-muted">
                        <i class="fas fa-envelope"></i> {{ $ad->user->email }}
                    </small>
                    <small class="d-block text-muted">
                        <i class="fas fa-phone"></i> {{ $ad->user->phone ?? 'N/A' }}
                    </small>
                </div>

                <!-- Campaign Details -->
                <div class="ad-preview-card mb-4">
                    <h5 class="mb-3"><i class="fas fa-info-circle"></i> Campaign Details</h5>
                    
                    <div class="mb-3">
                        <strong>Ad Type</strong>
                        <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $ad->ad_type)) }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Budget</strong>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ min(100, ($ad->spent / $ad->budget) * 100) }}%">
                                {{ number_format(($ad->spent / $ad->budget) * 100, 1) }}%
                            </div>
                        </div>
                        <div class="d-flex justify-content-between small mt-1">
                            <span>₦{{ number_format($ad->spent, 2) }}</span>
                            <span>₦{{ number_format($ad->budget, 2) }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Duration</strong>
                        <p class="mb-0">
                            {{ $ad->start_date->format('M d, Y') }} - {{ $ad->end_date->format('M d, Y') }}
                        </p>
                        <small class="text-muted">
                            {{ $ad->getDaysRemaining() }} days remaining
                        </small>
                    </div>

                    <div class="mb-3">
                        <strong>Pricing</strong>
                        <p class="mb-0">₦{{ number_format($ad->cost_per_click, 2) }} per click</p>
                        <p class="mb-0">₦{{ number_format($ad->cost_per_impression, 4) }} per impression</p>
                    </div>

                    <div class="mb-3">
                        <strong>Targeting</strong>
                        @if($ad->target_countries)
                            <p class="mb-1">
                                <i class="fas fa-globe"></i> 
                                {{ implode(', ', is_array($ad->target_countries) ? $ad->target_countries : json_decode($ad->target_countries, true)) }}
                            </p>
                        @endif
                        @if($ad->target_gender)
                            <p class="mb-1">
                                <i class="fas fa-user"></i> 
                                {{ implode(', ', is_array($ad->target_gender) ? $ad->target_gender : json_decode($ad->target_gender, true)) }}
                            </p>
                        @endif
                        @if($ad->target_age_range)
                            @php
                                $ageRange = is_array($ad->target_age_range) ? $ad->target_age_range : json_decode($ad->target_age_range, true);
                            @endphp
                            <p class="mb-0">
                                <i class="fas fa-birthday-cake"></i> 
                                Ages {{ $ageRange['min'] ?? '13' }}-{{ $ageRange['max'] ?? '100+' }}
                            </p>
                        @endif
                    </div>

                    @if($ad->rejection_reason)
                        <div class="alert alert-danger">
                            <strong>Rejection Reason:</strong><br>
                            {{ $ad->rejection_reason }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <small class="text-muted">Created: {{ $ad->created_at->format('M d, Y H:i') }}</small>
                    </div>
                </div>

                <!-- Admin Actions -->
                <div class="ad-preview-card">
                    <h5 class="mb-3"><i class="fas fa-cog"></i> Actions</h5>
                    
                    @if($ad->status == 'pending')
                        <button class="btn btn-success w-100 mb-2" onclick="approveAd({{ $ad->id }})">
                            <i class="fas fa-check"></i> Approve Ad
                        </button>
                        <button class="btn btn-danger w-100 mb-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#rejectModal">
                            <i class="fas fa-times"></i> Reject Ad
                        </button>
                    @elseif($ad->status == 'active')
                        <button class="btn btn-warning w-100 mb-2" onclick="pauseAd({{ $ad->id }})">
                            <i class="fas fa-pause"></i> Pause Ad
                        </button>
                    @elseif($ad->status == 'paused')
                        <button class="btn btn-success w-100 mb-2" onclick="approveAd({{ $ad->id }})">
                            <i class="fas fa-play"></i> Resume Ad
                        </button>
                    @endif
                    
                    <button class="btn btn-outline-danger w-100" onclick="deleteAd({{ $ad->id }})">
                        <i class="fas fa-trash"></i> Delete Ad
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Advertisement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Why are you rejecting this ad?</p>
                    <textarea id="rejectReason" class="form-control" rows="3" 
                              placeholder="Enter rejection reason..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="rejectAd({{ $ad->id }})">
                        Reject Ad
                    </button>
                </div>
            </div>
        </div>
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

        // Sample performance chart (you can replace with real data)
        const ctx = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
                datasets: [{
                    label: 'Impressions',
                    data: [120, 190, 300, 250, 200, 300, 400],
                    borderColor: '#8A2BE2',
                    backgroundColor: 'rgba(138, 43, 226, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Clicks',
                    data: [12, 19, 30, 25, 20, 30, 40],
                    borderColor: '#FF6384',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

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
            const reason = document.getElementById('rejectReason').value;
            
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
                modal.hide();
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

        function deleteAd(adId) {
            if (confirm('Delete this ad permanently? This cannot be undone.')) {
                fetch(`/admin/advertising/${adId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    window.location.href = '{{ route("admin.advertising.index") }}';
                })
                .catch(err => {
                    alert('Failed to delete ad');
                    console.error(err);
                });
            }
        }
    </script>
    
    @endsection
</body>
</html>