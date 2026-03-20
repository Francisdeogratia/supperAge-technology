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

    <style>
        /* Full-screen upload spinner overlay */
        #uploadOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 9999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 18px;
        }
        #uploadOverlay.active { display: flex; }
        #uploadOverlay .overlay-box {
            background: #fff;
            border-radius: 16px;
            padding: 36px 40px;
            text-align: center;
            max-width: 320px;
            width: 90%;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        }
        #uploadOverlay .step-label {
            font-size: 15px;
            color: #374151;
            font-weight: 600;
            margin-top: 14px;
        }
        #uploadOverlay .step-sub {
            font-size: 13px;
            color: #9ca3af;
            margin-top: 6px;
        }
        /* Step indicators */
        .steps-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-top: 18px;
        }
        .step-dot {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #9ca3af;
            font-size: 13px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.3s, color 0.3s;
        }
        .step-dot.active   { background: #0EA5E9; color: #fff; }
        .step-dot.done     { background: #10b981; color: #fff; }
        .step-line {
            width: 36px; height: 3px;
            background: #e5e7eb;
            transition: background 0.3s;
        }
        .step-line.done { background: #10b981; }
        /* Payment success banner */
        .payment-banner {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1.5px solid #6ee7b7;
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }
        .payment-banner .banner-icon { font-size: 36px; flex-shrink: 0; }
        .payment-banner .banner-title { font-size: 17px; font-weight: 800; color: #065f46; }
        .payment-banner .banner-sub   { font-size: 13px; color: #047857; margin-top: 3px; }
    </style>
</head>

@include('layouts.navbar')

{{-- ── Full-screen upload overlay ── --}}
<div id="uploadOverlay">
    <div class="overlay-box">
        <div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status">
            <span class="visually-hidden">Uploading...</span>
        </div>

        {{-- Step indicators --}}
        <div class="steps-row">
            <div class="step-dot active" id="sd1">1</div>
            <div class="step-line"       id="sl1"></div>
            <div class="step-dot"        id="sd2">2</div>
            <div class="step-line"       id="sl2"></div>
            <div class="step-dot"        id="sd3">3</div>
        </div>

        <p class="step-label" id="overlayLabel">Uploading your documents…</p>
        <p class="step-sub"   id="overlaySub">Please keep this page open.</p>
    </div>
</div>

{{-- ── Success modal (shown after redirect back with submitted=true) ── --}}
@if(session('submitted'))
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">✅ Successfully Submitted</h5>
      </div>
      <div class="modal-body text-center">
        <div class="spinner-border text-success mb-3" role="status"></div>
        <p>Your verification documents have been submitted successfully!</p>
        <p class="mt-2 text-muted">Redirecting you to your profile…</p>
      </div>
    </div>
  </div>
</div>
@endif

<div class="container my-4">
    <div class="col-lg-8 offset-lg-2">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Payment success banner --}}
                <div class="payment-banner">
                    <div class="banner-icon">✅</div>
                    <div>
                        <div class="banner-title">Payment Successful!</div>
                        <div class="banner-sub">
                            {{ ucfirst($status) }} &nbsp;·&nbsp;
                            Ref: <strong>{{ $txRef }}</strong> &nbsp;·&nbsp;
                            ID: <strong>{{ $transactionId }}</strong>
                        </div>
                    </div>
                </div>

                <p class="text-muted mb-4">
                    Thank you for activating Blue Badge Verification.
                    Please upload your documents below to complete the process.
                </p>

                <form id="badgeForm" method="POST" action="{{ route('submit.badge.requirements') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf

                    {{-- User Info --}}
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $profileImg }}"
                             alt="{{ $fullName }}'s profile"
                             style="width:50px;height:50px;border-radius:50%;object-fit:cover;margin-right:10px;">
                        <div>
                            <strong>{{ $fullName }}</strong><br>
                            <small class="text-muted">This is the account you are verifying</small>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Full Name --}}
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name (as on ID)</label>
                        <input type="text" name="full_name" id="full_name"
                               class="form-control @error('full_name') is-invalid @enderror"
                               value="{{ old('full_name', $fullName) }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Full name is required.</div>
                        @enderror
                    </div>

                    {{-- Government ID --}}
                    <div class="mb-3">
                        <label for="gov_id" class="form-label">
                            Government-issued ID
                            <small class="text-muted">(Passport, NIN, Driver's License, etc.)</small>
                            <small class="text-muted">(max 5 MB)</small>
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
                            <img id="gov_id_preview" src="#" alt="Government ID Preview" class="img-thumbnail d-none" style="max-width:200px;">
                        </div>
                    </div>

                    {{-- Profile Picture --}}
                    <div class="mb-3">
                        <label for="profile_pic" class="form-label">
                            Recent Clear Profile Picture
                            <small class="text-muted">(max 3 MB)</small>
                        </label>
                        <input type="file" name="profile_pic" id="profile_pic"
                               class="form-control @error('profile_pic') is-invalid @enderror"
                               accept="image/*" required>
                        @error('profile_pic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Please upload your profile picture.</div>
                        @enderror
                        <div class="mt-2">
                            <img id="profile_pic_preview" src="#" alt="Profile Picture Preview" class="img-thumbnail d-none" style="max-width:200px;">
                        </div>
                    </div>

                    {{-- Additional Notes --}}
                    <div class="mb-3">
                        <label for="notes" class="form-label">Accurate Personal Information & Additional Notes</label>
                        <textarea name="notes" id="notes"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit button --}}
                    <button type="submit" id="submitBtn" class="btn btn-warning w-100 fw-bold py-3">
                        <span id="submitBtnText">Submit Requirements</span>
                        <span id="submitBtnSpinner" class="d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Uploading…
                        </span>
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
    setTimeout(function () {
        window.location.href = "{{ url('/update') }}";
    }, 3000);
