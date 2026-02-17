
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

    <title>Edit your profile</title>

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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script> -->
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<!-- Cropper CSS (loaded above) -->

<!-- Compressor.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js"></script>

<!-- Dropzone.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->



    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
    
    

    <!-- Scripts -->
    <style>
.modal-content {
  border-radius: 8px;
  box-shadow: 0 0 20px rgba(0,0,0,0.3);
  background-color: #fff;
}

.modal-header {
  background-color: #007bff;
  color: white;
  border-bottom: 1px solid #dee2e6;
}

.modal-title {
  font-weight: bold;
  font-size: 1.25rem;
}

.modal-body {
  padding: 20px;
}

.modal-footer {
  padding: 15px;
  display: flex;
  justify-content: center;
}


#cropBtn {
  padding: 10px 20px;
  font-size: 16px;
  font-weight: 600;
  background-color: #28a745;
  border: none;
  border-radius: 5px;
  color: white;
  transition: background-color 0.3s ease;
}

#cropBtn:hover {
  background-color: #218838;
}



    </style>
    
    
</head>
<body>
<!-- Your msg  navbar content  -->

 @include('layouts.navbar')


@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">

           <!-- Toast container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
    <div id="uploadToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">Upload successful!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- Place this right after the toast container -->
@if(session('success'))
<script>
document.addEventListener("DOMContentLoaded", function () {
    showToast("{{ session('success') }}");
});
</script>
@endif

        <div class="card-header" style="display:flex; justify-content:space-between;align-items:center;">
            <h3 class="card-title">Edit Your Profile</h3>
            <strong>{{ $user->number_followers }}: Followers</strong>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

           
                <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="{{ $user->username }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" value="{{ $user->phone }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Continent</label>
                    <input type="text" name="continent" value="{{ $user->continent }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" value="{{ $user->country }}" class="form-control" required>
                </div>

                 <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state" value="{{ $user->state }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" value="{{ $user->city }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="{{ $user->dob }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Gender</label><br>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" value="male" class="form-check-input" {{ $user->gender == 'male' ? 'checked' : '' }}>
                        <label class="form-check-label">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" value="female" class="form-check-input" {{ $user->gender == 'female' ? 'checked' : '' }}>
                        <label class="form-check-label">Female</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" value="other" class="form-check-input" {{ $user->gender == 'other' ? 'checked' : '' }}>
                        <label class="form-check-label">Other</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Bio</label>
                    <textarea name="bio" class="form-control" required>{{ $user->bio }}</textarea>
                </div>

                <div id="loadingSpinner" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center; flex-direction:column;">
    <div style="width:60px; height:60px; border:5px solid rgba(255,255,255,0.3); border-top:5px solid #fff; border-radius:50%; animation:bigSpin 0.8s linear infinite;"></div>
    <p id="spinnerText" style="color:#fff; margin-top:16px; font-size:16px; font-weight:600;">Updating profile...</p>
</div>
<style>
@keyframes bigSpin { 0%{transform:rotate(0deg)} 100%{transform:rotate(360deg)} }
</style>


               <div class="form-group">
    <label>Profile Image</label>
    <div id="profileDropzone" class="dropzone d-flex align-items-center justify-content-center" style="min-height: 150px; border: 2px dashed #007bff; background: #f8f9fa; cursor: pointer;">
        <div class="dz-message text-center">
            <i class="fa fa-upload fa-2x text-primary"></i>
            <p>Click or drag to upload profile image</p>
        </div>
    </div>
    <img id="profilePreview" class="img-thumbnail mt-2" style="display:none; border-radius:50%; width:150px;">
</div>

<div class="form-group">
    <label>Background Image</label>
    <div id="bgDropzone" class="dropzone d-flex align-items-center justify-content-center" style="min-height: 150px; border: 2px dashed #007bff; background: #f8f9fa; cursor: pointer;">
        <div class="dz-message text-center">
            <i class="fa fa-upload fa-2x text-primary"></i>
            <p>Click or drag to upload background image</p>
        </div>
    </div>
    <img id="bgPreview" class="img-thumbnail mt-2" style="display:none; width:100%; max-height:200px; object-fit:cover;">
</div>


<!-- progress bar  -->

<div class="progress mt-3" style="height: 20px; display:none;" id="uploadProgress">
    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: 0%;" id="progressBar">0%</div>
</div>



                <button type="submit" class="btn btn-block text-white" style="background: skyblue; font-weight: 700; font-size:20px;">
                    Update Changes
                </button>
            </form>
        </div>
    </div>
</div>

@endsection


<!-- my js files -->
 <!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Then other jQuery-based scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->
<!-- <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.1/dist/emoji-button.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/js/all.min.js"></script> -->
   <!-- Bootstrap JS Bundle -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->

<!-- Cropper JS -->
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>



<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
    
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>

@section('content')
<div id="profileSuccessPopup" style="
    position: fixed;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #17a2b8;
    color: white;
    padding: 20px 30px;
    border-radius: 10px;
    font-size: 18px;
    font-weight: bold;
    display: none;
    z-index: 9999;
    text-align: center;
">
    🎉 Profile updated successfully!
</div>
@endsection

@if(Session::has('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('profileSuccessPopup');
    popup.innerText = @json(Session::get('success'));
    popup.style.display = 'block';

    const audio = new Audio("{{ asset('sounds/mixkit-fantasy-game-success-notification-270.wav') }}");
    audio.play();

    setTimeout(() => {
        popup.style.display = 'none';
    }, 4000);
});
</script>
@endif



<script>
Dropzone.autoDiscover = false;

let croppedFiles = {}; // Store cropped blobs globally

function showSpinner(text) {
    const s = document.getElementById('loadingSpinner');
    const t = document.getElementById('spinnerText');
    if(text) t.textContent = text;
    s.style.display = 'flex';
}
function hideSpinner() {
    const s = document.getElementById('loadingSpinner');
    s.style.display = 'none';
    document.getElementById('spinnerText').textContent = 'Updating profile...';
}
function showToast(message, isError = false) {
    const toastEl = document.getElementById('uploadToast');
    const toastMsg = document.getElementById('toastMessage');
    toastMsg.textContent = message;
    toastEl.classList.remove('bg-success', 'bg-danger');
    toastEl.classList.add(isError ? 'bg-danger' : 'bg-success');
    new bootstrap.Toast(toastEl).show();
}
function updateProgress(percent) {
    const progressBar = document.getElementById('progressBar');
    const progressContainer = document.getElementById('uploadProgress');
    progressContainer.style.display = 'block';
    progressBar.style.width = percent + '%';
    progressBar.textContent = percent + '%';
}

function setupDropzone(id, previewId, inputName) {
    const dropzone = new Dropzone(id, {
        url: "#",
        autoProcessQueue: false,
        clickable: true,
        maxFiles: 1,
        acceptedFiles: "image/*",
        init: function () {
            this.on("addedfile", function (file) {
                showSpinner('Preparing image...');
                updateProgress(10);

                new Compressor(file, {
                    quality: 0.6,
                    success(result) {
                        updateProgress(30);
                        const reader = new FileReader();
                        const existingModal = document.querySelector('.modal');
                        if (existingModal) existingModal.remove();

                        reader.onload = function (e) {
                            updateProgress(50);

                            const modalWrapper = document.createElement('div');
                            modalWrapper.innerHTML = `
                              <div class="modal fade" tabindex="-1" role="dialog" id="cropModal">
                                <div class="modal-dialog modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title">Crop Image</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                      <img id="cropImage" src="${e.target.result}" style="max-width: 100%;" />
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-primary" id="cropBtn">Crop & Use</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            `;
                            document.body.appendChild(modalWrapper);

                            const cropModalEl = document.getElementById('cropModal');
                            const bsModal = new bootstrap.Modal(cropModalEl);
                            bsModal.show();
                            cropModalEl.removeAttribute('aria-hidden');

                            // Hide spinner if modal is dismissed without cropping
                            cropModalEl.addEventListener('hidden.bs.modal', () => {
                                hideSpinner();
                                updateProgress(0);
                                document.getElementById('uploadProgress').style.display = 'none';
                            }, { once: true });


                            cropModalEl.addEventListener('shown.bs.modal', () => {
                                // Hide the spinner overlay so user can interact with cropper
                                hideSpinner();
                                document.getElementById('uploadProgress').style.display = 'none';

                                const image = document.getElementById('cropImage');
                                const cropper = new Cropper(image, {
                                    aspectRatio: inputName === 'profileimg' ? 1 : 16 / 9,
                                    viewMode: 1
                                });

                                document.getElementById('cropBtn').onclick = function () {
                                    const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
                                    document.getElementById(previewId).src = canvas.toDataURL();
                                    document.getElementById(previewId).style.display = 'block';

                                    canvas.toBlob(blob => {
                                        croppedFiles[inputName] = new File([blob], `${inputName}.jpg`, { type: 'image/jpeg' });
                                        cropper.destroy();
                                        bsModal.hide();
                                        hideSpinner();
                                        updateProgress(0);
                                        document.getElementById('uploadProgress').style.display = 'none';
                                        cropModalEl.addEventListener('hidden.bs.modal', () => {
                                            modalWrapper.remove();
                                        });
                                    }, 'image/jpeg', 0.6);
                                };
                            });
                        };
                        reader.readAsDataURL(result);
                    },
                    error(err) {
                        hideSpinner();
                        showToast("Compression failed", true);
                    }
                });
            });
        }
    });
}


document.addEventListener("DOMContentLoaded", function () {
    setupDropzone("#profileDropzone", "profilePreview", "profileimg");
    setupDropzone("#bgDropzone", "bgPreview", "bgimg");

    $('#profileForm').on('submit', async function (e) {
        e.preventDefault();

        showSpinner('Updating profile...');
        updateProgress(10);

        let profileUrl = '';
        let bgUrl = '';
        const formElement = this; // Reference to the form element

        // Function to perform Cloudinary upload using reliable XHR
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
                        // Update the visible progress bar based on the specific file being uploaded
                        updateProgress(10 + Math.floor(percentComplete * 0.3)); // 10% to 40% for 1st file, 40% to 70% for 2nd
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
                            // ERROR
                            // Log Cloudinary's error message (usually in the responseText)
                            console.error("Cloudinary XHR Failed. Status:", xhr.status, "Response:", xhr.responseText);
                            reject(`Cloudinary upload failed. Status: ${xhr.status}`);
                        }
                    }
                };

                xhr.onerror = function() {
                    reject("Network or CORS error during Cloudinary upload.");
                };
                
                xhr.open("POST", endpoint, true);
                xhr.send(cloudData);
            });
        };

        // --- Sequential Uploads ---

        try {
            // 1. Upload Profile Image
            if (croppedFiles['profileimg']) {
                updateProgress(10);
                profileUrl = await uploadToCloudinaryXHR(croppedFiles['profileimg']);
                updateProgress(40); // Base progress after profile image upload
            } else {
                updateProgress(40);
            }

            // 2. Upload Background Image
            if (croppedFiles['bgimg']) {
                updateProgress(40);
                bgUrl = await uploadToCloudinaryXHR(croppedFiles['bgimg']);
                updateProgress(70); // Base progress after background image upload
            } else {
                updateProgress(70);
            }

            // --- Form Submission to Laravel ---

            const formData = new FormData(formElement);
            // Append the successful URLs (or empty strings if not uploaded)
            formData.append('profileimg_url', profileUrl); 
            formData.append('bgimg_url', bgUrl);

            $.ajax({
                url: $(formElement).attr('action'),
                method: $(formElement).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    hideSpinner();
                    updateProgress(100);
                    showToast("Profile updated successfully!");
                    window.location.reload(true);
                },
                error: function (xhr) {
                    hideSpinner();
                    // Display specific Laravel validation errors or generic message
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const firstError = Object.values(xhr.responseJSON.errors)[0][0];
                        showToast(`Update failed: ${firstError}`, true);
                    } else {
                        showToast("Update failed. Please try again.", true);
                    }
                }
            });

        } catch (err) {
            // This catches the XHR reject call if the Cloudinary upload failed
            hideSpinner();
            updateProgress(0);
            showToast(err, true);
        }

        setTimeout(() => {
            updateProgress(0);
            document.getElementById('uploadProgress').style.display = 'none';
        }, 4000);
    });
});





