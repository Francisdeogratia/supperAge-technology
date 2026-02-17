<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Advertisement - Supperage</title>
    
   
    

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Flutterwave Inline JS -->
<script src="https://checkout.flutterwave.com/v3.js"></script>

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
    .ad-create-container {
        background: #f5f7fa;
        min-height: 100vh;
        padding: 30px 0;
    }

    .ad-header {
        background: linear-gradient(135deg, #8A2BE2 0%, #9370DB 100%);
        color: white;
        padding: 40px 20px;
        margin-bottom: 30px;
        border-radius: 15px;
        text-align: center;
    }

    .form-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .form-section {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 2px solid #f0f0f0;
    }

    .section-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #8A2BE2;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .targeting-option {
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
        display: block;
    }

    .targeting-option:hover {
        border-color: #8A2BE2;
        background: #f8f9ff;
    }

    .targeting-option input[type="checkbox"] {
        margin-right: 8px;
    }

    .payment-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.7);
    }

    .payment-modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 0;
        border-radius: 20px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }

    .payment-option-card {
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .payment-option-card:hover {
        border-color: #8A2BE2;
        background: #f8f9ff;
        transform: translateY(-2px);
    }

    .wallet-balance {
        background: #f0f0f0;
        padding: 10px;
        border-radius: 8px;
        margin: 10px 0;
    }

    .currency-select {
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        margin: 10px 0;
    }

    .ad-preview-box {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        position: sticky;
        top: 20px;
    }

    .preview-ad {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px;
        background: #f9f9f9;
    }

    .preview-image {
        width: 100%;
        height: 200px;
        background: #e0e0e0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .estimation-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 20px;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding: 10px;
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
    }
</style>

 </head>
<body>
 @include('layouts.navbar')
    @extends('layouts.app')

@section('content')
<div class="ad-create-container">
    <div class="container">
        <div class="ad-header">
            <h1><i class="fas fa-ad"></i> Create Advertisement</h1>
            <p>Reach thousands of Supperage users with your ad</p>
        </div>

        <div class="row">
            <!-- Form Column -->
            <div class="col-lg-8">
                <div class="form-container">
                    <form id="createAdForm">
                        @csrf

                        <!-- Ad Content Section -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-paint-brush"></i> Ad Content
                            </h3>

                            <div class="form-group">
                                <label>Ad Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="adTitle" 
                                       placeholder="e.g., Get 50% Off Our Premium Service" 
                                       maxlength="200" required>
                            </div>

                            <div class="form-group">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" id="adDescription" 
                                          rows="4" placeholder="Tell people about your offer..." 
                                          maxlength="1000" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ad Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="ad_type" required>
                                            <option value="banner">Banner Ad</option>
                                            <option value="sponsored_post">Sponsored Post</option>
                                            <option value="video">Video Ad</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Media Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="media_type" id="mediaType" required>
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Replace the entire Ad Media section with this -->

<div class="form-group">
    <label>Ad Media <span class="text-danger">*</span></label>
    <input type="file" id="mediaInput" accept="image/*,video/*" style="display: none;">
    <input type="hidden" name="media_url" id="mediaUrl" required>
    
    <!-- Upload Area -->
    <div class="media-upload-area" id="uploadArea" onclick="document.getElementById('mediaInput').click()" 
         style="border: 2px dashed #8A2BE2; border-radius: 15px; padding: 40px; text-align: center; cursor: pointer;">
        <i class="fas fa-cloud-upload-alt fa-3x" style="color: #8A2BE2;"></i>
        <h5 class="mt-3">Click to upload</h5>
        <p class="text-muted mb-2">Image or Video (Max 10MB)</p>
        <small class="text-muted">Supported: JPG, PNG, GIF, MP4, MOV, AVI</small>
    </div>
    
    <!-- Preview Container (Initially Hidden) -->
    <div id="previewContainer" style="display: none; margin-top: 15px; position: relative;">
        <!-- Image Preview -->
        <img id="mediaPreview" class="media-preview" 
             style="max-width: 100%; max-height: 300px; border-radius: 15px; display: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        
        <!-- Video Preview -->
        <video id="videoPreview" class="media-preview" controls
               style="max-width: 100%; max-height: 300px; border-radius: 15px; display: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <source id="videoSource" src="" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        
        <!-- Change Media Button -->
        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="document.getElementById('mediaInput').click()">
            <i class="fas fa-sync-alt"></i> Change Media
        </button>
    </div>
</div>

<style>
    .media-upload-area:hover {
        background: #f8f9ff;
        border-color: #6a1bb2;
    }
    
    .media-preview {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    #uploadArea.uploaded {
        border-color: #28a745;
        background: #f0fff4;
    }
</style>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Call-to-Action Text <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="cta_text" id="ctaText" 
                                               value="Learn More" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Destination URL <span class="text-danger">*</span></label>
                                        <input type="url" class="form-control" name="cta_link" id="ctaLink"
                                               placeholder="https://yourwebsite.com" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Targeting Section -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-bullseye"></i> Targeting
                            </h3>

                            <div class="form-group">
                                <label>Target Countries</label>
                                <div class="row">
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_countries[]" value="NG"> 🇳🇬 Nigeria</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_countries[]" value="US"> 🇺🇸 United States</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_countries[]" value="GB"> 🇬🇧 United Kingdom</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_countries[]" value="CA"> 🇨🇦 Canada</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_countries[]" value="GH"> 🇬🇭 Ghana</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_countries[]" value="KE"> 🇰🇪 Kenya</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_countries[]" value="ZA"> 🇿🇦 South Africa</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_countries[]" value="IN"> 🇮🇳 India</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_countries[]" value="AU"> 🇦🇺 Australia</label></div>
                                </div>
                                <small class="text-muted">Leave empty to target all countries</small>
                            </div>

                            <div class="form-group">
                                <label>Target Gender</label>
                                <div class="row">
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_gender[]" value="male"> <i class="fas fa-mars text-primary"></i> Male</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_gender[]" value="female"> <i class="fas fa-venus text-danger"></i> Female</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_gender[]" value="other"> <i class="fas fa-genderless text-success"></i> Other</label></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Target Age Range</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" name="min_age" class="form-control" placeholder="Min Age" min="13" max="100">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="max_age" class="form-control" placeholder="Max Age" min="13" max="100">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Target Interests</label>
                                <div class="row">
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_interests[]" value="technology"> 💻 Technology</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_interests[]" value="business"> 💼 Business</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_interests[]" value="fashion"> 👗 Fashion</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_interests[]" value="sports"> ⚽ Sports</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_interests[]" value="education"> 📚 Education</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_interests[]" value="entertainment"> 🎬 Entertainment</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_interests[]" value="health"> 💪 Health & Fitness</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_interests[]" value="food"> 🍔 Food & Dining</label></div>
                                    <div class="col-md-4"><label class="targeting-option"><input type="checkbox" name="target_interests[]" value="travel"> ✈️ Travel</label></div>
                                </div>
                            </div>
                        </div>

                        <!-- Budget & Schedule -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-money-bill-wave"></i> Budget & Schedule
                            </h3>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total Campaign Budget (NGN) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="budget" id="budget" 
                                               placeholder="2500" min="2500" step="100" required>
                                        <small class="text-muted">Minimum ₦2,500</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Daily Budget (Optional)</label>
                                        <input type="number" class="form-control" name="daily_budget" id="dailyBudget" 
                                               placeholder="1000" min="100" step="100">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Start Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="start_date" id="startDate" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>End Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="end_date" id="endDate" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="text-center">
                            <a href="{{ route('advertising.index') }}" class="btn btn-secondary btn-lg mr-3">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="button" class="btn btn-success btn-lg" id="showPaymentModal">
                                <i class="fas fa-credit-card"></i> Proceed to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview & Estimation Column -->
            <div class="col-lg-4">
                <!-- Ad Preview -->
                <div class="ad-preview-box">
                    <h5 class="mb-3"><i class="fas fa-eye"></i> Ad Preview</h5>
                    <div class="preview-ad">
                        <div class="preview-image" id="previewImageBox">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        <h6 id="previewTitle">Your Ad Title</h6>
                        <p class="text-muted small" id="previewDescription">Your ad description will appear here...</p>
                        <button type="button" class="btn btn-primary btn-sm btn-block" id="previewCTA">Learn More</button>
                    </div>
                </div>

                <!-- Pricing Info -->
                <div class="estimation-box">
                    <h5 class="mb-3"><i class="fas fa-tags"></i> Pricing Model</h5>
                    <div class="stat-item">
                        <span>Cost Per Click (CPC)</span>
                        <strong>₦50</strong>
                    </div>
                    <div class="stat-item">
                        <span>Cost Per Impression (CPM)</span>
                        <strong>₦2.50</strong>
                    </div>
                    <div class="stat-item">
                        <span>Cost Per Action (CPA)</span>
                        <strong>₦400</strong>
                    </div>
                    <div class="stat-item">
                        <span>Cost Per 1000 Views</span>
                        <strong>₦2,500</strong>
                    </div>
                </div>

                <!-- Estimation -->
                <div class="ad-preview-box">
                    <h5 class="mb-3"><i class="fas fa-calculator"></i> Estimated Reach</h5>
                    <div class="stat-item" style="background: #f0f0f0; color: #333;">
                        <span>Estimated Impressions</span>
                        <strong id="estimatedImpressions">0</strong>
                    </div>
                    <div class="stat-item" style="background: #f0f0f0; color: #333;">
                        <span>Estimated Clicks</span>
                        <strong id="estimatedClicks">0</strong>
                    </div>
                    <div class="stat-item" style="background: #f0f0f0; color: #333;">
                        <span>Campaign Duration</span>
                        <strong id="campaignDuration">0 days</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="payment-modal">
    <div class="payment-modal-content">
        <div style="background: linear-gradient(135deg, #8A2BE2 0%, #9370DB 100%); color: white; padding: 20px; border-radius: 20px 20px 0 0;">
            <h4 class="mb-0"><i class="fas fa-credit-card"></i> Choose Payment Method</h4>
        </div>
        
        <div style="padding: 30px;">
            <!-- Wallet Payment -->
            <div class="payment-option-card" onclick="selectPaymentMethod('wallet')">
                <h5><i class="fas fa-wallet text-success"></i> Pay from Wallet</h5>
                <p class="text-muted mb-3">Use your account balance</p>
                
                <div id="walletBalances">
                    @if(isset($balances) && count($balances) > 0)
                        @foreach($balances as $currency => $amount)
                            <div class="wallet-balance">
                                <strong>{{ $currency }}:</strong> {{ number_format($amount, 2) }}
                                @if(isset($currencies[$currency]))
                                    <small class="text-muted">({{ $currencies[$currency] }})</small>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No wallet balance</p>
                    @endif
                </div>
                
                <div id="walletCurrencySelect" style="display:none; margin-top: 15px;">
                    <label>Select Currency:</label>
                    <select class="form-control currency-select" id="walletCurrency" onchange="updateWalletAmount()">
                        @if(isset($balances) && count($balances) > 0)
                            @foreach($balances as $currency => $amount)
                                <option value="{{ $currency }}">
                                    {{ $currency }} - {{ $currencies[$currency] ?? $currency }} 
                                    (Balance: {{ number_format($amount, 2) }})
                                </option>
                            @endforeach
                        @else
                            <option value="NGN">No balance available</option>
                        @endif
                    </select>
                    <div id="walletAmountDisplay" class="mt-3 text-center">
                        <strong>Amount to pay:</strong> <span id="walletAmount">Calculating...</span>
                    </div>
                    <button type="button" class="btn btn-success btn-block mt-3" onclick="processWalletPayment()">
                        <i class="fas fa-check"></i> Confirm Payment
                    </button>
                </div>
            </div>

            <!-- Card/Bank Payment -->
            <div class="payment-option-card" onclick="selectPaymentMethod('card')">
                <h5><i class="fas fa-credit-card text-primary"></i> Pay with Card/Bank</h5>
                <p class="text-muted mb-0">Secure payment via Flutterwave</p>
                
                <div id="cardCurrencySelect" style="display:none; margin-top: 15px;">
                    <label>Select Currency:</label>
                    <select class="form-control currency-select" id="cardCurrency" onchange="updateCardAmount()">
                        <option value="NGN">🇳🇬 Nigerian Naira (NGN)</option>
                        <option value="USD">🇺🇸 US Dollar (USD)</option>
                        <option value="GBP">🇬🇧 British Pound (GBP)</option>
                        <option value="EUR">🇪🇺 Euro (EUR)</option>
                        <option value="GHS">🇬🇭 Ghanaian Cedi (GHS)</option>
                        <option value="KES">🇰🇪 Kenyan Shilling (KES)</option>
                        <option value="ZAR">🇿🇦 South African Rand (ZAR)</option>
                        <option value="TZS">🇹🇿 Tanzanian Shilling (TZS)</option>
                        <option value="UGX">🇺🇬 Ugandan Shilling (UGX)</option>
                    </select>
                    <div id="cardAmountDisplay" class="mt-3 text-center">
                        <strong>Amount to pay:</strong> <span id="cardAmount">Calculating...</span>
                    </div>
                    <button type="button" class="btn btn-primary btn-block mt-3" onclick="initiateCardPayment()">
                        <i class="fas fa-credit-card"></i> Pay Now
                    </button>
                </div>
            </div>

            <button type="button" class="btn btn-secondary btn-block mt-3" onclick="closePaymentModal()">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

    <script src="{{ asset('myjs/bar.js') }}"></script>
    <script src="{{ asset('myjs/more_lesstext.js') }}"></script>
    <script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
    <script src="{{ asset('myjs/allpost.js') }}"></script>
    <script src="{{ asset('myjs/tales.js') }}"></script>
    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script>
<script>
    // Replace the JavaScript section in your blade file with this fixed version

// Complete Fixed JavaScript - Replace entire <script> section

const csrfToken = '{{ csrf_token() }}';
let selectedPaymentMethod = null;

$(document).ready(function() {
    const today = new Date().toISOString().split('T')[0];
    $('#startDate').attr('min', today);
    $('#endDate').attr('min', today);

    // Live preview updates
    $('#adTitle').on('input keyup', function() {
        const val = $(this).val().trim();
        $('#previewTitle').text(val || 'Your Ad Title');
    });

    $('#adDescription').on('input keyup', function() {
        const val = $(this).val().trim();
        $('#previewDescription').text(val || 'Your ad description will appear here...');
    });

    $('#ctaText').on('input keyup', function() {
        const val = $(this).val().trim();
        $('#previewCTA').text(val || 'Learn More');
    });

    // FIXED: Handle both image and video preview with proper display
    $('#mediaInput').on('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // Validate file size (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            alert('⚠ File size must be less than 10MB');
            $(this).val('');
            return;
        }

        const reader = new FileReader();
        const isVideo = file.type.startsWith('video/');
        
        reader.onload = function(e) {
            // Show preview container
            $('#previewContainer').show();
            
            if (isVideo) {
                // Hide image, show video in preview area
                $('#mediaPreview').hide();
                $('#videoPreview').show();
                $('#videoSource').attr('src', e.target.result);
                $('#videoSource').attr('type', file.type);
                $('#videoPreview')[0].load();
                
                // Update ad preview box with video
                $('#previewImageBox').html(`
                    <video style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;" 
                           controls autoplay muted loop>
                        <source src="${e.target.result}" type="${file.type}">
                    </video>
                `);
            } else {
                // Hide video, show image in preview area
                $('#videoPreview').hide();
                $('#mediaPreview').attr('src', e.target.result).show();
                
                // Update ad preview box with image
                $('#previewImageBox').html(`
                    <img src="${e.target.result}" 
                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                `);
            }
        };
        
        reader.readAsDataURL(file);
        uploadMedia(file);
    });

    // Budget change - update estimations
    $('#budget, #startDate, #endDate').on('change input', updateEstimations);
});

