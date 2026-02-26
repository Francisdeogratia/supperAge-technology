

<!-- @extends('layouts.app') -->

@section('content')

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
    <meta name="author" content="omoha Ekenedilichukwu Francis">
    <meta name="description" content="SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money.">
    <meta name="keywords" content="SupperAge, social financial app, earn money online, chat and earn, online marketplace, digital wallet, social networking, e-commerce platform">
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />

    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>editing of tales</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <!-- Stylesheets -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    

    <!-- Scripts -->
  
    
    
</head>
<body>
<div style="display:flex;align-items:center; justify-content:center;">
    <div style="width:400px;">
        <!-- Back Arrow Button -->
      <a href="{{ route('view.tale', $tale->tales_id) }}" style="margin-right: 10px; font-size: 20px; float:right; margin-top:25px;">
  <i class="fa fa-arrow-left"></i>Back
</a>


  <h2>Edit Tale</h2>

  @if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
  @endif

  <div id="custom-message-container" style="margin-bottom: 15px;">
            </div>


  <form action="{{ route('tale.update', $tale->tales_id) }}" method="POST" enctype="multipart/form-data">

    @csrf
    @method('PUT')

<div class="form-group">
    <label for="tales_content">Edit your Tale Content:</label><br>
    <textarea class="form-control" name="tales_content" rows="6" style="width:100%;">{{ $tale->tales_content }}</textarea>
</div>
<div class="form-group">
  <label for="tales_file_url">Update Tale File (image/video):</label>
  <input type="file" name="tales_file_url" id="tales_file_url" class="form-control" accept="image/*,video/*">
  <input type="hidden" name="cloudinary_url" id="cloudinary_url">
<i class="fa fa-spinner fa-spin m-2" style="position:absolute;top:50%;right:50%;font-size:40px;color:green; display:none;" id="loader4"></i></span> 
</div>

<!-- Preview Area -->
<div id="filePreview" style="margin-top: 15px;width:100px;"></div>
<!-- Upload Progress Bar -->
<div class="form-group">
  <label>Upload Progress:</label>
  <div class="progress">
    <div id="uploadProgressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
  </div>
</div>

<div class="form-group" style="border-radius:25px;">
   <button type="submit" id="submitBtn" class="btn btn-success btn-block" style="border-radius:25px;">Update Tale</button>


    </div>
  </form>
