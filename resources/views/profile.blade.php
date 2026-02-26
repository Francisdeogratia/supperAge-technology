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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $profileUser->name }}'s Profile - Supperage</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/allbtns.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 20px;
        border-radius: 15px;
        margin-bottom: 30px;
        position: relative;
    }
    
    .profile-cover {
        height: 250px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px 15px 0 0;
        position: relative;
    }
    .profile-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: 15px 15px 0 0;
        position: absolute;
        top: 0; left: 0;
    }
    .cover-upload-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0,0,0,0.55);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
        z-index: 20;
        white-space: nowrap;
    }
    .cover-upload-btn:hover { background: rgba(0,0,0,0.75); }
    .cover-upload-btn .btn-label { display: inline; }
    @media (max-width: 480px) {
        .cover-upload-btn { padding: 7px 10px; font-size: 14px; border-radius: 50%; gap: 0; }
        .cover-upload-btn .btn-label { display: none; }
    }
    
    .profile-picture-container {
        position: absolute;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }
    
    .profile-picture {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .profile-stats {
        display: flex;
        justify-content: center;
        gap: 25px;
        margin-top: 70px;
        padding: 18px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        flex-wrap: wrap;
    }
    
    .stat-item {
        text-align: center;
        min-width: 80px;
    }
    
    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: #667eea;
    }
    
    .stat-label {
        font-size: 14px;
        color: #666;
    }
    
    .profile-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 20px 0;
        flex-wrap: wrap;
    }
    
    .post-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    
    /* ✅ MONETIZATION BADGE STYLES */
    .monetization-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: bold;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        margin-top: 10px;
        animation: shimmer 2s infinite;
    }
    
    .monetization-badge i {
        font-size: 16px;
    }
    
    @keyframes shimmer {
        0%, 100% { box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); }
        50% { box-shadow: 0 4px 20px rgba(16, 185, 129, 0.5); }
    }
    
    .monetization-info-card {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 2px solid #10b981;
        border-radius: 15px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);
    }
    
    .monetization-info-card h5 {
        color: #059669;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .monetization-info-card h5 i {
        color: #10b981;
        font-size: 24px;
    }
    
    .monetization-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    
    .monetization-stat-item {
        background: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .monetization-stat-value {
        font-size: 20px;
        font-weight: bold;
        color: #10b981;
        margin-bottom: 5px;
    }
    
    .monetization-stat-label {
        font-size: 12px;
        color: #666;
    }
    
    @media (max-width: 768px) {
        .profile-stats {
            gap: 15px;
        }
        .stat-item {
            min-width: 60px;
        }
        .stat-number {
            font-size: 20px;
        }
        .stat-label {
            font-size: 12px;
        }
        .monetization-badge {
            font-size: 12px;
            padding: 6px 12px;
        }
    }
</style>
</head>
<body>
@include('layouts.navbar')

@extends('layouts.app')

@section('seo_title', 'My Profile - SupperAge')
@section('seo_description', 'View and manage your SupperAge profile. Update your info, see your posts, followers, and activity.')

@section('content')
<div class="container mt-4" style="margin-bottom: 80px;">
    {{-- Profile Header --}}
    <div class="profile-cover" id="profileCoverBox">
        {{-- Cover background image --}}
        @if($profileUser->bgimg)
            <img src="{{ asset($profileUser->bgimg) }}" class="profile-cover-img" alt="Cover Photo" id="coverPreview">
        @else
            <img src="" class="profile-cover-img" id="coverPreview" style="display:none;">
        @endif

        {{-- Upload cover button (own profile only) --}}
        @if(isset($isOwnProfile) && $isOwnProfile)
            <input type="file" id="coverFileInput" accept="image/*" style="display:none;">
            <button class="cover-upload-btn" onclick="document.getElementById('coverFileInput').click()">
                <i class="fa fa-camera"></i><span class="btn-label"> Edit Cover</span>
            </button>
        @endif

        <div class="profile-picture-container">
            @if($profileUser->profileimg)
                <img src="{{ str_replace('/upload/', '/upload/w_150,h_150,c_fill,r_max,q_70/', $profileUser->profileimg) }}"
                     class="profile-picture"
                     alt="{{ $profileUser->name }}">
            @else
                <div class="profile-picture" style="background: #ddd; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-user" style="font-size: 60px; color: #999;"></i>
                </div>
            @endif

            @if($profileUser->badge_status)
                <img src="{{ asset($profileUser->badge_status) }}"
                     alt="Verified"
                     style="width:40px;height:40px;position:absolute;bottom:5px;right:5px;border-radius:50%;border:3px solid white;">
            @endif
        </div>
    </div>
    
    {{-- Profile Info --}}
    <div style="text-align: center; margin-top: 60px;">
        <h2 style="display:inline-flex;align-items:center;gap:8px;justify-content:center;">
            {{ $profileUser->name }}
            @if($profileUser->badge_status)
                <img src="{{ asset($profileUser->badge_status) }}"
                     alt="Verified" title="Verified User"
                     style="width:26px;height:26px;border-radius:50%;vertical-align:middle;">
            @endif
        </h2>
        <p class="text-muted">{{ '@' . $profileUser->username }}</p>
        
        {{-- ✅ MONETIZATION BADGE --}}
        @if($profileUser->monetization && $profileUser->monetization->status === 'approved')
            <div class="monetization-badge">
                <i class="fa fa-dollar-sign"></i>
                <span>Monetized Creator</span>
                <i class="fa fa-badge-check"></i>
            </div>
        @endif
        
        @if(isset($isOnline) && $isOnline)
            <span style="color: green; font-weight: bold; display: block; margin-top: 10px;">
                <i class="fa fa-circle" style="font-size: 8px;"></i> Online now
            </span>
        @elseif(isset($lastSeen))
            <span style="color: gray; display: block; margin-top: 10px;">
                Last seen: {{ $lastSeen }}
            </span>
        @endif
        
        @if($profileUser->bio ?? false)
            <p style="margin-top: 15px; max-width: 600px; margin-left: auto; margin-right: auto;">
                {{ $profileUser->bio }}
            </p>
        @endif
        
        @if(($profileUser->city ?? false) || ($profileUser->state ?? false) || ($profileUser->country ?? false))
            <p class="text-muted">
                <i class="fa fa-map-marker"></i> 
                @if($profileUser->city) {{ $profileUser->city }}, @endif
                @if($profileUser->state) {{ $profileUser->state }}, @endif
                @if($profileUser->country) {{ $profileUser->country }} @endif
                @if($profileUser->continent) ({{ $profileUser->continent }}) @endif
            </p>
        @endif
    </div>
    
    {{-- ✅ MONETIZATION INFO CARD (Only show for own profile or if monetized) --}}
    @if($profileUser->monetization && $profileUser->monetization->status === 'approved')
        <div class="monetization-info-card">
            <h5>
                <i class="fa fa-chart-line"></i>
                Monetization Status
            </h5>
            <p style="color: #666; margin-bottom: 15px;">
                <i class="fa fa-check-circle" style="color: #10b981;"></i>
                This account is approved for monetization since 
                <strong>{{ $profileUser->monetization->approved_at ? $profileUser->monetization->approved_at->format('M d, Y') : 'N/A' }}</strong>
            </p>
            
            @if(isset($isOwnProfile) && $isOwnProfile)
                <div class="monetization-stats">
                    <div class="monetization-stat-item">
                        <div class="monetization-stat-value">₦{{ number_format($profileUser->monetization->total_earnings ?? 0, 2) }}</div>
                        <div class="monetization-stat-label">Total Earnings</div>
                    </div>
                    <div class="monetization-stat-item">
                        <div class="monetization-stat-value">{{ $profileUser->monetization->approved_at ? $profileUser->monetization->approved_at->diffForHumans() : 'Recently' }}</div>
                        <div class="monetization-stat-label">Member Since</div>
                    </div>
                </div>
                
                @if($profileUser->monetization->notes)
                    <p style="margin-top: 15px; padding: 10px; background: white; border-radius: 8px; font-size: 14px; color: #666;">
                        <strong>Note:</strong> {{ $profileUser->monetization->notes }}
                    </p>
                @endif
            @endif
        </div>
    @endif
    
    {{-- Profile Stats --}}
    <div class="profile-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $posts->total() }}</div>
            <div class="stat-label">Posts</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" id="followers-count">{{ $followersCount }}</div>
            <div class="stat-label">Followers</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $followingCount }}</div>
            <div class="stat-label">Following</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $friendsCount ?? 0 }}</div>
            <div class="stat-label">Friends</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ number_format($totalLikes) }}</div>
            <div class="stat-label">Likes</div>
        </div>
        @if(isset($totalViews))
        <div class="stat-item">
            <div class="stat-number">{{ number_format($totalViews) }}</div>
            <div class="stat-label">Views</div>
        </div>
        @endif
    </div>
    
    {{-- Action Buttons --}}
    <div class="profile-actions">
        @if(isset($isOwnProfile) && $isOwnProfile)
            {{-- Own Profile - Show Edit Button --}}
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                <i class="fa fa-edit"></i> Edit Profile
            </a>
            <a href="{{ route('wallet.fund', $profileUser->id) }}" class="btn btn-success">
                <i class="fa fa-wallet"></i> My Wallet
            </a>
        @else
            {{-- Other User's Profile - Show Follow, Message, Fund Wallet, Friend Request --}}
            
            {{-- Follow/Unfollow Button --}}
            <button type="button" 
                    class="btn {{ $isFollowing ? 'btn-secondary' : 'btn-primary' }} follow-toggle-btn" 
                    data-user-id="{{ $profileUser->id }}"
                    data-is-following="{{ $isFollowing ? 'true' : 'false' }}"
                    style="min-width: 120px;">
                <i class="fa fa-user-{{ $isFollowing ? 'check' : 'plus' }}"></i>
                <span class="follow-text">{{ $isFollowing ? 'Following' : 'Follow' }}</span>
            </button>
            
            {{-- Friend Request Button --}}
            @php
                $friendStatus = 'none';
                
                if (isset($friendRequestStatus)) {
                    $friendStatus = $friendRequestStatus;
                } elseif (isset($areFriends) && $areFriends) {
                    $friendStatus = 'friends';
                }
            @endphp
            
            @if($friendStatus === 'friends')
                <button type="button" class="btn btn-success" disabled>
                    <i class="fa fa-check"></i> Friends
                </button>
                <button type="button" class="btn btn-outline-danger unfriend-btn"
                        data-user-id="{{ $profileUser->id }}">
                    <i class="fa fa-user-times"></i> Unfriend
                </button>
            @elseif($friendStatus === 'pending_sent')
                <button type="button" class="btn btn-warning friend-action-btn" 
                        data-action="cancel" 
                        data-request-id="{{ $friendRequestId ?? '' }}">
                    <i class="fa fa-clock"></i> Request Sent
                </button>
            @elseif($friendStatus === 'pending_received')
                <button type="button" class="btn btn-info friend-action-btn" 
                        data-action="accept" 
                        data-request-id="{{ $friendRequestId ?? '' }}">
                    <i class="fa fa-user-plus"></i> Accept Request
                </button>
            @else
                <button type="button" class="btn btn-info send-friend-request-btn" 
                        data-user-id="{{ $profileUser->id }}">
                    <i class="fa fa-user-plus"></i> Add Friend
                </button>
            @endif
            
            {{-- Message Button --}}
            <a href="{{ route('messages.chat', $profileUser->id) }}" class="btn btn-info">
                <i class="fa fa-envelope"></i> Message
            </a>
            
            {{-- Fund Wallet Button --}}
            <a href="{{ route('wallet.fund', $profileUser->id) }}" class="btn btn-success">
                <i class="fa fa-wallet"></i> Fund Wallet
            </a>
        @endif
    </div>
    
    {{-- Mutual Friends --}}
    @if(isset($mutualFriends) && isset($isOwnProfile) && !$isOwnProfile && count($mutualFriends) > 0)
        <div style="background: white; padding: 20px; border-radius: 15px; margin: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h5 style="margin-bottom: 15px;">
                <i class="fa fa-users"></i> 
                Mutual Friends ({{ count($mutualFriends) }})
            </h5>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                @foreach($mutualFriends as $friend)
                    <a href="{{ route('profile.show', $friend->id) }}" style="text-decoration: none;">
                        <div style="text-align: center;">
                            @if($friend->profileimg)
                                <img src="{{ str_replace('/upload/', '/upload/w_50,h_50,c_fill,r_max,q_70/', $friend->profileimg) }}" 
                                     style="width: 50px; height: 50px; border-radius: 50%;">
                            @else
                                <div style="width: 50px; height: 50px; border-radius: 50%; background: #ddd; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-user"></i>
                                </div>
                            @endif
                            <small style="display: block; margin-top: 5px; color: #666;">
                                {{ Str::limit($friend->name, 10) }}
                            </small>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
    
    {{-- User's Posts --}}
    <div style="margin-top: 40px;">
        <h4 style="margin-bottom: 20px;">
            <i class="fa fa-image"></i> 
            {{ (isset($isOwnProfile) && $isOwnProfile) ? 'Your' : $profileUser->name . "'s" }} Posts
            <span style="color: #999; font-size: 16px;">({{ $posts->total() }})</span>
        </h4>
        
        @if($posts->count() > 0)
            <div class="post-grid">
                @foreach($posts as $post)
                    <div class="card" style="border-radius: 15px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        {{-- Post Media --}}
                        @if($post->file_path)
                            @php
                                $files = is_string($post->file_path) ? json_decode($post->file_path, true) : $post->file_path;
                                $firstFile = is_array($files) && count($files) > 0 ? $files[0] : null;
                            @endphp
                            
                            @if($firstFile)
                                @if(Str::contains($firstFile, ['.mp4', '.webm', '.ogg']))
                                    <video style="width: 100%; height: 200px; object-fit: cover;">
                                        <source src="{{ $firstFile }}" type="video/mp4">
                                    </video>
                                @else
                                    <img src="{{ $firstFile }}" 
                                         style="width: 100%; height: 200px; object-fit: cover;">
                                @endif
                            @endif
                        @else
                            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, {{ $post->bgnd_color ?? '#f0f0f0' }} 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                <p style="color: {{ $post->text_color ?? '#000' }}; padding: 20px; text-align: center;">
                                    {{ Str::limit($post->post_content, 100) }}
                                </p>
                            </div>
                        @endif
                        
                        {{-- Post Stats --}}
                        <div class="card-body">
                            <p class="card-text" style="margin-bottom: 10px;">
                                {{ Str::limit($post->post_content, 100) }}
                            </p>
                            
                            <div style="display: flex; justify-content: space-between; font-size: 14px; color: #666;">
                                <span><i class="fa fa-heart"></i> {{ $post->likes_relation_count ?? 0 }}</span>
                                <span><i class="fa fa-comment"></i> {{ $post->comments_count ?? 0 }}</span>
                                <span><i class="fa fa-retweet"></i> {{ $post->reposts_relation_count ?? 0 }}</span>
                                <span><i class="fa fa-eye"></i> {{ $post->views_relation_count ?? 0 }}</span>
                            </div>
                            
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary btn-sm btn-block mt-3">
                                View Post
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px; background: #f8f9fa; border-radius: 15px;">
                <i class="fa fa-image" style="font-size: 60px; color: #ccc;"></i>
                <h5 style="margin-top: 20px; color: #666;">
                    {{ (isset($isOwnProfile) && $isOwnProfile) ? 'You haven\'t posted anything yet' : $profileUser->name . ' hasn\'t posted anything yet' }}
                </h5>
                @if(isset($isOwnProfile) && $isOwnProfile)
                    <a href="{{ url('update') }}" class="btn btn-primary mt-3">
                        Create Your First Post
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
$(document).ready(function() {
    
    // Cover photo upload
    const coverInput = document.getElementById('coverFileInput');
    if (coverInput) {
        coverInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('cover', file);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            const btn = document.querySelector('.cover-upload-btn');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Uploading...';
            btn.disabled = true;

            fetch('{{ route("profile.uploadCover") }}', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const preview = document.getElementById('coverPreview');
                    preview.src = data.url;
                    preview.style.display = 'block';
                    // Remove default gradient once image loads
                    document.getElementById('profileCoverBox').style.background = 'none';
                    btn.innerHTML = '<i class="fa fa-check"></i> Saved!';
                    setTimeout(() => { btn.innerHTML = originalHtml; btn.disabled = false; }, 2000);
                } else {
                    alert(data.error || 'Upload failed. Try again.');
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
            })
            .catch(() => {
                alert('Upload failed. Try again.');
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
        });
    }

    // Remove gradient if cover image exists
    @if($profileUser->bgimg)
        document.getElementById('profileCoverBox').style.background = 'none';
    @endif

    // Follow/Unfollow Button Handler
    $('.follow-toggle-btn').on('click', function() {
        const btn = $(this);
        const userId = btn.data('user-id');
        const isFollowing = btn.data('is-following') === 'true';
        const url = isFollowing 
            ? '{{ url("/unfollow") }}/' + userId 
            : '{{ url("/follow") }}/' + userId;
        
        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Please wait...');
        
        $.ajax({
            url: url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'true' || response.success || response.status === 'success') {
                    const newIsFollowing = !isFollowing;
                    btn.data('is-following', newIsFollowing);
                    
                    if (newIsFollowing) {
                        btn.removeClass('btn-primary').addClass('btn-secondary');
                        btn.html('<i class="fa fa-user-check"></i> <span class="follow-text">Following</span>');
                    } else {
                        btn.removeClass('btn-secondary').addClass('btn-primary');
                        btn.html('<i class="fa fa-user-plus"></i> <span class="follow-text">Follow</span>');
                    }
                    
                    if (response.followers_count !== undefined) {
                        $('#followers-count').text(response.followers_count);
                    }
                    
                    btn.prop('disabled', false);
                } else {
                    alert(response.message || 'Something went wrong. Please try again.');
                    btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                alert('Something went wrong. Please try again.');
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
    
    // Friend Request Handler
    $('.send-friend-request-btn').on('click', function() {
        const btn = $(this);
        const userId = btn.data('user-id');
        const originalHtml = btn.html();

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');

        $.ajax({
            url: '{{ url("/friends/send-request") }}/' + userId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    btn.removeClass('btn-info').addClass('btn-warning');
                    btn.html('<i class="fa fa-clock"></i> Request Sent');
                    alert('Friend request sent successfully!');
                } else {
                    alert(response.error || response.message || 'Something went wrong.');
                    btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr) {
                console.error('Friend request error:', xhr.responseJSON);
                const errorMsg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Something went wrong. Please try again.';
                alert(errorMsg);
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
    
    // Unfriend Handler
    $('.unfriend-btn').on('click', function() {
        if (!confirm('Are you sure you want to unfriend this person?')) return;
        const btn = $(this);
        const userId = btn.data('user-id');
        const originalHtml = btn.html();

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Removing...');

        $.ajax({
            url: '{{ url("/friends/unfriend") }}/' + userId,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                if (response.success) {
                    // Replace both buttons with Add Friend button
                    btn.closest('.profile-actions').find('.btn-success[disabled]').remove();
                    btn.replaceWith(
                        '<button type="button" class="btn btn-info send-friend-request-btn" data-user-id="' + userId + '">' +
                        '<i class="fa fa-user-plus"></i> Add Friend</button>'
                    );
                } else {
                    alert(response.error || 'Something went wrong.');
                    btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function() {
                alert('Something went wrong. Please try again.');
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // Accept/Cancel Friend Request Handler
    $('.friend-action-btn').on('click', function() {
        const btn = $(this);
        const action = btn.data('action');
        const requestId = btn.data('request-id');
        const originalHtml = btn.html();

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        const url = action === 'accept'
            ? '{{ url("/friends/accept") }}/' + requestId
            : '{{ url("/friends/cancel") }}/' + requestId;

        $.ajax({
            url: url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message || 'Done!');
                    location.reload();
                } else {
                    alert(response.error || 'Something went wrong.');
                    btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr) {
                console.error('Friend action error:', xhr.responseJSON);
                alert(xhr.responseJSON?.error || 'Something went wrong. Please try again.');
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
});
</script>
@endsection

</body>
</html>