function updateEstimations() {
    const budget = parseFloat($('#budget').val()) || 0;
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        // FIXED: Correct day calculation
        const days = Math.max(1, Math.ceil((end - start) / (1000 * 60 * 60 * 24)));
        $('#campaignDuration').text(days + ' day' + (days > 1 ? 's' : ''));
    } else {
        $('#campaignDuration').text('0 days');
    }

    // Estimate impressions (budget / cost per impression)
    const estimatedImpressions = Math.floor(budget / 2.5);
    $('#estimatedImpressions').text(estimatedImpressions.toLocaleString());

    // Estimate clicks (assuming 2% CTR)
    const estimatedClicks = Math.floor(estimatedImpressions * 0.02);
    $('#estimatedClicks').text(estimatedClicks.toLocaleString());
}

function uploadMedia(file) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('upload_preset', 'francis');

    // Update upload area to show uploading
    $('#uploadArea').html(`
        <i class="fas fa-spinner fa-spin fa-2x" style="color: #8A2BE2;"></i>
        <p class="mt-3 mb-0">Uploading ${file.type.startsWith('video/') ? 'video' : 'image'}...</p>
        <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
    `);

    const uploadUrl = file.type.includes('video') 
        ? 'https://api.cloudinary.com/v1_1/djaqqrwoi/video/upload' 
        : 'https://api.cloudinary.com/v1_1/djaqqrwoi/image/upload';

    fetch(uploadUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.secure_url) {
            $('#mediaUrl').val(data.secure_url);
            
            // Show success in upload area
            $('#uploadArea').html(`
                <i class="fas fa-check-circle fa-3x text-success"></i>
                <h5 class="mt-3 text-success">✓ Upload Complete!</h5>
                <p class="text-muted mb-0">Your ${file.type.startsWith('video/') ? 'video' : 'image'} is ready</p>
                <small class="text-muted">Click to upload a different file</small>
            `).addClass('uploaded');
            
            console.log('✓ Upload successful:', data.secure_url);
        } else {
            throw new Error('No URL returned');
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        alert('⚠ Upload failed. Please try again or use a smaller file.');
        
        // Reset upload area
        $('#uploadArea').html(`
            <i class="fas fa-cloud-upload-alt fa-3x" style="color: #8A2BE2;"></i>
            <h5 class="mt-3">Click to upload</h5>
            <p class="text-muted mb-2">Image or Video (Max 10MB)</p>
            <small class="text-muted">Supported: JPG, PNG, GIF, MP4, MOV, AVI</small>
        `).removeClass('uploaded');
        
        // Clear previews
        $('#previewContainer').hide();
        $('#mediaPreview').hide();
        $('#videoPreview').hide();
        $('#mediaUrl').val('');
    });
}

