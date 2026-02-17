$(document).ready(function() {

// ✅ NEW: Link Preview Variables
let linkPreviewData = null;
let urlDetectionTimeout = null;

// Smooth toggle
$(".showmemore").click(function() {
    $("#left-column").delay(400).fadeToggle(500);
});
  
// File preview handler - modern horizontal scroll
$('#media').on('change', function(event) {
    const files = event.target.files;
    const preview = $('#previewmedia');
    preview.empty();

    if (files.length === 0) return;

    // Show count label
    preview.append(`<div class="media-count-label"><i class="fas fa-paperclip"></i> ${files.length} file${files.length > 1 ? 's' : ''} selected</div>`);

    const scrollWrap = $('<div class="media-preview-scroll"></div>');

    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            let thumb = '';
            if (file.type.startsWith('image/')) {
                thumb = `
                    <div class="media-thumb" data-index="${index}">
                        <img src="${e.target.result}" alt="${file.name}" />
                        <button type="button" class="media-thumb-remove" data-index="${index}"><i class="fas fa-times"></i></button>
                        <span class="media-thumb-name">${file.name.length > 12 ? file.name.substring(0, 12) + '...' : file.name}</span>
                    </div>`;
            } else if (file.type.startsWith('video/')) {
                thumb = `
                    <div class="media-thumb media-thumb-video" data-index="${index}">
                        <video src="${e.target.result}" muted></video>
                        <div class="media-thumb-play"><i class="fas fa-play"></i></div>
                        <button type="button" class="media-thumb-remove" data-index="${index}"><i class="fas fa-times"></i></button>
                        <span class="media-thumb-name">${file.name.length > 12 ? file.name.substring(0, 12) + '...' : file.name}</span>
                    </div>`;
            }
            scrollWrap.append(thumb);
        };
        reader.readAsDataURL(file);
    });

    preview.append(scrollWrap);
});

// Remove file preview
$(document).on('click', '.media-thumb-remove', function(e) {
    e.preventDefault();
    const index = $(this).data('index');
    $(`.media-thumb[data-index="${index}"]`).fadeOut(200, function() { $(this).remove(); });
});

 
// Dark/Light mode color picker defaults
function setColorPickerDefaults() {
    const isDark = $('body').hasClass('dark-mode');
    // Create Post modal
    if (isDark) {
        $('#colorpickers').val('#ffffff');
        $('#bgColorPickers').val('#242526');
        $('#colorPreview').css({ 'color': '#ffffff', 'background-color': '#242526' });
        $('#previewText').css('color', '#ffffff');
    } else {
        $('#colorpickers').val('#000000');
        $('#bgColorPickers').val('#ffffff');
        $('#colorPreview').css({ 'color': '#000000', 'background-color': '#ffffff' });
        $('#previewText').css('color', '#000000');
    }
    // Tales modal
    if (isDark) {
        $('#colorpicker').val('#ffffff');
        $('#bgColorPicker').val('#242526');
        $('#previewArea').css({ 'color': '#ffffff', 'background-color': '#242526' });
    } else {
        $('#colorpicker').val('#000000');
        $('#bgColorPicker').val('#ffffff');
        $('#previewArea').css({ 'color': '#000000', 'background-color': '#ffffff' });
    }
}
// Set defaults on page load (with delay to ensure dark mode class is applied first)
setTimeout(setColorPickerDefaults, 300);
// Re-apply when dark mode is toggled
$(document).on('click', '#darkModeBtn, #darkModeToggle, .dark-toggle', function() {
    setTimeout(setColorPickerDefaults, 150);
});
// Re-apply when modals are opened (ensures correct colors even if dark mode loaded late)
$(document).on('show.bs.modal', '#myModal, #myModal3', function() {
    setTimeout(setColorPickerDefaults, 100);
});

// Live color preview
$('#colorpickers, #bgColorPickers, #post_contents').on('input', function () {
    const textColor = $('#colorpickers').val();
    const bgColor = $('#bgColorPickers').val();
    const content = $('#post_contents').val();

    $('#colorPreview').css({
        'color': textColor,
        'background-color': bgColor
    });

    $('#previewText').css('color', textColor).text(content || 'Your post preview will appear here...');
});