// document.addEventListener("DOMContentLoaded", function () {
//     setupDropzone("#profileDropzone", "profilePreview", "profileimg");
//     setupDropzone("#bgDropzone", "bgPreview", "bgimg");

//     $('#profileForm').on('submit', async function (e) {
//         e.preventDefault();

//         showSpinner();
//         updateProgress(10);

//         const cloudinaryUpload = async (file) => {
//             const cloudData = new FormData();
//             cloudData.append("file", file);
//             cloudData.append("upload_preset", "francis");

//             const endpoint = file.type.startsWith("video")
//                 ? "https://api.cloudinary.com/v1_1/djaqqrwoi/video/upload"
//                 : "https://api.cloudinary.com/v1_1/djaqqrwoi/image/upload";

//             return $.ajax({
//                 url: endpoint,
//                 type: "POST",
//                 data: cloudData,
//                 contentType: false,
//                 processData: false
//             });
//         };

//         let profileUrl = '';
//         let bgUrl = '';

//         try {
//             if (croppedFiles['profileimg']) {
//                 const res = await cloudinaryUpload(croppedFiles['profileimg']);
//                 profileUrl = res.secure_url;
//             }

//             if (croppedFiles['bgimg']) {
//                 const res = await cloudinaryUpload(croppedFiles['bgimg']);
//                 bgUrl = res.secure_url;
//             }

//             updateProgress(70);

//             const formData = new FormData(this);
//             formData.append('profileimg_url', profileUrl);
//             formData.append('bgimg_url', bgUrl);

//             $.ajax({
//                 url: $(this).attr('action'),
//                 method: $(this).attr('method'),
//                 data: formData,
//                 processData: false,
//                 contentType: false,
//                 success: function () {
//                     hideSpinner();
//                     updateProgress(100);
//                     showToast("Profile updated successfully!");
//                     window.location.reload(true);

//                 },
//                 error: function () {
//                     hideSpinner();
//                     showToast("Update failed. Please try again.", true);
//                     // window.location.reload(true);

//                 }
//             });

//         } catch (err) {
//             hideSpinner();
//             showToast("Cloudinary upload failed", true);
//             // window.location.reload(true);

//         }

//         setTimeout(() => {
//             updateProgress(0);
//             document.getElementById('uploadProgress').style.display = 'none';
//         }, 4000);
//     });
// });


   
</script>

</body>

</html>