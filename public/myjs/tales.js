$(document).ready(function() {

// ✅ NEW: Link Preview Variables for Tales
let talesLinkPreviewData = null;
let talesUrlDetectionTimeout = null;

// tales preview
$('#tales_files').on('change', function () {
    const file = this.files[0];
    const previewArea = $('#previewArea');
    previewArea.html(''); // Clear previous preview

    if (!file) return;

    const fileType = file.type;
    const reader = new FileReader();

    reader.onload = function (e) {
        let previewElement;

        if (fileType.startsWith('image')) {
            previewElement = `<img src="${e.target.result}" style="max-width:100%; border-radius:10px;" />`;
        } else if (fileType.startsWith('video')) {
            previewElement = `<video controls style="max-width:100%; border-radius:10px;"><source src="${e.target.result}" type="${fileType}"></video>`;
        } else if (fileType.startsWith('audio')) {
            previewElement = `<audio controls><source src="${e.target.result}" type="${fileType}"></audio>`;
        } else {
            previewElement = `<p>File type not supported for preview.</p>`;
        }

        previewArea.html(previewElement);
    };

    reader.readAsDataURL(file);
});

// Apply text color
$('#colorpicker').on('input', function () {
    const textColor = $(this).val();
    $('#tales_msg').css('color', textColor);
    $('#previewArea').css('color', textColor);
});

// Apply background color
$('#bgColorPicker').on('input', function () {
    const bgColor = $(this).val();
    $('#tales_msg').css('background-color', bgColor);
    $('#previewArea').css('background-color', bgColor);
});

// ✅ UPDATED: Mirror text to previewArea + detect URLs for link preview
$('#tales_msg').on('input', function () {
    const text = $(this).val();
    $('#previewArea').text(text);
    
    // ✅ NEW: Detect URLs for link preview
    clearTimeout(talesUrlDetectionTimeout);
    talesUrlDetectionTimeout = setTimeout(() => {
        detectAndPreviewTalesLinks(text);
    }, 1000);
});

// ✅ NEW: Link Preview Functions for Tales
function detectAndPreviewTalesLinks(text) {
    const urlRegex = /(https?:\/\/[^\s]+)/gi;
    const urls = text.match(urlRegex);
    
    if (urls && urls.length > 0) {
        const firstUrl = urls[0];
        fetchTalesLinkPreview(firstUrl);
    } else {
        clearTalesLinkPreview();
    }
}

function fetchTalesLinkPreview(url) {
    // Check if container exists, if not create it
    if ($('#talesLinkPreviewContainer').length === 0) {
        $('#previewArea').after('<div id="talesLinkPreviewContainer" style="display:none; margin-top:15px;"></div>');
    }
    
    $('#talesLinkPreviewContainer').html(`
        <div class="link-preview-loading">
            <i class="fas fa-spinner fa-spin"></i> Loading preview...
        </div>
    `).show();

    $.ajax({
        url: '/tales/fetch-link-preview', // ✅ FIXED: Changed from /posts/ to /tales/
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            url: url
        },
        success: function(data) {
            talesLinkPreviewData = data;
            displayTalesLinkPreview(data);
        },
        error: function(xhr) {
            console.error('Failed to fetch tales link preview:', xhr);
            console.error('Response:', xhr.responseText);
            clearTalesLinkPreview();
        }
    });
}

function displayTalesLinkPreview(data) {
    const previewHtml = `
        <div class="link-preview-card">
            <button type="button" class="link-preview-close" onclick="window.removeTalesLinkPreview()">
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
    
    $('#talesLinkPreviewContainer').html(previewHtml).show();
}

function clearTalesLinkPreview() {
    talesLinkPreviewData = null;
    $('#talesLinkPreviewContainer').empty().hide();
}

// Make removeTalesLinkPreview global
window.removeTalesLinkPreview = function() {
    clearTalesLinkPreview();
};

// ✅ UPDATED: sharetales with link preview support
$("#mytales-frm").on('submit', function (e) {
  e.preventDefault();
  $("#loader4").show();
  $('#progressSection').show(); // ✅ Show progress section

  const talesCat = $('#tales_cat').val();
  const talesMsg = $('#tales_msg').val();
  const fileInput = $('#tales_files')[0];
  const file = fileInput.files[0];

  if (!talesCat) {
    alert("Please select the type of tales you want to share!");
    $("#loader4").hide();
    $('#progressSection').hide();
    return;
  }

  if (!talesMsg && !file) {
    alert("Please write or select tales you want to share!");
    $("#loader4").hide();
    $('#progressSection').hide();
    return;
  }

  const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'audio/mp3', 'audio/mpeg'];
  const maxSize = 20 * 1024 * 1024; // 20MB

  if (file) {
    if (!allowedTypes.includes(file.type)) {
      alert('Only JPEG, PNG, GIF, MP4, and MP3 files are allowed.');
      $("#loader4").hide();
      $('#progressSection').hide();
      return;
    }

    if (file.size > maxSize) {
      alert('File size must be less than 20MB.');
      $("#loader4").hide();
      $('#progressSection').hide();
      return;
    }

    const cloudData = new FormData();
    cloudData.append("file", file);
    cloudData.append("upload_preset", "francis");

    // ✅ FIXED: Handle both video and audio files properly
    const endpoint = (file.type.startsWith("video") || file.type.startsWith("audio"))
      ? "https://api.cloudinary.com/v1_1/djaqqrwoi/video/upload"
      : "https://api.cloudinary.com/v1_1/djaqqrwoi/image/upload";

    const xhr = new XMLHttpRequest();
    
    xhr.upload.addEventListener("progress", function (evt) {
        if (evt.lengthComputable) {
            const percentComplete = Math.round((evt.loaded / evt.total) * 100);
            $("#uploadProgressBar").css("width", percentComplete + "%")
                                   .attr("aria-valuenow", percentComplete)
                                   .text(percentComplete + "%");
        }
    }, false);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            $("#uploadProgressBar").css("width", "0%").attr("aria-valuenow", 0).text("0%");
            $('#progressSection').hide();
            
            if (xhr.status === 200) {
                const res = JSON.parse(xhr.responseText);
                const cloudinaryUrl = res.secure_url;
                
                const formData = new FormData();
                formData.append('_token', $('input[name="_token"]').val());
                formData.append('tales_cat', talesCat);
                formData.append('tales_msg', talesMsg);
                formData.append('colorpicker', $('#colorpicker').val());
                formData.append('bgColorPicker', $('#bgColorPicker').val());
                formData.append('action', 'talestype');
                formData.append('tales_file_url', cloudinaryUrl);
                
                if (talesLinkPreviewData) {
                    formData.append('link_preview', JSON.stringify(talesLinkPreviewData));
                }

                $.ajax({
                    url: '/share-tale',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#successPopup').text('✅ Tale shared successfully!').fadeIn(400);
                        const sound = document.getElementById('successSound');
                        if (sound) {
                            sound.currentTime = 0;
                            sound.play().catch(() => {
                                console.warn("Sound playback blocked by browser.");
                            });
                        }
                        setTimeout(() => {
                            $('#successPopup').fadeOut(400);
                            $('#talesmsg').html(response.message);
                            $("#loader4").hide();
                            $("#myModal3").modal("hide");
                            $('#mytales-frm')[0].reset();
                            clearTalesLinkPreview();
                            location.reload();
                        }, 3000);
                    },
                    error: function (xhr) {
                        console.error('Laravel submission error:', xhr.responseText);
                        alert("Something went wrong with submission. Please try again.");
                        $("#loader4").hide();
                    }
                });

            } else {
                console.error('Cloudinary upload failed:', xhr.responseText);
                alert("Failed to upload " + file.name + ". Cloudinary Error Status: " + xhr.status);
                $("#loader4").hide();
            }
        }
    };
    
    xhr.open("POST", endpoint, true);
    xhr.send(cloudData);

  } else {
    // No file, just text
    $('#progressSection').hide();
    
    const formData = new FormData();
    formData.append('_token', $('input[name="_token"]').val());
    formData.append('tales_cat', talesCat);
    formData.append('tales_msg', talesMsg);
    formData.append('colorpicker', $('#colorpicker').val());
    formData.append('bgColorPicker', $('#bgColorPicker').val());
    formData.append('action', 'talestype');
    
    if (talesLinkPreviewData) {
        formData.append('link_preview', JSON.stringify(talesLinkPreviewData));
    }

    $.ajax({
      url: '/share-tale',
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        $('#successPopup').text('✅ Tale shared successfully!').fadeIn(400);
        const sound = document.getElementById('successSound');
        if (sound) {
            sound.currentTime = 0;
            sound.play().catch(() => {
                console.warn("Sound playback blocked by browser.");
            });
        }
        setTimeout(() => {
            $('#successPopup').fadeOut(400);
            $('#talesmsg').html(response.message);
            $("#loader4").hide();
            $("#myModal3").modal("hide");
            $('#mytales-frm')[0].reset();
            clearTalesLinkPreview();
            location.reload();
            fetch_tales();
        }, 3000);
      },
      error: function (xhr) {
        console.error('Submission error:', xhr.responseText);
        alert("Something went wrong. Please try again.");
        $("#loader4").hide();
      }
    });
  }
});

// fetch tales
fetch_tales();
function fetch_tales() {
  $.ajax({
    url: '/fetch-tales',
    method: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) {
      $('.scroll-container').html(response.html);
    },
    error: function(xhr) {
      console.error("Failed to fetch tales:", xhr.responseText);
    }
  });
}

setInterval(fetch_tales, 60000);

});