$('#showPaymentModal').click(function() {
    // Validate form
    if (!$('#createAdForm')[0].checkValidity()) {
        $('#createAdForm')[0].reportValidity();
        return;
    }
    
    // Ensure media is uploaded
    if (!$('#mediaUrl').val()) {
        alert('⚠ Please wait for media upload to complete or upload a file.');
        return;
    }
    
    $('#paymentModal').show();
});

function closePaymentModal() {
    $('#paymentModal').hide();
    selectedPaymentMethod = null;
    $('#walletCurrencySelect').hide();
    $('#cardCurrencySelect').hide();
}

// Also update the selectPaymentMethod to show more info
function selectPaymentMethod(method) {
    selectedPaymentMethod = method;
    
    console.log('💳 Payment method selected:', method);
    
    if (method === 'wallet') {
        $('#walletCurrencySelect').show();
        $('#cardCurrencySelect').hide();
        
        // Show initial currency info
        const currency = $('#walletCurrency').val();
        console.log('Selected currency:', currency);
        
        updateWalletAmount();
    } else {
        $('#cardCurrencySelect').show();
        $('#walletCurrencySelect').hide();
        updateCardAmount();
    }
}
// FIXED: Conversion with fallback rates
function updateWalletAmount() {
    const currency = $('#walletCurrency').val();
    const budgetNGN = parseFloat($('#budget').val()) || 2500;
    
    $('#walletAmount').html('<i class="fas fa-spinner fa-spin"></i> Calculating...');
    
    // If NGN, show immediately
    if (currency === 'NGN') {
        $('#walletAmount').text('₦' + budgetNGN.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        return;
    }
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 15000);
    
    fetch(`{{ route('advertising.conversion-rate') }}?currency=${currency}&amount=${budgetNGN}`, {
        signal: controller.signal,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        clearTimeout(timeoutId);
        if (!response.ok) throw new Error('API Error');
        return response.json();
    })
    .then(data => {
        console.log('Conversion response:', data);
        
        if (data.success) {
            const symbols = {
                'NGN': '₦', 'USD': '$', 'GBP': '£', 'EUR': '€', 
                'GHS': '₵', 'KES': 'KSh', 'ZAR': 'R', 'TZS': 'TSh',
                'UGX': 'USh', 'XAF': 'FCFA', 'XOF': 'CFA'
            };
            
            const symbol = symbols[currency] || (currency + ' ');
            const amount = parseFloat(data.convertedAmount).toFixed(2);
            
            $('#walletAmount').html(`
                ${symbol}${parseFloat(amount).toLocaleString()}
                ${data.using_fallback ? '<br><small class="text-warning">⚠ Estimated rate</small>' : ''}
            `);
        } else {
            throw new Error(data.message || 'Conversion failed');
        }
    })
    .catch(error => {
        clearTimeout(timeoutId);
        console.error('Conversion error:', error);
        
        // Fallback static rates
        const fallbackRates = {
            'USD': 0.0006, 'GBP': 0.00048, 'EUR': 0.00056, 
            'GHS': 0.0091, 'KES': 0.083, 'ZAR': 0.011, 
            'TZS': 1.65, 'UGX': 2.4, 'XAF': 0.27, 'XOF': 0.27
        };
        
        if (fallbackRates[currency]) {
            const amount = (budgetNGN * fallbackRates[currency]).toFixed(2);
            const symbols = {
                'USD': '$', 'GBP': '£', 'EUR': '€', 'GHS': '₵',
                'KES': 'KSh', 'ZAR': 'R', 'TZS': 'TSh', 'UGX': 'USh',
                'XAF': 'FCFA', 'XOF': 'CFA'
            };
            
            $('#walletAmount').html(`
                ${symbols[currency]}${parseFloat(amount).toLocaleString()}
                <br><small class="text-warning">⚠ Using estimated rate</small>
            `);
        } else {
            $('#walletAmount').html(`
                <span class="text-danger">Conversion unavailable</span>
                <br><small>Please select NGN</small>
            `);
        }
    });
}

