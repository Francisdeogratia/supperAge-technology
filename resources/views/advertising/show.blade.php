<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $ad->title }} - Ad Manager</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
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
        .ad-detail-container {
            background: #f5f7fa;
            min-height: 100vh;
            padding: 20px 0;
        }

        .ad-hero {
            background: linear-gradient(135deg, #8A2BE2 0%, #9370DB 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .metric-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .metric-value {
            font-size: 36px;
            font-weight: bold;
            color: #8A2BE2;
        }

        .metric-label {
            color: #666;
            font-size: 14px;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        .info-badge {
            display: inline-block;
            padding: 8px 15px;
            background: #f0f0f0;
            border-radius: 20px;
            margin: 5px;
            font-size: 14px;
        }

        .info-badge.success {
            background: #d4edda;
            color: #155724;
        }

        .info-badge.warning {
            background: #fff3cd;
            color: #856404;
        }

        .daily-budget-alert {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
     @include('layouts.navbar')
    @extends('layouts.app')
    @section('content')

    <div class="ad-detail-container">
        <div class="container">
            <!-- Ad Hero -->
            <div class="ad-hero">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h2>{{ $ad->title }}</h2>
                        <p class="mb-0">Campaign ID: #{{ $ad->id }}</p>
                        
                        <!-- Payment Status Badge -->
                        @if($ad->payment_status === 'paid')
                            <span class="badge badge-success mt-2">
                                <i class="fas fa-check-circle"></i> Payment Verified
                            </span>
                        @elseif($ad->payment_status === 'test')
                            <span class="badge badge-info mt-2">
                                <i class="fas fa-flask"></i> Test Mode
                            </span>
                        @else
                            <span class="badge badge-warning mt-2">
                                <i class="fas fa-clock"></i> Payment Pending
                            </span>
                        @endif
                    </div>
                    <span class="badge badge-{{ $ad->status === 'active' ? 'success' : ($ad->status === 'pending' ? 'warning' : 'secondary') }} badge-lg" style="font-size: 1.2rem; padding: 10px 20px;">
                        {{ ucfirst($ad->status) }}
                    </span>
                </div>
            </div>

            <!-- ✅ Daily Budget Alert (if set) -->
            @if($ad->daily_budget)
                @php
                    $todaySpent = \App\Models\AdImpression::where('ad_id', $ad->id)
                        ->whereDate('viewed_at', today())
                        ->count() * $ad->cost_per_impression;
                    $dailyPercentage = ($todaySpent / $ad->daily_budget) * 100;
                @endphp
                
                <div class="daily-budget-alert">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1"><i class="fas fa-calendar-day"></i> Daily Budget Tracking</h5>
                            <p class="mb-0">Today's spending: ₦{{ number_format($todaySpent, 2) }} / ₦{{ number_format($ad->daily_budget, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <h3 class="mb-0">{{ number_format($dailyPercentage, 1) }}%</h3>
                            <small>of daily limit</small>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 8px; background: rgba(255,255,255,0.3);">
                        <div class="progress-bar bg-white" style="width: {{ min(100, $dailyPercentage) }}%"></div>
                    </div>
                    
                    @if($dailyPercentage >= 100)
                        <div class="alert alert-warning mt-2 mb-0">
                            <i class="fas fa-pause-circle"></i> Daily budget reached. Ad will resume tomorrow.
                        </div>
                    @elseif($dailyPercentage >= 80)
                        <div class="alert alert-info mt-2 mb-0">
                            <i class="fas fa-exclamation-triangle"></i> Approaching daily limit. {{ number_format($ad->daily_budget - $todaySpent, 2) }} remaining today.
                        </div>
                    @endif
                </div>
            @endif

            <!-- Key Metrics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-value">{{ number_format($ad->impressions) }}</div>
                        <div class="metric-label">Total Impressions</div>
                        <small class="text-muted">₦{{ number_format($ad->impressions * $ad->cost_per_impression, 2) }}</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-value">{{ number_format($ad->clicks) }}</div>
                        <div class="metric-label">Total Clicks</div>
                        <small class="text-muted">₦{{ number_format($ad->clicks * $ad->cost_per_click, 2) }}</small>
                    </div>
                </div>

                <!-- Add to show.blade.php after clicks metric -->
<div class="col-md-3">
    <div class="metric-card">
        <div class="metric-value">{{ number_format($ad->actions) }}</div>
        <div class="metric-label">Total Actions</div>
        <small class="text-muted">₦{{ number_format($ad->actions * $ad->cost_per_action, 2) }}</small>
    </div>
</div>

<!-- Add conversion rate metric -->
<div class="col-md-3">
    <div class="metric-card">
        <div class="metric-value">{{ $ad->getConversionRatecpa() }}%</div>
        <div class="metric-label">Conversion Rate</div>
        <small class="text-muted">Actions / Clicks</small>
    </div>
</div>



                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-value">{{ $ad->getCTR() }}%</div>
                        <div class="metric-label">Click-Through Rate</div>
                        <small class="text-muted">Industry avg: 2%</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-value">₦{{ number_format($ad->spent, 2) }}</div>
                        <div class="metric-label">Total Spent</div>
                        <small class="text-muted">{{ number_format(($ad->spent / $ad->budget) * 100, 1) }}% of budget</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Performance Chart -->
                    <div class="content-card">
                        <h5 class="mb-3"><i class="fas fa-chart-line"></i> Performance (Last 7 Days)</h5>
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>

                    <!-- Ad Preview -->
                    <div class="content-card">
                        <h5 class="mb-3"><i class="fas fa-eye"></i> Ad Preview</h5>
                        <div class="text-center">
                            @if($ad->media_url)
                                @if($ad->media_type === 'image')
                                    <img src="{{ $ad->media_url }}" class="img-fluid rounded" style="max-height: 400px;">
                                @else
                                    <video src="{{ $ad->media_url }}" controls class="img-fluid rounded" style="max-height: 400px;"></video>
                                @endif
                            @endif
                        </div>
                        <div class="mt-3">
                            <h5>{{ $ad->title }}</h5>
                            <p>{{ $ad->description }}</p>
                            <a href="{{ $ad->cta_link }}" target="_blank" class="btn btn-primary">
                                {{ $ad->cta_text }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Campaign Details -->
                    <div class="content-card">
                        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Campaign Details</h5>
                        
                        <div class="mb-3">
                            <strong>Duration</strong>
                            <p class="mb-0">{{ $ad->start_date->format('M d, Y') }} - {{ $ad->end_date->format('M d, Y') }}</p>
                            <small class="text-muted">
                                @if($ad->getDaysRemaining() > 0)
                                    <i class="fas fa-clock"></i> {{ $ad->getDaysRemaining() }} days remaining
                                @else
                                    <i class="fas fa-check"></i> Campaign ended
                                @endif
                            </small>
                        </div>

                        <!-- ✅ Budget Section with Daily Budget -->
                        <div class="mb-3">
                            <strong>Total Campaign Budget</strong>
                            <div class="progress mt-2" style="height: 25px;">
                                <div class="progress-bar bg-success" style="width: {{ min(100, ($ad->spent / $ad->budget) * 100) }}%">
                                    {{ number_format(($ad->spent / $ad->budget) * 100, 1) }}%
                                </div>
                            </div>
                            <div class="d-flex justify-content-between small mt-1">
                                <span>₦{{ number_format($ad->spent, 2) }} spent</span>
                                <span>₦{{ number_format($ad->budget, 2) }} total</span>
                            </div>
                            
                            <!-- ✅ Daily Budget Display -->
                            @if($ad->daily_budget)
                                <div class="mt-2 p-2" style="background: #f8f9fa; border-radius: 8px;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted d-block">Daily Budget Cap</small>
                                            <strong class="text-primary">₦{{ number_format($ad->daily_budget, 2) }}/day</strong>
                                        </div>
                                        <i class="fas fa-calendar-day fa-2x text-muted"></i>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> Ad pauses when daily limit reached
                                    </small>
                                </div>
                            @else
                                <div class="mt-2">
                                    <span class="info-badge">
                                        <i class="fas fa-infinity"></i> No daily limit
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- ✅ Pricing Breakdown -->
                        <div class="mb-3">
                            <strong>Pricing Model</strong>
                            <div class="mt-2">
                                <div class="info-badge">
                                    💰 CPC: ₦{{ number_format($ad->cost_per_click, 2) }}
                                </div>
                                <div class="info-badge">
                                    👁️ CPM: ₦{{ number_format($ad->cost_per_mille, 2) }}
                                </div>
                                <div class="info-badge">
                                    🎯 CPA: ₦{{ number_format($ad->cost_per_action, 2) }}
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Ad Type</strong>
                            <p class="mb-0">
                                <span class="info-badge success">
                                    {{ ucfirst(str_replace('_', ' ', $ad->ad_type)) }}
                                </span>
                            </p>
                        </div>

                        <!-- ✅ Payment Info -->
                        @if($ad->payment_status === 'paid' && $ad->paid_at)
                            <div class="mb-3">
                                <strong>Payment Details</strong>
                                <p class="mb-1"><small class="text-muted">Paid: {{ $ad->paid_at->format('M d, Y H:i') }}</small></p>
                                <p class="mb-0"><small class="text-muted">Reference: {{ $ad->payment_reference }}</small></p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <strong>Targeting</strong>
                            <div class="mt-2">
                                @if($ad->target_countries)
                                    <div class="info-badge">
                                        <i class="fas fa-globe"></i> {{ implode(', ', is_array($ad->target_countries) ? $ad->target_countries : json_decode($ad->target_countries, true)) }}
                                    </div>
                                @endif
                                @if($ad->target_gender)
                                    <div class="info-badge">
                                        <i class="fas fa-user"></i> {{ implode(', ', is_array($ad->target_gender) ? $ad->target_gender : json_decode($ad->target_gender, true)) }}
                                    </div>
                                @endif
                                @if($ad->target_age_range)
                                    @php
                                        $ageRange = is_array($ad->target_age_range) ? $ad->target_age_range : json_decode($ad->target_age_range, true);
                                    @endphp
                                    <div class="info-badge">
                                        <i class="fas fa-birthday-cake"></i> Ages {{ $ageRange['min'] ?? '13' }}-{{ $ageRange['max'] ?? '100+' }}
                                    </div>
                                @endif
                                @if($ad->target_interests)
                                    <div class="mt-2">
                                        <small class="text-muted d-block mb-1">Interests:</small>
                                        @foreach(is_array($ad->target_interests) ? $ad->target_interests : json_decode($ad->target_interests, true) as $interest)
                                            <span class="info-badge">{{ ucfirst($interest) }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="d-grid gap-2">
                            <a href="{{ route('advertising.index') }}" class="btn btn-secondary btn-block mb-2">
                                <i class="fas fa-arrow-left"></i> Back to Campaigns
                            </a>
                            @if($ad->status === 'active')
                                <button class="btn btn-warning btn-block mb-2" onclick="pauseAd()">
                                    <i class="fas fa-pause"></i> Pause Campaign
                                </button>
                            @elseif($ad->status === 'paused')
                                <button class="btn btn-success btn-block mb-2" onclick="resumeAd()">
                                    <i class="fas fa-play"></i> Resume Campaign
                                </button>
                            @endif
                            <button class="btn btn-danger btn-block" onclick="deleteAd()">
                                <i class="fas fa-trash"></i> Delete Campaign
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('myjs/bar.js') }}"></script>
    <script src="{{ asset('myjs/more_lesstext.js') }}"></script>
    <script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
    <script src="{{ asset('myjs/allpost.js') }}"></script>
    <script src="{{ asset('myjs/tales.js') }}"></script>
    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';
        const adId = {{ $ad->id }};

        // Performance Chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($performanceData['dates']),
                datasets: [{
                    label: 'Impressions',
                    data: @json($performanceData['impressions']),
                    borderColor: '#8A2BE2',
                    backgroundColor: 'rgba(138, 43, 226, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Clicks',
                    data: @json($performanceData['clicks']),
                    borderColor: '#FF6384',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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

        function pauseAd() {
            if (confirm('Pause this campaign? You can resume it later.')) {
                $.ajax({
                    url: `/advertising/${adId}`,
                    method: 'PUT',
                    data: {
                        _token: csrfToken,
                        status: 'paused'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('✅ Campaign paused successfully');
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('❌ Failed to pause campaign');
                    }
                });
            }
        }

        function resumeAd() {
            if (confirm('Resume this campaign?')) {
                $.ajax({
                    url: `/advertising/${adId}`,
                    method: 'PUT',
                    data: {
                        _token: csrfToken,
                        status: 'active'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('✅ Campaign resumed successfully');
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('❌ Failed to resume campaign');
                    }
                });
            }
        }

        function deleteAd() {
            if (confirm('Delete this campaign? This cannot be undone.')) {
                $.ajax({
                    url: `/advertising/${adId}`,
                    method: 'DELETE',
                    data: { _token: csrfToken },
                    success: function(response) {
                        if (response.success) {
                            alert('✅ Campaign deleted successfully');
                            window.location.href = '{{ route("advertising.index") }}';
                        }
                    },
                    error: function() {
                        alert('❌ Failed to delete campaign');
                    }
                });
            }
        }
    </script>

    <?php
// ============================================
// STEP 9: Generate Tracking Code for Advertisers
// ============================================
?>

<!-- Add this section to show.blade.php -->
<div class="content-card">
    <h5 class="mb-3"><i class="fas fa-code"></i> Conversion Tracking Code</h5>
    <p>Add this code to your website to track conversions (signups, purchases, downloads, etc.):</p>
    
    <!-- Step 1: Installation -->
    <div class="alert alert-info">
        <small><strong>Step 1:</strong> Add this script to your website's &lt;head&gt; section or before closing &lt;/body&gt; tag:</small>
    </div>
    
    <pre style="background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 8px; overflow-x: auto;"><code>&lt;!-- Supperage Conversion Tracking --&gt;
&lt;script src="{{ url('/js/supperage-tracking.js') }}"&gt;&lt;/script&gt;
&lt;script&gt;
    SupperageTracking.init('{{ $ad->id }}');
&lt;/script&gt;</code></pre>
    
    <button class="btn btn-sm btn-primary mb-3" onclick="copyInstallCode()">
        <i class="fas fa-copy"></i> Copy Installation Code
    </button>
    
    <!-- Step 2: Usage Examples -->
    <div class="alert alert-info mt-3">
        <small><strong>Step 2:</strong> Track conversions when actions happen on your website:</small>
    </div>
    
    <div class="accordion" id="trackingExamples">
        <!-- Signup Example -->
        <div class="card">
            <div class="card-header" id="signupExample">
                <h6 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseSignup">
                        ✅ Track Signups
                    </button>
                </h6>
            </div>
            <div id="collapseSignup" class="collapse show" data-parent="#trackingExamples">
                <div class="card-body">
<pre style="background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 8px; overflow-x: auto;"><code>// After user successfully signs up
SupperageTracking.trackSignup({
    email: 'user@example.com',
    plan: 'free'
});

// Or use the basic track method
SupperageTracking.track('signup');</code></pre>
                </div>
            </div>
        </div>
        
        <!-- Purchase Example -->
        <div class="card">
            <div class="card-header" id="purchaseExample">
                <h6 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsePurchase">
                        💰 Track Purchases
                    </button>
                </h6>
            </div>
            <div id="collapsePurchase" class="collapse" data-parent="#trackingExamples">
                <div class="card-body">
<pre style="background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 8px; overflow-x: auto;"><code>// After successful payment
SupperageTracking.trackPurchase(5000, {
    orderId: 'ORD-12345',
    product: 'Premium Plan',
    currency: 'NGN'
});

// Track with value
SupperageTracking.track('purchase', 5000);</code></pre>
                </div>
            </div>
        </div>
        
        <!-- Download Example -->
        <div class="card">
            <div class="card-header" id="downloadExample">
                <h6 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseDownload">
                        📥 Track Downloads
                    </button>
                </h6>
            </div>
            <div id="collapseDownload" class="collapse" data-parent="#trackingExamples">
                <div class="card-body">
<pre style="background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 8px; overflow-x: auto;"><code>// When user downloads a file
document.getElementById('downloadBtn').addEventListener('click', function() {
    SupperageTracking.trackDownload({
        fileName: 'ebook.pdf',
        fileType: 'pdf'
    });
});

// Simple version
SupperageTracking.track('download');</code></pre>
                </div>
            </div>
        </div>
        
        <!-- Form Submit Example -->
        <div class="card">
            <div class="card-header" id="formExample">
                <h6 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseForm">
                        📝 Track Form Submissions
                    </button>
                </h6>
            </div>
            <div id="collapseForm" class="collapse" data-parent="#trackingExamples">
                <div class="card-body">
<pre style="background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 8px; overflow-x: auto;"><code>// After form submission
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Submit your form first, then track
    SupperageTracking.trackFormSubmit({
        formName: 'Contact Form',
        formType: 'lead'
    });
});

// Simple version
SupperageTracking.track('form_submit');</code></pre>
                </div>
            </div>
        </div>
        
        <!-- Other Actions -->
        <div class="card">
            <div class="card-header" id="otherExample">
                <h6 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOther">
                        🎯 Other Actions
                    </button>
                </h6>
            </div>
            <div id="collapseOther" class="collapse" data-parent="#trackingExamples">
                <div class="card-body">
<pre style="background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 8px; overflow-x: auto;"><code>// Track contact action
SupperageTracking.trackContact();

// Track lead generation
SupperageTracking.trackLead();

// Track trial signup
SupperageTracking.trackTrial();

// Custom action
SupperageTracking.track('other', 0, {
    customField: 'value'
});</code></pre>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Types Reference -->
    <div class="alert alert-secondary mt-3">
        <strong>Available Action Types:</strong><br>
        <small>
            <code>signup</code>, <code>purchase</code>, <code>download</code>, 
            <code>form_submit</code>, <code>contact</code>, <code>lead</code>, 
            <code>trial</code>, <code>other</code>
        </small>
    </div>
    
    <!-- Testing -->
    <div class="alert alert-warning mt-3">
        <strong>⚠️ Important:</strong> Test your tracking before going live!
        <ul class="mb-0 mt-2">
            <li>Check browser console for tracking confirmations</li>
            <li>Verify actions appear in your campaign dashboard</li>
            <li>Make sure the script loads on all pages where you want to track</li>
        </ul>
    </div>
</div>

<script>
function copyInstallCode() {
    const code = `<!-- Supperage Conversion Tracking -->
<script src="{{ url('/js/supperage-tracking.js') }}"><\/script>
<script>
    SupperageTracking.init('{{ $ad->id }}');
<\/script>`;
    
    navigator.clipboard.writeText(code).then(function() {
        alert('✅ Installation code copied! Paste it in your website\'s HTML.');
    }, function() {
        alert('❌ Copy failed. Please copy manually.');
    });
}
</script>


<a href="{{ route('integration.guide') }}" class="btn btn-outline-primary">
    <i class="fas fa-book"></i> View Integration Guide
</a>

<li class="nav-item">
    <a class="nav-link" href="{{ route('integration.guide') }}">
        <i class="fas fa-terminal mr-2"></i> <span>Developer API</span>
    </a>
</li>




    @endsection
</body>
</html>