</script>
@endif

<script>
(function () {
    'use strict';

    // ── File size check ──────────────────────────────────────────────────────
    function checkFileSize(input, maxMB) {
        const file = input.files[0];
        const feedback = input.parentElement.querySelector('.invalid-feedback');
        if (file && file.size > maxMB * 1024 * 1024) {
            input.classList.add('is-invalid');
            if (feedback) feedback.textContent = 'File too large. Maximum allowed size is ' + maxMB + ' MB.';
            input.value = '';
            return false;
        }
        input.classList.remove('is-invalid');
        return true;
    }

    // ── File preview ─────────────────────────────────────────────────────────
    function previewFile(input, previewId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
            preview.src = '#';
        }
    }

    document.getElementById('gov_id').addEventListener('change', function () {
        if (checkFileSize(this, 5)) previewFile(this, 'gov_id_preview');
    });
    document.getElementById('profile_pic').addEventListener('change', function () {
        if (checkFileSize(this, 3)) previewFile(this, 'profile_pic_preview');
    });

    // ── Overlay step animation ────────────────────────────────────────────────
    var overlaySteps = [
        { label: 'Uploading your documents…',     sub: 'Please keep this page open.',       sd: [1], sl: [] },
        { label: 'Verifying your submission…',    sub: 'Almost there, hang tight.',          sd: [1,2], sl: [1] },
        { label: 'Saving your verification…',     sub: 'Just a moment more.',                sd: [1,2,3], sl: [1,2] },
    ];
    var overlayTimer = null;

    function showOverlayStep(idx) {
        var step = overlaySteps[Math.min(idx, overlaySteps.length - 1)];
        document.getElementById('overlayLabel').textContent = step.label;
        document.getElementById('overlaySub').textContent   = step.sub;
        for (var i = 1; i <= 3; i++) {
            var dot  = document.getElementById('sd' + i);
            dot.className = 'step-dot';
            if (step.sd.indexOf(i) !== -1) dot.classList.add(i === step.sd[step.sd.length - 1] ? 'active' : 'done');
        }
        for (var j = 1; j <= 2; j++) {
            var line = document.getElementById('sl' + j);
            line.className = 'step-line';
            if (step.sl.indexOf(j) !== -1) line.classList.add('done');
        }
    }

    function startOverlay() {
        var overlay = document.getElementById('uploadOverlay');
        overlay.classList.add('active');
        showOverlayStep(0);
        var step = 0;
        overlayTimer = setInterval(function () {
            step++;
            if (step < overlaySteps.length) showOverlayStep(step);
            else clearInterval(overlayTimer);
        }, 2200);
    }

    // ── Form submit ───────────────────────────────────────────────────────────
    var form = document.getElementById('badgeForm');
    form.addEventListener('submit', function (e) {
        form.classList.add('was-validated');
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            return;
        }
        // Valid — show spinner on button + full overlay
        document.getElementById('submitBtnText').classList.add('d-none');
        document.getElementById('submitBtnSpinner').classList.remove('d-none');
        document.getElementById('submitBtn').disabled = true;
        startOverlay();
    });

})();
</script>
@endsection