function updateCardAmount() {
    const currency = $('#cardCurrency').val();
    const budgetNGN = parseFloat($('#budget').val()) || 2500;
    
    $('#cardAmount').html('<i class="fas fa-spinner fa-spin"></i> Calculating...');
    
    // If NGN, show immediately
    if (currency === 'NGN') {
        $('#cardAmount').text('₦' + budgetNGN.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        return;
    }
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 15000);
    
    fetch(`{{ route('advertising.conversion-rate') }}?currency=${currency}&amount=${budgetNGN}`, {
        signal: controller.signal,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        clearTimeout(timeoutId);
        if (!response.ok) throw new Error('API Error');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const symbols = {
                'NGN': '₦', 'USD': '$', 'GBP': '£', 'EUR': '€',
                'GHS': '₵', 'KES': 'KSh', 'ZAR': 'R', 'TZS': 'TSh',
                'UGX': 'USh', 'XAF': 'FCFA', 'XOF': 'CFA'
            };
            
            const symbol = symbols[currency] || (currency + ' ');
            const amount = parseFloat(data.convertedAmount).toFixed(2);
            
            $('#cardAmount').html(`
                ${symbol}${parseFloat(amount).toLocaleString()}
                ${data.using_fallback ? '<br><small class="text-warning">⚠ Estimated rate</small>' : ''}
            `);
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        clearTimeout(timeoutId);
        console.error('Card conversion error:', error);
        
        // Fallback rates
        const fallbackRates = {
            'USD': 0.0006, 'GBP': 0.00048, 'EUR': 0.00056,
            'GHS': 0.0091, 'KES': 0.083, 'ZAR': 0.011,
            'TZS': 1.65, 'UGX': 2.4
        };
        
        if (fallbackRates[currency]) {
            const amount = (budgetNGN * fallbackRates[currency]).toFixed(2);
            const symbols = {
                'USD': '$', 'GBP': '£', 'EUR': '€', 'GHS': '₵',
                'KES': 'KSh', 'ZAR': 'R', 'TZS': 'TSh', 'UGX': 'USh'
            };
            
            $('#cardAmount').html(`
                ${symbols[currency]}${parseFloat(amount).toLocaleString()}
                <br><small class="text-warning">⚠ Using estimated rate</small>
            `);
        } else {
            $('#cardAmount').html(`
                <span class="text-danger">Conversion unavailable</span>
                <br><small>Please try NGN</small>
            `);
        }
    });
}

