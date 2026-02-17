<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <title>{{ '@' . $userTales->first()->username }}'s Tale</title>

    <style>
        body, html { margin: 0; padding: 0; height: 100%; width: 100%; background-color: #000; color: #fff; font-family: 'Segoe UI', sans-serif; overflow: hidden; }
        .status-wrapper { display: flex; align-items: center; justify-content: center; height: 100vh; width: 100vw; position: relative; }
        .card { position: relative; width: 100%; height: 100%; max-width: 450px; background: #000; display: none; flex-direction: column; }
        @media (min-width: 768px) { .card { height: 95vh; border-radius: 15px; overflow: hidden; } }

        #taleProgressIndicators { display: flex; gap: 5px; position: absolute; top: 10px; left: 10px; right: 10px; z-index: 100; }
        .progress-dot { flex: 1; height: 3px; background: rgba(255,255,255,0.3); border-radius: 2px; }
        .progress-dot.viewed { background: #fff; }
        .progress-dot.active { background: cornflowerblue; box-shadow: 0 0 5px cornflowerblue; }

        .header { display: flex; align-items: center; padding: 25px 15px 10px; background: linear-gradient(to bottom, rgba(0,0,0,0.8), transparent); position: absolute; top: 0; width: 100%; z-index: 50; box-sizing: border-box; }
        .profile-pic { width: 45px; height: 45px; border-radius: 50%; border: 2px solid #fff; margin-right: 12px; object-fit: cover; }
        .user-info { flex: 1; }
        .username { font-weight: bold; font-size: 1rem; display: flex; align-items: center; gap: 5px; }
        .time { font-size: 0.75rem; color: #ccc; display: flex; align-items: center; gap: 5px; }

        .dropdown { position: relative; }
        .dropdown-menu { display: none; position: absolute; right: 0; top: 40px; background: #fff; border-radius: 8px; min-width: 160px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); z-index: 200; overflow: hidden; }
        .dropdown-menu a, .dropdown-menu button { display: block; width: 100%; padding: 12px 15px; text-decoration: none; color: #333; font-size: 0.9rem; border: none; background: none; text-align: left; cursor: pointer; }
        .dropdown-menu a:hover, .dropdown-menu button:hover { background: #f0f0f0; }

        .media-container { flex: 1; display: flex; align-items: center; justify-content: center; position: relative; background: #000; overflow: hidden; cursor: pointer; }
        .main-media { width: 100%; max-height: 100%; object-fit: contain; z-index: 1; }

        .play-pause-overlay { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 15; font-size: 3rem; color: rgba(255,255,255,0.7); display: none; pointer-events: none; }

        .overlay { position: absolute; bottom: 0; width: 100%; background: linear-gradient(to top, rgba(0,0,0,1), transparent); padding: 20px 15px; box-sizing: border-box; z-index: 60; }
        .message { margin-bottom: 15px; font-size: 0.95rem; max-height: 80px; overflow-y: auto; }

        .stats { display: flex; gap: 15px; margin-bottom: 15px; font-size: 0.85rem; }
        .stats strong { cursor: pointer; color: #fff; }
        
        .comments-tray { display: none; background: rgba(20, 20, 20, 0.98); border-radius: 15px 15px 0 0; position: absolute; bottom: 0; left: 0; width: 100%; max-height: 70%; z-index: 150; padding: 20px; box-sizing: border-box; border-top: 1px solid #333; }
        .comment-item { font-size: 0.85rem; margin-bottom: 10px; padding: 8px; background: rgba(255,255,255,0.05); border-radius: 8px; }

        .comment-box { display: flex; gap: 10px; align-items: center; }
        .commentInput { flex: 1; padding: 12px 18px; border-radius: 25px; border: none; background: rgba(255,255,255,0.2); color: #fff; outline: none; }
        .sendCommentBtn, .internalSendBtn { background: cornflowerblue; border: none; color: #fff; width: 42px; height: 42px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
        .internalSendBtn { background: #2ed573; }

        .user-list-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 1000; display: flex; justify-content: center; align-items: center; }
        .user-list { background: #1a1a1a; padding: 20px; width: 85%; max-width: 380px; border-radius: 15px; border: 1px solid #333; max-height: 80vh; overflow-y: auto; }
        .search-input { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 8px; border: none; background: #333; color: #fff; box-sizing: border-box; }
        .user-item { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; border-bottom: 1px solid #222; padding-bottom: 8px; }
        .profile-thumb { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }










        .link-preview-container {
    display: flex;
    flex-direction: column;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    margin: 10px 0;
    overflow: hidden;
    text-decoration: none;
    color: white;
    transition: transform 0.2s ease;
}

.link-preview-container:active {
    transform: scale(0.98);
}

.preview-img-box {
    width: 100%;
    height: 140px;
    overflow: hidden;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.preview-img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-details {
    padding: 10px 12px;
}

.preview-site {
    display: block;
    font-size: 10px;
    font-weight: 700;
    color: cornflowerblue;
    margin-bottom: 4px;
    letter-spacing: 0.5px;
}

.preview-title {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.preview-desc {
    margin: 4px 0 0;
    font-size: 0.75rem;
    color: #ccc;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
    </style>
</head>
<body>

<div class="status-wrapper">
    <div id="taleProgressIndicators">
        @foreach($userTales as $index => $tale)
            <div class="progress-dot" data-index="{{ $index }}"></div>
        @endforeach
    </div>

    @foreach($userTales as $index => $tale)
    <div class="card tale-slide" data-id="{{ $tale->tales_id }}">
        
        <div class="header">
            <a href="{{ url('update') }}" style="color: #fff; margin-right: 15px;"><i class="fa fa-arrow-left"></i></a>
            <img src="{{ $tale->profileimg ?: asset('images/default.png') }}" class="profile-pic" />
            
            <div class="user-info">
                <div class="username">
                    {{ $tale->name }}
                    @if($tale->badge_status) <img src="{{ asset($tale->badge_status) }}" style="width:16px;"> @endif
                </div>
                <div class="time">
                    {{ \Carbon\Carbon::parse($tale->created_at)->diffForHumans() }}
                    @php
                        $loginSession = $tale->lastLoginSession ?? null;
                        $isOnline = $loginSession && $loginSession->logout_at === null;
                    @endphp
                    @if($isOnline)
                        <span style="color: #00ff00; font-weight: bold;">● Online</span>
                    @endif
                </div>
            </div>

            <div class="dropdown">
                <i class="fa fa-ellipsis-v dropdown-toggles" style="padding: 10px; cursor: pointer;"></i>
                <div class="dropdown-menu">
                    <a href="{{ $tale->files_talesexten }}" download><i class="fa fa-download"></i> Download</a>
                    <button class="share-btn" data-url="{{ url('/viewtales/'.$tale->tales_id) }}"><i class="fa fa-share"></i> Share Externally</button>
                    @if(Session::get('username') === $tale->username)
                        <a href="{{ route('tale.edit', $tale->tales_id) }}"><i class="fa fa-edit"></i> Edit</a>
                        <form action="{{ route('tale.delete', $tale->tales_id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"><i class="fa fa-trash"></i> Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div style="position: absolute; width: 30%; height: 70%; left: 0; z-index: 10;" onclick="moveToPrevSlide()"></div>
        <div style="position: absolute; width: 70%; height: 70%; right: 0; z-index: 10;" onclick="moveToNextSlide()"></div>

        <div class="media-container" onclick="moveToNextSlide()">
            <div class="play-pause-overlay"><i class="fa fa-play"></i></div>
            @if(Str::endsWith($tale->files_talesexten, ['.mp4', '.webm', '.ogg']))
                <video class="main-media tale-video" playsinline id="vid-{{ $tale->tales_id }}">
                    <source src="{{ $tale->files_talesexten }}" type="video/mp4">
                </video>
            @else
                <img src="{{ $tale->files_talesexten }}" class="main-media">
            @endif
        </div>

        <div class="overlay">
            <div class="message">{{ $tale->tales_content }}</div>
            
    @if($tale->link_preview)
    @php $linkData = json_decode($tale->link_preview, true); @endphp
    @if($linkData)
    <a href="{{ $linkData['url'] }}" target="_blank" class="link-preview-container">
        @if(!empty($linkData['image']))
            <div class="preview-img-box">
                <img src="{{ $linkData['image'] }}" alt="Preview">
            </div>
        @endif
        <div class="preview-details">
            <span class="preview-site">{{ strtoupper($linkData['site_name'] ?? parse_url($linkData['url'], PHP_URL_HOST)) }}</span>
            <h4 class="preview-title">{{ $linkData['title'] }}</h4>
            @if(!empty($linkData['description']))
                <p class="preview-desc">{{ Str::limit($linkData['description'], 60) }}</p>
            @endif
        </div>
    </a>
    @endif
@endif
    
    
            <div class="stats">
                <strong class="view-trigger" data-id="{{ $tale->tales_id }}">{{ $tale->views }} Views</strong>
                <strong class="like-trigger" data-id="{{ $tale->tales_id }}"><span id="l-{{ $tale->tales_id }}">{{ $tale->likes }}</span> Likes</strong>
                <strong class="comment-trigger" data-id="{{ $tale->tales_id }}">Comments (<span class="comment-count-{{ $tale->tales_id }}">{{ count($tale->comments) }}</span>)</strong>
            </div>

            <div class="share-section" style="margin-bottom: 10px; display: flex; gap: 5px;">
        <select class="shareTarget" data-id="{{ $tale->tales_id }}" style="flex: 1; padding: 8px; border-radius: 20px; background: rgba(255,255,255,0.2); color: #fff; border: none;">
            <option value="" style="color: #000;">Select recipient</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" style="color: #000;">{{ $user->username }}</option>
            @endforeach
        </select>
        <button class="shareToBtn" data-id="{{ $tale->tales_id }}" style="background: #2ed573; border: none; color: #fff; padding: 0 15px; border-radius: 20px; cursor: pointer;">Share</button>
    </div>



            <div class="comment-box">
                <input type="text" class="commentInput" placeholder="Reply..." data-id="{{ $tale->tales_id }}">
                <button class="sendCommentBtn" data-id="{{ $tale->tales_id }}">➤</button>
                
                
                <button class="likeBtn" data-id="{{ $tale->tales_id }}" style="background:none; border:none; color:#fff; font-size:1.5rem;">
                    <i class="fa-heart {{ $tale->isLiked ? 'fas' : 'far' }}" style="{{ $tale->isLiked ? 'color:red;' : '' }}"></i>
                </button>
            </div>
        </div>

        <div class="comments-tray">
            <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
                <span>Comments</span>
                <i class="fa fa-times close-comments" style="cursor:pointer;"></i>
            </div>
            <div class="comments-list-{{ $tale->tales_id }}" style="overflow-y:auto; max-height:280px;">
                @foreach($tale->comments as $c)
                    <div class="comment-item"><strong>{{ $c->username }}:</strong> {{ $c->comment }}</div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentIndex = 0;
    let taleTimer;
    let isPaused = false;
    const slides = $('.tale-slide');
    const dots = $('.progress-dot');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    function showSlide(index) {
        clearTimeout(taleTimer);
        isPaused = false;
        $('.play-pause-overlay').hide();
        
        $('video').each(function() {
            this.pause();
            this.currentTime = 0;
        });

        slides.hide();
        $('.dropdown-menu, .comments-tray').hide();
        
        const currentSlide = slides.eq(index);
        currentSlide.css('display', 'flex');

        dots.removeClass('active viewed').each((i, d) => {
            if (i < index) $(d).addClass('viewed');
            if (i === index) $(d).addClass('active');
        });

        const video = currentSlide.find('video')[0];
        if (video) {
            video.play().catch(() => {});
            video.onended = moveToNextSlide;
        } else {
            taleTimer = setTimeout(moveToNextSlide, 7000); 
        }

        $.post(`/tales/${currentSlide.data('id')}/view`, {_token: csrfToken});
    }

    function moveToNextSlide() {
        if (currentIndex < slides.length - 1) {
            currentIndex++;
            showSlide(currentIndex);
        } else {
            window.location.href="{{ url('update') }}";
        }
    }

    function moveToPrevSlide() {
        if(currentIndex > 0) { 
            currentIndex--; 
            showSlide(currentIndex); 
        }
    }


    // New Share to Recipient Logic
$(document).on('click', '.shareToBtn', function () {
    const $btn = $(this);
    const taleId = $btn.data('id');
    const $select = $btn.closest('.card').find('.shareTarget');
    const targetUserId = $select.val();
    
    const shareCountEl = $btn.closest('.card').find('#shareCount'); // Ensure you have this ID in your stats
    const errorMsg = $btn.closest('.card').find('#errorMsg');

    if (!targetUserId) {
        alert('⚠️ Please select a recipient.');
        return;
    }

    $btn.prop('disabled', true).text('...');

    $.post(`/tales/${taleId}/share-to`, {
        _token: $('meta[name="csrf-token"]').attr('content'),
        target: targetUserId
    })
    .done(function (data) {
        alert('✅ ' + (data.message || 'Shared successfully!'));
        $btn.prop('disabled', false).text('Share');
        $select.val(''); 
    })
    .fail(function(xhr) {
        alert('❌ Failed to share. Please try again.');
        $btn.prop('disabled', false).text('Share');
    });
});

    // External Share Logic
    $(document).on('click', '.share-btn', function(e) {
        e.preventDefault();
        const shareData = {
            title: "Check out this Tale",
            text: "Check out this story on our platform!",
            url: $(this).data('url')
        };

        // Pause the timer so the story doesn't skip while sharing
        clearTimeout(taleTimer);

        // Check if the browser supports the native share sheet (Mobile)
        if (navigator.share) {
            navigator.share(shareData)
                .then(() => console.log('Shared successfully'))
                .catch((err) => console.log('Error sharing:', err))
                .finally(() => showSlide(currentIndex)); // Resume after share
        } else {
            // Fallback: Copy to clipboard if Share API isn't available (Desktop)
            const tempInput = document.createElement("input");
            tempInput.value = shareData.url;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            
            alert("Link copied to clipboard!");
            showSlide(currentIndex);
        }
    });


    // Logic for viewing Likes or Views list
    $(document).on('click', '.view-trigger, .like-trigger', function() {
        const id = $(this).data('id');
        const type = $(this).hasClass('view-trigger') ? 'views' : 'likes';
        
        // Pause timer and video
        clearTimeout(taleTimer);
        const currentVid = slides.eq(currentIndex).find('video')[0];
        if(currentVid) currentVid.pause();

        // Fetch user list from your backend
        // Note: Ensure these routes exist: /tales/{id}/viewers and /tales/{id}/likers
        const endpoint = type === 'views' ? `/tales/${id}/viewers` : `/tales/${id}/likers`;

        $.get(endpoint, function(users) {
            let html = `<div class="user-list-overlay"><div class="user-list">
                <h3 style="margin-top:0; color:cornflowerblue;">${type.charAt(0).toUpperCase() + type.slice(1)}</h3>
                <div id="userItems">`;
            
            if(users.length === 0) {
                html += `<p style="color:#888; text-align:center;">No ${type} yet.</p>`;
            }

            users.forEach(u => {
                html += `<div class="user-item">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img src="${u.profileimg || '/images/default.png'}" class="profile-thumb">
                        <span>@${u.username}</span>
                    </div>
                    <a href="/profile/${u.id}" style="color:cornflowerblue; text-decoration:none; font-size:0.8rem;">View</a>
                </div>`;
            });
            
            html += `</div><button onclick="$('.user-list-overlay').remove(); showSlide(currentIndex);" style="width:100%; padding:10px; background:#444; color:#fff; border:none; border-radius:5px; margin-top:10px;">Close</button></div></div>`;
            $('body').append(html);
        }).fail(function() {
            alert('Could not load user list.');
            showSlide(currentIndex);
        });
    });

    
    $(document).on('click', '.sendCommentBtn', function() {
        const id = $(this).data('id');
        const input = $(this).siblings('.commentInput');
        const commentText = input.val();
        if(!commentText) return;
        const btn = $(this);
        btn.prop('disabled', true);
        $.post(`/tales/${id}/comment`, {_token: csrfToken, comment: commentText}, function(data) {
            input.val('');
            // Append comment to the list
            const commentHtml = `<div class="comment-item"><strong>${data.username}:</strong> ${$('<span>').text(data.comment).html()}</div>`;
            const list = $(`.comments-list-${id}`);
            list.append(commentHtml);
            list.scrollTop(list[0].scrollHeight);
            // Update count
            $(`.comment-count-${id}`).text(data.comment_count);
            // Open the comments tray so user sees it
            btn.closest('.card').find('.comments-tray').slideDown();
        }).fail(function(xhr) {
            alert(xhr.responseJSON?.error || 'Failed to post comment');
        }).always(function() {
            btn.prop('disabled', false);
        });
    });

    $(document).on('keypress', '.commentInput', function(e) {
        if(e.which === 13) {
            $(this).siblings('.sendCommentBtn').click();
        }
    });

    $(document).on('click', '.comment-trigger', function() {
        $(this).closest('.card').find('.comments-tray').slideDown();
        clearTimeout(taleTimer);
    });

    $(document).on('click', '.close-comments', function() {
        $(this).closest('.comments-tray').slideUp();
        showSlide(currentIndex);
    });

    $(document).on('click', '.likeBtn', function() {
        const id = $(this).data('id');
        const icon = $(this).find('i');
        $.post(`/tales/${id}/like`, {_token: csrfToken}, (data) => {
            $(`#l-${id}`).text(data.likes);
            icon.toggleClass('far fas').css('color', data.isLiked ? 'red' : 'white');
        });
    });

    $(document).on('click', '.dropdown-toggles', function(e) {
        e.stopPropagation();
        $(this).next('.dropdown-menu').toggle();
        clearTimeout(taleTimer);
    });

    $(document).ready(() => showSlide(0));
    $(document).click(() => $('.dropdown-menu').hide());
</script>
</body>
</html>