// showing hashtag
$('#post_contents').on('input', function () {
    const content = $(this).val();
    const styled = content
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;') // Prevent HTML injection
        .replace(/>/g, '&gt;')
        .replace(/(#\w+)/g, '<span style="color:red;">$1</span>')
        .replace(/\n/g, '<br>');
        
    $('#styledText').html(styled);

    // ✅ NEW: Detect URLs for link preview
    clearTimeout(urlDetectionTimeout);
    urlDetectionTimeout = setTimeout(() => {
        detectAndPreviewLinks(content);
    }, 1000);
});

// ✅ NEW: Link Preview Functions
function detectAndPreviewLinks(text) {
    const urlRegex = /(https?:\/\/[^\s]+)/gi;
    const urls = text.match(urlRegex);
    
    if (urls && urls.length > 0) {
        const firstUrl = urls[0];
        fetchLinkPreview(firstUrl);
    } else {
        clearLinkPreview();
    }
}

function fetchLinkPreview(url) {
    $('#linkPreviewContainer').html(`
        <div class="link-preview-loading">
            <i class="fas fa-spinner fa-spin"></i> Loading preview...
        </div>
    `).show();

    $.ajax({
        url: '/fetch-link-preview',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            url: url
        },
        success: function(data) {
            linkPreviewData = data;
            displayLinkPreview(data);
        },
        error: function(xhr) {
            console.error('Failed to fetch link preview:', xhr);
            clearLinkPreview();
        }
    });
}

function displayLinkPreview(data) {
    const previewHtml = `
        <div class="link-preview-card">
            <button type="button" class="link-preview-close" onclick="window.removeLinkPreview()">
                <i class="fas fa-times"></i>
            </button>
            ${data.image ? `
                <div class="link-preview-image">
                    <img src="${data.image}" alt="${data.title}" onerror="this.parentElement.style.display='none'">
                </div>
            ` : ''}
            <div class="link-preview-content">
                <div class="link-preview-site">
                    ${data.favicon ? `<img src="${data.favicon}" class="link-preview-favicon" onerror="this.style.display='none'">` : ''}
                    <span>${data.site_name}</span>
                </div>
                <h4 class="link-preview-title">${data.title}</h4>
                ${data.description ? `<p class="link-preview-description">${data.description}</p>` : ''}
                <a href="${data.url}" class="link-preview-url" target="_blank" rel="noopener">
                    <i class="fas fa-external-link-alt"></i> ${data.url}
                </a>
            </div>
        </div>
    `;
    
    $('#linkPreviewContainer').html(previewHtml).show();
}

function clearLinkPreview() {
    linkPreviewData = null;
    $('#linkPreviewContainer').empty().hide();
}

// Make removeLinkPreview global
window.removeLinkPreview = function() {
    clearLinkPreview();
};


// Post submission
$('#post_form').on('submit', function(event) {
    event.preventDefault();
    $('#loading').show();

    const files = $('#media')[0].files;
    let postContent = $('#post_contents').val(); 
    const uploadedUrls = [];
    let completed = 0;
    let validFileCount = 0;

    $('#progressContainer').show();
    $('#progressBar').css('width', '0%');
    $('#progressText').text('Uploading...');

    // 1. Determine valid files and set up uploads
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.size > 100 * 1024 * 1024) {
            alert(file.name + " is too large. Skipping.");
            continue;
        }

        validFileCount++;
        const cloudData = new FormData();
        cloudData.append("file", file);
        cloudData.append("upload_preset", "francis");

        const endpoint = file.type.startsWith("video")
            ? "https://api.cloudinary.com/v1_1/djaqqrwoi/video/upload"
            : "https://api.cloudinary.com/v1_1/djaqqrwoi/image/upload";

        const xhr = new XMLHttpRequest();

        // Setup progress handler
        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const totalUploadProgress = completed + (e.loaded / e.total);
                const percent = Math.round((totalUploadProgress / Math.max(validFileCount, 1)) * 100);
                
                $('#progressBar').css('width', percent + '%');
                $('#progressText').text(percent + '% uploaded');
            }
        };

        // Setup complete/error handler
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const res = JSON.parse(xhr.responseText);
                        uploadedUrls.push(res.secure_url);
                    } catch (e) {
                         alert("Upload success, but failed to parse response for " + file.name);
                    }
                } else {
                    alert("Failed to upload " + file.name + ". Status: " + xhr.status);
                    console.error("Cloudinary Upload Error:", xhr.responseText);
                }
                checkIfDone();
            }
        };

        xhr.open("POST", endpoint, true);
        xhr.send(cloudData);
    }

    // 2. Handle initial checks (text-only or file-only post)
    if (files.length === 0 || validFileCount === 0) {
        if (!postContent) {
            alert("No valid content to post.");
            $('#loading').hide();
            $('#progressContainer').hide();
            return;
        } else {
            submitFinalPost();
        }
    }

    // 3. Check if all uploads are complete
    function checkIfDone() {
        completed++;
        if (completed === validFileCount) {
            if (!postContent.trim()) {
                postContent = '[media-only post]'; 
            }
            submitFinalPost();
        }
    }
    
    // 4. Final Submission to Laravel Backend
    function submitFinalPost() {
        $('#progressBar').css('width', '100%');
        $('#progressText').text('100% Uploaded. Finalizing post...');
        
        const postData = new FormData();
        postData.append("post_content", postContent);
        postData.append("file_path", JSON.stringify(uploadedUrls));
        postData.append("action", "insert");
        postData.append("colorpicker", $('#colorpickers').val());
        postData.append("bgColorPicker", $('#bgColorPickers').val());
        postData.append("status", $('select[name="status"]').val()); 
        postData.append("scheduled_at", $('input[name="scheduled_at"]').val());
        
        // ✅ NEW: Include link preview data
        if (linkPreviewData) {
            postData.append("link_preview", JSON.stringify(linkPreviewData));
        }

        $.ajax({
            url: '/submit-post',
            method: 'POST',
            data: postData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#successPopup').fadeIn(400);

                const sound = document.getElementById('successSound');
                sound.currentTime = 0;
                sound.play().catch(() => {
                    console.warn("Sound playback blocked by browser.");
                });

                setTimeout(() => {
                    $('#successPopup').fadeOut(400);
                    location.reload(); 
                }, 3000);

                $('#postmsg').html(data);
                $('#loading').hide();
                $('#progressContainer').hide();
                $("#myModal").hide();
                $('#post_form')[0].reset();
                $('#previewmedia').empty();
                $('#post_contents').val('');
                clearLinkPreview(); // ✅ NEW: Clear link preview on success
            },
            error: function(xhr) {
                alert("Failed to save post. Server error details logged in console.");
                console.error("Laravel Submission Error:", xhr.responseText);
                $('#loading').hide();
                $('#progressContainer').hide();
            }
        });
    }
});

});