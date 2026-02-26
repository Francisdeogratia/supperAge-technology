<!DOCTYPE html>
<html lang="en">
<head>
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
    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Post - SupperAge</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

    <style>
    .edit-page {
        max-width: 680px;
        margin: 0 auto;
        padding: 20px 15px 100px;
    }

    .edit-page-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .edit-page-header .back-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e4e6eb;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #050505;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }

    .edit-page-header .back-btn:hover {
        background: #d8dadf;
        text-decoration: none;
        color: #050505;
    }

    .edit-page-header h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #050505;
    }

    .edit-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .edit-card-body {
        padding: 24px;
    }

    .edit-field {
        margin-bottom: 20px;
    }

    .edit-field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #65676b;
        margin-bottom: 8px;
    }

    .edit-field label i {
        margin-right: 6px;
        width: 16px;
        text-align: center;
    }

    .edit-textarea {
        width: 100%;
        min-height: 140px;
        padding: 14px;
        border: 2px solid #e4e6eb;
        border-radius: 10px;
        font-size: 15px;
        resize: vertical;
        transition: border-color 0.2s;
        font-family: inherit;
    }

    .edit-textarea:focus {
        outline: none;
        border-color: #1877f2;
        box-shadow: 0 0 0 3px rgba(24,119,242,0.1);
    }

    .color-row {
        display: flex;
        gap: 16px;
    }

    .color-field {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f0f2f5;
        padding: 10px 14px;
        border-radius: 10px;
    }

    .color-field label {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        color: #65676b;
        white-space: nowrap;
    }

    .color-field input[type="color"] {
        width: 36px;
        height: 36px;
        border: 2px solid #e4e6eb;
        border-radius: 50%;
        cursor: pointer;
        padding: 0;
    }

    .edit-preview {
        margin-bottom: 20px;
        border-radius: 10px;
        padding: 16px;
        min-height: 60px;
        border: 2px dashed #e4e6eb;
        transition: all 0.2s;
    }

    .edit-preview p {
        margin: 0;
        font-size: 15px;
        word-wrap: break-word;
    }

    /* Media section */
    .media-section {
        margin-bottom: 20px;
    }

    .media-section-title {
        font-size: 13px;
        font-weight: 600;
        color: #65676b;
        margin-bottom: 10px;
    }

    .media-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .media-thumb {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        width: 100px;
        height: 100px;
    }

    .media-thumb img,
    .media-thumb video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .media-thumb .remove-media {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(0,0,0,0.6);
        color: #fff;
        border: none;
        font-size: 11px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-area {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .upload-btn-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: #e4e6eb;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #050505;
        cursor: pointer;
        transition: background 0.2s;
    }

    .upload-btn-label:hover {
        background: #d8dadf;
    }

    .edit-actions {
        display: flex;
        gap: 10px;
        padding: 16px 24px;
        border-top: 1px solid #e4e6eb;
        background: #f7f8fa;
    }

    .btn-update {
        flex: 1;
        padding: 12px;
        background: #1877f2;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-update:hover {
        background: #1565c0;
    }

    .btn-update:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-cancel-edit {
        padding: 12px 24px;
        background: #e4e6eb;
        color: #050505;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: background 0.2s;
    }

    .btn-cancel-edit:hover {
        background: #d8dadf;
        text-decoration: none;
        color: #050505;
    }

    /* Toast */
    .edit-toast {
        position: fixed;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        z-index: 9999;
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        max-width: 90vw;
    }

    .edit-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
    .edit-toast.success { background: #00a400; color: #fff; }
    .edit-toast.error { background: #e41e3f; color: #fff; }

    /* Upload progress */
    .upload-progress {
        display: none;
        margin-top: 10px;
    }

    .upload-progress-bar {
        height: 6px;
        background: #e4e6eb;
        border-radius: 3px;
        overflow: hidden;
    }

    .upload-progress-fill {
        height: 100%;
        width: 0%;
        background: #1877f2;
        border-radius: 3px;
        transition: width 0.3s;
    }

    .upload-progress-text {
        font-size: 12px;
        color: #65676b;
        margin-top: 4px;
    }

    /* Dark mode */
    body.dark-mode .edit-page-header h4 { color: #E4E6EB; }
    body.dark-mode .edit-page-header .back-btn { background: #3A3B3C; color: #E4E6EB; }
    body.dark-mode .edit-page-header .back-btn:hover { background: #4E4F50; }
    body.dark-mode .edit-card { background: #242526; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
    body.dark-mode .edit-field label { color: #B0B3B8; }
    body.dark-mode .edit-textarea { background: #3A3B3C; border-color: #3E4042; color: #E4E6EB; }
    body.dark-mode .edit-textarea:focus { border-color: #2D88FF; box-shadow: 0 0 0 3px rgba(45,136,255,0.2); }
    body.dark-mode .color-field { background: #3A3B3C; }
    body.dark-mode .color-field label { color: #B0B3B8; }
    body.dark-mode .color-field input[type="color"] { border-color: #3E4042; }
    body.dark-mode .edit-preview { border-color: #3E4042; }
    body.dark-mode .media-section-title { color: #B0B3B8; }
    body.dark-mode .upload-btn-label { background: #3A3B3C; color: #E4E6EB; }
    body.dark-mode .upload-btn-label:hover { background: #4E4F50; }
    body.dark-mode .edit-actions { background: #1C1D1E; border-top-color: #3E4042; }
    body.dark-mode .btn-cancel-edit { background: #3A3B3C; color: #E4E6EB; }
    body.dark-mode .btn-cancel-edit:hover { background: #4E4F50; }
    body.dark-mode .upload-progress-bar { background: #3A3B3C; }
    body.dark-mode .upload-progress-text { color: #B0B3B8; }

    @media (max-width: 600px) {
        .edit-page { padding: 12px 10px 100px; }
        .edit-card-body { padding: 16px; }
        .edit-actions { padding: 12px 16px; }
        .color-row { flex-direction: column; gap: 10px; }
        .media-thumb { width: 80px; height: 80px; }
    }
    </style>
</head>
<body>
@include('layouts.navbar')

<div class="edit-page">
    <div class="edit-page-header">
        <a href="{{ route('posts.all') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>
        <h4>Edit Post</h4>
    </div>

    <form id="editPostForm" action="{{ route('posts.update', $post->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="edit-card">
            <div class="edit-card-body">
                <!-- Content -->
                <div class="edit-field">
                    <label><i class="fas fa-pen"></i> Post Content</label>
                    <textarea name="post_content" id="postContent" class="edit-textarea" maxlength="1000">{{ $post->post_content }}</textarea>
                </div>

                <!-- Colors -->
                <div class="edit-field">
                    <label><i class="fas fa-palette"></i> Colors</label>
                    <div class="color-row">
                        <div class="color-field">
                            <label>Text</label>
                            <input type="color" name="colorpicker" id="textColorPick" value="{{ $post->text_color ?? '#ffffff' }}">
                        </div>
                        <div class="color-field">
                            <label>Background</label>
                            <input type="color" name="bgColorPicker" id="bgColorPick" value="{{ $post->bgnd_color ?? '#000000' }}">
                        </div>
                    </div>
                </div>

                <!-- Live Preview -->
                <div class="edit-field">
                    <label><i class="fas fa-eye"></i> Preview</label>
                    <div class="edit-preview" id="livePreview" style="background-color: {{ $post->bgnd_color ?? '#000000' }}; color: {{ $post->text_color ?? '#ffffff' }};">
                        <p id="previewText">{{ $post->post_content }}</p>
                    </div>
                </div>

                <!-- Existing Media -->
                @php $existingMedia = json_decode($post->file_path, true) ?? []; @endphp
                <div class="media-section">
                    <div class="media-section-title"><i class="fas fa-photo-video"></i> Media</div>
                    <div class="media-grid" id="mediaGrid">
                        @foreach($existingMedia as $i => $file)
                            @php
                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                $isVideo = in_array($ext, ['mp4','webm','ogg']);
                            @endphp
                            <div class="media-thumb" data-url="{{ $file }}">
                                @if($isVideo)
                                    <video src="{{ $file }}" muted></video>
                                @else
                                    <img src="{{ $file }}" alt="Media">
                                @endif
                                <button type="button" class="remove-media" onclick="removeMedia(this)"><i class="fas fa-times"></i></button>
                            </div>
                        @endforeach
                    </div>

                    <div class="upload-area">
                        <label for="newMediaFiles" class="upload-btn-label">
                            <i class="fas fa-cloud-upload-alt"></i> Add Media
                        </label>
                        <input type="file" id="newMediaFiles" multiple accept="image/*,video/*" style="display:none;">
                    </div>

                    <div class="upload-progress" id="uploadProgress">
                        <div class="upload-progress-bar"><div class="upload-progress-fill" id="progressFill"></div></div>
                        <div class="upload-progress-text" id="progressText">Uploading...</div>
                    </div>
                </div>

                <!-- Hidden field for media URLs -->
                <input type="hidden" name="media_urls" id="mediaUrlsInput">
            </div>

            <div class="edit-actions">
                <a href="{{ route('posts.all') }}" class="btn-cancel-edit">Cancel</a>
                <button type="submit" class="btn-update" id="updateBtn">
                    <i class="fas fa-check"></i> Update Post
                </button>
            </div>
        </div>
    </form>
</div>

<div class="edit-toast" id="editToast"></div>
<audio id="successSound" src="{{ asset('/sounds/mixkit-fantasy-game-success-notification-270.wav') }}" preload="auto"></audio>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
// Track existing media URLs
var existingUrls = @json($existingMedia);

function showEditToast(msg, type) {
    var t = document.getElementById('editToast');
    t.textContent = msg;
    t.className = 'edit-toast ' + type;
    t.offsetHeight;
    t.classList.add('show');
    setTimeout(function() { t.classList.remove('show'); }, 3000);
}

// Remove existing media
function removeMedia(btn) {
    var thumb = btn.closest('.media-thumb');
    var url = thumb.getAttribute('data-url');
    existingUrls = existingUrls.filter(function(u) { return u !== url; });
    thumb.remove();
}

// Live preview
$('#postContent').on('input', function() {
    $('#previewText').text($(this).val() || 'Your post will appear like this...');
});

$('#textColorPick').on('input', function() {
    $('#livePreview').css('color', $(this).val());
});

$('#bgColorPick').on('input', function() {
    $('#livePreview').css('background-color', $(this).val());
});

// New file preview
$('#newMediaFiles').on('change', function() {
    var files = this.files;
    var grid = $('#mediaGrid');
    Array.from(files).forEach(function(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var isVid = file.type.startsWith('video/');
            var html = '<div class="media-thumb" data-new="true">';
            if (isVid) {
                html += '<video src="' + e.target.result + '" muted></video>';
            } else {
                html += '<img src="' + e.target.result + '" alt="New">';
            }
            html += '<button type="button" class="remove-media" onclick="this.closest(\'.media-thumb\').remove()"><i class="fas fa-times"></i></button>';
            html += '</div>';
            grid.append(html);
        };
        reader.readAsDataURL(file);
    });
});

// Submit form
$('#editPostForm').on('submit', function(e) {
    e.preventDefault();

    var btn = document.getElementById('updateBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

    var files = document.getElementById('newMediaFiles').files;
    var newUploadedUrls = [];
    var completed = 0;
    var validCount = 0;

    // Count valid files
    for (var i = 0; i < files.length; i++) {
        if (files[i].size <= 100 * 1024 * 1024) validCount++;
    }

    if (validCount === 0) {
        submitUpdate(existingUrls);
        return;
    }

    // Show progress
    $('#uploadProgress').show();

    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        if (file.size > 100 * 1024 * 1024) {
            showEditToast(file.name + ' is too large (max 100MB). Skipped.', 'error');
            continue;
        }

        (function(f) {
            var cloudData = new FormData();
            cloudData.append('file', f);
            cloudData.append('upload_preset', 'francis');

            var endpoint = f.type.startsWith('video')
                ? 'https://api.cloudinary.com/v1_1/djaqqrwoi/video/upload'
                : 'https://api.cloudinary.com/v1_1/djaqqrwoi/image/upload';

            $.ajax({
                url: endpoint,
                type: 'POST',
                data: cloudData,
                contentType: false,
                processData: false,
                success: function(res) {
                    newUploadedUrls.push(res.secure_url);
                    completed++;
                    var pct = Math.round((completed / validCount) * 100);
                    $('#progressFill').css('width', pct + '%');
                    $('#progressText').text('Uploaded ' + completed + ' of ' + validCount);
                    if (completed === validCount) {
                        submitUpdate(existingUrls.concat(newUploadedUrls));
                    }
                },
                error: function() {
                    completed++;
                    showEditToast('Failed to upload ' + f.name, 'error');
                    if (completed === validCount) {
                        submitUpdate(existingUrls.concat(newUploadedUrls));
                    }
                }
            });
        })(file);
    }
});

function submitUpdate(allMediaUrls) {
    var formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('_token', $('input[name="_token"]').val());
    formData.append('post_content', $('#postContent').val());
    formData.append('colorpicker', $('#textColorPick').val());
    formData.append('bgColorPicker', $('#bgColorPick').val());

    if (allMediaUrls.length > 0) {
        formData.append('media_urls', JSON.stringify(allMediaUrls));
    }

    $.ajax({
        url: $('#editPostForm').attr('action'),
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function() {
            showEditToast('Post updated successfully!', 'success');
            try {
                var snd = document.getElementById('successSound');
                snd.currentTime = 0;
                snd.play();
            } catch(e) {}
            setTimeout(function() {
                window.location.href = "{{ route('posts.all') }}";
            }, 1500);
        },
        error: function(xhr) {
            var msg = 'Failed to update post.';
            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            showEditToast(msg, 'error');
            var btn = document.getElementById('updateBtn');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Update Post';
            $('#uploadProgress').hide();
        }
    });
}
</script>

@include('partials.global-calls')
</body>
</html>