// Fixed processWalletPayment function with better error handling and debugging

function processWalletPayment() {
    const formData = new FormData(document.getElementById('createAdForm'));
    const currency = $('#walletCurrency').val();
    
    console.log('🔵 Starting wallet payment process...');
    console.log('Currency:', currency);
    
    // Validate currency
    if (!currency) {
        alert('⚠ Please select a currency');
        return;
    }
    
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    // Build ad data object
    const adData = {};
    let hasRequiredFields = true;
    
    formData.forEach((value, key) => {
        // Handle array fields
        if (key.includes('[]')) {
            const cleanKey = key.replace('[]', '');
            if (!adData[cleanKey]) adData[cleanKey] = [];
            adData[cleanKey].push(value);
        } else {
            adData[key] = value;
        }
    });
    
    console.log('📦 Ad Data:', adData);
    
    // Validate required fields
    if (!adData.title || !adData.description || !adData.media_url || !adData.budget) {
        alert('⚠ Please fill in all required fields');
        btn.disabled = false;
        btn.innerHTML = originalText;
        return;
    }
    
    console.log('✅ Validation passed, sending request...');
    
    $.ajax({
        url: '{{ route("advertising.pay-from-wallet") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        data: {
            currency: currency,
            ad_data: JSON.stringify(adData)
        },
        success: function(response) {
            console.log('✅ Success response:', response);
            
            if (response.success) {
                alert('✓ Payment Successful!\n\n' + 
                      'Ad created successfully.\n' +
                      'Remaining balance: ' + response.remaining_balance + '\n\n' +
                      'Your ad is pending admin approval.');
                window.location.href = '{{ route("advertising.index") }}';
            } else {
                throw new Error(response.message || 'Unknown error');
            }
        },
        error: function(xhr) {
            console.error('❌ Error response:', xhr);
            console.error('Status:', xhr.status);
            console.error('Response:', xhr.responseText);
            
            btn.disabled = false;
            btn.innerHTML = originalText;
            
            let errorMessage = 'Payment failed. Please try again.';
            
            try {
                const response = xhr.responseJSON;
                if (response && response.error) {
                    errorMessage = response.error;
                } else if (response && response.message) {
                    errorMessage = response.message;
                }
            } catch (e) {
                console.error('Could not parse error response:', e);
            }
            
            // Show detailed error
            alert('✗ Payment Failed\n\n' + errorMessage);
            
            // If it's a validation error, show details
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                let errorList = '\n\nValidation Errors:\n';
                for (let field in errors) {
                    errorList += `• ${field}: ${errors[field].join(', ')}\n`;
                }
                console.error('Validation errors:', errors);
                alert(errorList);
            }
        }
    });
}

