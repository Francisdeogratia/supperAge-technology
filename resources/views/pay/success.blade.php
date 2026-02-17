@extends('layouts.app')

@section('content')

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
     <!-- Bootstrap 5.3.3 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
      rel="stylesheet" 
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
      crossorigin="anonymous">

      <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

    

</head>

 @include('layouts.navbar')

@if(session('submitted'))
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">✅ Successfully Submitted</h5>
      </div>
      <div class="modal-body text-center">
        <p>Your verification documents have been submitted successfully!</p>
        <p class="mt-3 text-muted">Redirecting you to update page...</p>
      </div>
    </div>
  </div>
</div>

@endif

<div class="container my-4">
    <div class="col-lg-8 offset-lg-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-success">✅ Payment Successful!</h2>
                <p>Thank you for activating Blue Badge Verification.  
                   Please complete the form below to submit your verification requirements.</p>

                <h4 class="mt-3">✅ Payment {{ ucfirst($status) }}</h4>
                <p><strong>Reference:</strong> {{ $txRef }}</p>
                <p><strong>Transaction ID:</strong> {{ $transactionId }}</p>

                <form method="POST" action="{{ route('submit.badge.requirements') }}" enctype="multipart/form-data" class="needs-validation mt-4" novalidate>
                    @csrf
<!-- User Info Display -->
<div class="d-flex align-items-center mb-4">
    <img src="{{ $profileImg }}" 
         alt="{{ $fullName }}'s profile" 
         style="width:50px;height:50px;border-radius:50%;object-fit:cover;margin-right:10px;">
    <div>
        <strong>{{ $fullName }}</strong><br>
        <small class="text-muted">This is the account you are verifying</small>
    </div>
</div>


<!-- full_name field so it’s still sent to the controller if needed -->
    <div class="mb-3">
    <label class="form-label">Full Name (from your account)</label>
    <input type="text" name="full_name" class="form-control" value="{{ $fullName }}" readonly>
</div>



                    
                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name (as on ID)</label>
                        <input type="text" name="full_name" id="full_name" 
                               class="form-control @error('full_name') is-invalid @enderror" 
                               value="{{ old('full_name') }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Full name is required.</div>
                        @enderror
                    </div>

                    <!-- Government ID -->
                    <div class="mb-3">
                        <label for="gov_id" class="form-label">
                            Government-issued ID <small class="text-muted">(Passport, NIN, Driver’s License, etc.)</small><small>(max 5 MB)</small>
                        </label>
                        <input type="file" name="gov_id" id="gov_id" 
                               class="form-control @error('gov_id') is-invalid @enderror" 
                               accept="image/*,application/pdf" required>
                        @error('gov_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Please upload your government ID.</div>
                        @enderror
                        <div class="mt-2">
                            <img id="gov_id_preview" src="#" alt="Government ID Preview" class="img-thumbnail d-none" style="max-width: 200px;">
                        </div>
                    </div>

                    <!-- Profile Picture -->
                    <div class="mb-3">
                        <label for="profile_pic" class="form-label">Recent Clear Profile Picture<small>(max 3 MB)</small></label>
                        <input type="file" name="profile_pic" id="profile_pic" 
                               class="form-control @error('profile_pic') is-invalid @enderror" 
                               accept="image/*" required>
                        @error('profile_pic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Please upload your profile picture.</div>
                        @enderror
                        <div class="mt-2">
                            <img id="profile_pic_preview" src="#" alt="Profile Picture Preview" class="img-thumbnail d-none" style="max-width: 200px;">
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Accurate Personal Information & Additional Notes</label>
                        <textarea name="notes" id="notes" 
                                  class="form-control @error('notes') is-invalid @enderror" 
                                  rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-warning w-100 fw-bold">
                        Submit Requirements
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

        <script src="{{ asset('myjs/bar.js') }}"></script>
    <script src="{{ asset('myjs/more_lesstext.js') }}"></script>
    <script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
    <script src="{{ asset('myjs/allpost.js') }}"></script>
    <script src="{{ asset('myjs/tales.js') }}"></script>
    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
    <script src="{{ asset('myjs/searchuser.js') }}"></script>

@if(session('submitted'))
<script>
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();

    // After 3 seconds, redirect to /update
    setTimeout(function () {
        window.location.href = "{{ url('/update') }}";
    }, 3000);
</script>
@endif


<!-- Bootstrap front-end validation + live preview -->
<script>
    (function () {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })

        // File preview function
        function previewFile(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('d-none');
                preview.src = '#';
            }
        }

        // Attach preview listeners
        document.getElementById('gov_id').addEventListener('change', function () {
            previewFile(this, 'gov_id_preview');
        });

        document.getElementById('profile_pic').addEventListener('change', function () {
            previewFile(this, 'profile_pic_preview');
        });
    })()




   
(function () {
    'use strict';

    // File size check helper
    function checkFileSize(input, maxMB) {
        const file = input.files[0];
        const feedback = input.parentElement.querySelector('.invalid-feedback');
        if (file && file.size > maxMB * 1024 * 1024) {
            input.classList.add('is-invalid');
            if (feedback) {
                feedback.textContent = `File too large. Maximum allowed size is ${maxMB} MB.`;
            }
            input.value = "";
            return false;
        } else {
            input.classList.remove('is-invalid');
            return true;
        }
    }

    // Live preview for images
    function previewFile(input, previewId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
            preview.src = '#';
        }
    }

    // Attach listeners
    document.getElementById('gov_id').addEventListener('change', function () {
        if (checkFileSize(this, 5)) {
            previewFile(this, 'gov_id_preview');
        }
    });

    document.getElementById('profile_pic').addEventListener('change', function () {
        if (checkFileSize(this, 3)) {
            previewFile(this, 'profile_pic_preview');
        }
    });

})();




</script>
@endsection