@endsection
</div>
</div>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // NOTE: Ensure you have included Bootstrap JS for the alert fade to work (added in the HTML above)
    
    
    // Custom function to display messages
    function showCustomMessage(message, type) {
        // Map type to Bootstrap alert classes
        let alertClass = '';
        switch (type) {
            case 'success':
                alertClass = 'alert-success';
                break;
            case 'error':
                alertClass = 'alert-danger';
                break;
            case 'info':
            default:
                alertClass = 'alert-info';
                break;
        }

        const messageHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        
        // Append message and auto-close after 5 seconds
        const container = $('#custom-message-container');
        container.append(messageHtml);
        
        // Find the newly appended alert and fade it out
        const newAlert = container.children('.alert').last();
        setTimeout(function() {
            newAlert.alert('close');
        }, 5000); // Message disappears after 5 seconds
    }
    
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('tales_file_url');
        const preview = document.getElementById('filePreview');
        const cloudinaryInput = document.getElementById('cloudinary_url');
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submitBtn');
        const progressBar = document.getElementById('uploadProgressBar');
        const loader = document.getElementById('loader4');

        // Initial preview setup (if an existing file URL is present on the page)

        // Clear file URL on file change, but DON'T upload yet
        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            preview.innerHTML = '';
            cloudinaryInput.value = ''; // Ensure hidden field is clear

            if (!file) return;

            const fileType = file.type;

            // Show local preview
            if (fileType.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = '100px'; 
                img.style.borderRadius = '10px';
                preview.appendChild(img);
            } else if (fileType.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = URL.createObjectURL(file);
                video.controls = true;
                video.style.maxWidth = '100px';
                video.style.borderRadius = '10px';
                preview.appendChild(video);
            } else {
                preview.innerHTML = '<p style="color:red;">Unsupported file type.</p>';
            }
        });

        // ----------------------------------------------------
        // --- XHR-based Cloudinary Upload Function (Reliable) ---
        // ----------------------------------------------------
        const uploadToCloudinaryXHR = (file) => {
            return new Promise((resolve, reject) => {
                const cloudData = new FormData();
                cloudData.append("file", file);
                cloudData.append("upload_preset", "francis");

                const endpoint = file.type.startsWith("video")
                    ? "https://api.cloudinary.com/v1_1/djaqqrwoi/video/upload"
                    : "https://api.cloudinary.com/v1_1/djaqqrwoi/image/upload";

                const xhr = new XMLHttpRequest();
                
                // Track progress
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        const percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        progressBar.style.width = percentComplete + '%';
                        progressBar.innerText = percentComplete + '%';
                    }
                }, false);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) { // Request finished and response is ready
                        if (xhr.status === 200) {
                            // SUCCESS
                            try {
                                const res = JSON.parse(xhr.responseText);
                                resolve(res.secure_url);
                            } catch (e) {
                                reject("Failed to parse Cloudinary response.");
                            }
                        } else {
                            // ERROR: Log and reject with details
                            let errorMessage = `Cloudinary Upload Failed. Status: ${xhr.status}. `;
                            try {
                                const responseJson = JSON.parse(xhr.responseText);
                                if (responseJson.error && responseJson.error.message) {
                                    errorMessage += `Details: ${responseJson.error.message}`;
                                }
                            } catch (e) {
                                // Ignore JSON parse error if it's a network/CORS issue
                            }
                            reject(errorMessage);
                        }
                    }
                };

                xhr.onerror = function() {
                    // This handles Status 0/Network/CORS failure
                    reject("Network/CORS Error: Could not connect to Cloudinary. Check network and Cloudinary 'Allowed Origins'.");
                };
                
                xhr.open("POST", endpoint, true);
                xhr.send(cloudData);
            });
        };

        // ------------------------------------------
        // --- Main Form Submission Handler (jQuery) ---
        // ------------------------------------------
        $(form).on('submit', async function (e) {
            e.preventDefault(); // Stop the default form submission immediately

            const file = fileInput.files[0];
            let finalUrl = '';

            submitBtn.disabled = true;
            $(loader).show();

            try {
                // 1. Check if a new file was selected for upload
                if (file) {
                    // Upload the file via XHR
                    progressBar.parentNode.style.display = 'block'; // Show progress bar container
                    finalUrl = await uploadToCloudinaryXHR(file);
                    
                    // Set the resolved Cloudinary URL
                    cloudinaryInput.value = finalUrl;
                    
                    // Replaced: alert("File uploaded successfully! Proceeding to save tale.");
                    showCustomMessage("File uploaded successfully! Proceeding to update tale.", 'success');
                }

                // 2. Prepare Form Data for Laravel Submission
                const formData = new FormData(this);
                
                // 3. Submit to Laravel backend via jQuery AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST', 
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        // Replaced: alert("Tale successfully updated!");
                        showCustomMessage("Tale successfully updated! Redirecting...", 'success');
                        
                        // Wait a moment for the user to see the message before redirecting
                        setTimeout(() => {
                           window.location.href = `{{ route('view.tale', $tale->tales_id) }}`;
                        }, 1500);
                    },
                    error: function (jqXHR) {
                        let laravelError = "An unknown error occurred on the server.";
                        if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                            laravelError = jqXHR.responseJSON.message;
                        } else {
                            laravelError = `Server Error: ${jqXHR.status}.`;
                        }
                        // Replaced: alert("Tale update failed: " + laravelError);
                        showCustomMessage("Tale update failed: " + laravelError, 'error');
                    }
                });

            } catch (error) {
                // This catches the Cloudinary XHR reject
                // Replaced: alert("Upload Error: " + error);
                showCustomMessage("Upload Error: " + error, 'error');
            } finally {
                $(loader).hide();
                submitBtn.disabled = false;
                progressBar.style.width = '0%';
                progressBar.innerText = '0%';
                progressBar.parentNode.style.display = 'none'; // Hide progress bar container
            }
        });
    });
</script>

@include('partials.global-calls')
</body>
</html>