function initiateCardPayment() {
    const budget = parseFloat($('#budget').val());
    const currency = $('#cardCurrency').val();

    if (!budget || budget < 2500) {
        alert('Please enter a valid budget (minimum ₦2,500)');
        return;
    }

    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

    FlutterwaveCheckout({
        public_key: "{{ env('FLW_PUBLIC_KEY') }}",
        tx_ref: 'AD-' + Date.now(),
        amount: budget,
        currency: currency,
        payment_options: 'card, banktransfer, ussd',
        customer: {
            email: '{{ $user->email }}',
            phone_number: '{{ $user->phone ?? "" }}',
            name: '{{ $user->name }}'
        },
        customizations: {
            title: 'Advertisement Payment',
            description: 'Payment for ad campaign on Supperage',
        },
        callback: function(data) {
            verifyPayment(data.transaction_id);
        },
        onclose: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-credit-card"></i> Pay Now';
        }
    });
}

function verifyPayment(transactionId) {
    const formData = new FormData(document.getElementById('createAdForm'));
    formData.append('flutterwave_tx_ref', transactionId);

    $.ajax({
        url: '{{ route("advertising.store") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                alert('✓ Payment Confirmed!\n\nAd created successfully.\nYour ad is pending admin approval.');
                window.location.href = '{{ route("advertising.index") }}';
            }
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.error || 'Failed to create ad';
            alert('✗ Error\n\n' + error);
        }
    });
}
</script>
@endsection
</body>
</html>