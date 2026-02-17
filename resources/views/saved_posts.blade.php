<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <title>Saved Posts - SupperAge</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f0f2f5; color: #1c1e21; }

        .sp-topbar {
            background: #fff; padding: 12px 20px; display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid #ddd; position: sticky; top: 0; z-index: 100;
        }
        .sp-topbar a { color: #1877f2; text-decoration: none; font-size: 20px; }
        .sp-topbar h1 { font-size: 18px; font-weight: 600; }

        .sp-container { max-width: 680px; margin: 20px auto; padding: 0 12px; }

        .sp-empty {
            text-align: center; padding: 60px 20px; background: #fff;
            border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .sp-empty i { font-size: 48px; color: #bbb; margin-bottom: 12px; }
        .sp-empty p { color: #65676b; font-size: 15px; }

        .sp-card {
            background: #fff; border-radius: 10px; margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;
        }
        .sp-card-header {
            display: flex; align-items: center; padding: 12px 16px; gap: 10px;
        }
        .sp-card-avatar {
            width: 40px; height: 40px; border-radius: 50%; object-fit: cover;
        }
        .sp-card-author { flex: 1; }
        .sp-card-author a { text-decoration: none; color: #1c1e21; font-weight: 600; font-size: 14px; }
        .sp-card-meta { font-size: 12px; color: #65676b; }

        .sp-card-body { padding: 0 16px 12px; }
        .sp-card-body p { font-size: 15px; line-height: 1.4; margin-bottom: 8px; }

        .sp-card-media { width: 100%; max-height: 500px; object-fit: cover; }
        .sp-card-media-video { width: 100%; max-height: 500px; }

        .sp-card-actions { padding: 8px 16px 12px; display: flex; gap: 10px; }
        .sp-btn {
            padding: 6px 14px; border-radius: 6px; border: none; cursor: pointer;
            font-size: 13px; font-weight: 500;
        }
        .sp-btn-view { background: #1877f2; color: #fff; text-decoration: none; }
        .sp-btn-view:hover { background: #166fe5; }
        .sp-btn-unsave { background: #f0f0f0; color: #dc3545; }
        .sp-btn-unsave:hover { background: #e4e4e4; }
    </style>
</head>
<body>

<div class="sp-topbar">
    <a href="{{ route('update') }}"><i class="fa fa-arrow-left"></i></a>
    <h1><i class="fa fa-bookmark"></i> Saved Posts</h1>
</div>

<div class="sp-container">
    @if($savedPosts->isEmpty())
        <div class="sp-empty">
            <i class="fa fa-bookmark"></i>
            <p>You haven't saved any posts yet.</p>
            <p style="margin-top:8px;"><a href="{{ route('update') }}" style="color:#1877f2;">Browse posts</a></p>
        </div>
    @else
        @foreach($savedPosts as $savedPost)
            @php
                $post = $savedPost->post;
                $author = $post->user;
                $files = json_decode($post->file_path, true);
                $files = is_array($files) ? $files : [];
                $firstFile = count($files) > 0 ? $files[0] : null;
            @endphp
            <div class="sp-card" id="saved-card-{{ $post->id }}">
                <div class="sp-card-header">
                    @if($author)
                    <a href="{{ route('profile.show', $author->id) }}">
                        <img src="{{ $author->profileimg ?? asset('images/best3.png') }}" class="sp-card-avatar" alt="">
                    </a>
                    <div class="sp-card-author">
                        <a href="{{ route('profile.show', $author->id) }}">{{ $author->name }}</a>
                        @if($author->badge_status)
                            <img src="{{ asset($author->badge_status) }}" alt="Verified" style="width:14px;height:14px;vertical-align:middle;">
                        @endif
                        <div class="sp-card-meta">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</div>
                    </div>
                    @else
                    <div class="sp-card-author">
                        <span style="font-weight:600;">Unknown user</span>
                        <div class="sp-card-meta">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</div>
                    </div>
                    @endif
                </div>

                @if($post->post_content)
                <div class="sp-card-body" style="color: {{ $post->text_color ?? '#000' }}; background-color: {{ $post->bgnd_color ?? '#fff' }};">
                    <p>{!! nl2br(e(\Illuminate\Support\Str::limit(strip_tags($post->post_content), 200))) !!}</p>
                </div>
                @endif

                @if($firstFile)
                    @php
                        $ext = strtolower(pathinfo(parse_url($firstFile, PHP_URL_PATH), PATHINFO_EXTENSION));
                        $isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
                    @endphp
                    @if($isVideo)
                        <video class="sp-card-media-video" controls preload="metadata">
                            <source src="{{ $firstFile }}">
                        </video>
                    @else
                        <img src="{{ $firstFile }}" class="sp-card-media" alt="Post media">
                    @endif
                @endif

                <div class="sp-card-actions">
                    <a href="{{ route('posts.show', $post->id) }}" class="sp-btn sp-btn-view">View post</a>
                    <button class="sp-btn sp-btn-unsave" onclick="unsavePost({{ $post->id }}, this)">
                        <i class="fa fa-bookmark"></i> Unsave
                    </button>
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
function unsavePost(postId, btn) {
    fetch('{{ route("posts.toggleSave") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ post_id: postId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'unsaved') {
            const card = document.getElementById('saved-card-' + postId);
            if (card) {
                card.style.transition = 'opacity 0.3s';
                card.style.opacity = '0';
                setTimeout(() => card.remove(), 300);
            }
        }
    })
    .catch(err => console.error('Unsave failed:', err));
}
</script>
</body>
</html>
