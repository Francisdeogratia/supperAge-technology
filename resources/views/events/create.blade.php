<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Event - Supperage</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">

    <!-- google ads -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2892124627300694"
     crossorigin="anonymous"></script>
    
    <style>
        .create-event-container {
            background: #f5f7fa;
            min-height: 100vh;
            padding: 30px 0;
        }

        .create-event-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            margin-bottom: 30px;
            border-radius: 15px;
            text-align: center;
        }

        .create-event-header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #667eea;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .image-upload-area {
            border: 2px dashed #667eea;
            border-radius: 15px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f8f9ff;
        }

        .image-upload-area:hover {
            background: #eff1ff;
            border-color: #5568d3;
        }

        .image-upload-area i {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .image-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 15px;
            margin-top: 15px;
            display: none;
        }

        .btn-custom {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary-custom {
            background: #e9ecef;
            color: #333;
        }

        .btn-secondary-custom:hover {
            background: #dee2e6;
        }

        .radio-group, .checkbox-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .custom-radio, .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .custom-radio:hover, .custom-checkbox:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .custom-radio input:checked + .custom-radio,
        .custom-checkbox input:checked + .custom-checkbox {
            border-color: #667eea;
            background: #eff1ff;
        }

        .help-text {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }

        .required {
            color: #dc3545;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            .create-event-header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <!-- Your msg  navbar content  -->

 @include('layouts.navbar')
    @extends('layouts.app')
    @section('content')

    <div class="create-event-container">
        <div class="container">
            <div class="create-event-header">
                <h1><i class="fas fa-calendar-plus"></i> Create New Event</h1>
                <p>Share your event with the Supperage community</p>
            </div>

            <div class="form-container">
                <form id="createEventForm">
                    @csrf

                    <!-- Basic Information -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-info-circle"></i> Basic Information
                        </h3>

                        <div class="form-group">
                            <label>Event Title <span class="required">*</span></label>
                            <input type="text" class="form-control" name="title" id="title" 
                                   placeholder="e.g., Tech Meetup Lagos 2025" required>
                        </div>

                        <div class="form-group">
                            <label>Description <span class="required">*</span></label>
                            <textarea class="form-control" name="description" id="description" 
                                      placeholder="Tell people what your event is about..." required></textarea>
                            <small class="help-text">Be clear and descriptive to attract attendees</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category <span class="required">*</span></label>
                                    <select class="form-control" name="category" id="category" required>
                                        <option value="">Select Category</option>
                                        <option value="technology">Technology</option>
                                        <option value="business">Business</option>
                                        <option value="education">Education</option>
                                        <option value="entertainment">Entertainment</option>
                                        <option value="sports">Sports</option>
                                        <option value="health">Health & Wellness</option>
                                        <option value="networking">Networking</option>
                                        <option value="social">Social</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Privacy <span class="required">*</span></label>
                                    <select class="form-control" name="privacy" id="privacy" required>
                                        <option value="public">Public - Anyone can see and join</option>
                                        <option value="private">Private - Only invited people</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="far fa-calendar-alt"></i> Date & Time
                        </h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Date <span class="required">*</span></label>
                                    <input type="date" class="form-control" name="event_date" id="event_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Time <span class="required">*</span></label>
                                    <input type="time" class="form-control" name="event_time" id="event_time" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Event Type & Location -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-map-marker-alt"></i> Location & Type
                        </h3>

                        <div class="form-group">
                            <label>Event Type <span class="required">*</span></label>
                            <div class="radio-group">
                                <label class="custom-radio">
                                    <input type="radio" name="event_type" value="online" checked>
                                    <span><i class="fas fa-globe"></i> Online Event</span>
                                </label>
                                <label class="custom-radio">
                                    <input type="radio" name="event_type" value="physical">
                                    <span><i class="fas fa-map-marker-alt"></i> Physical Event</span>
                                </label>
                                <label class="custom-radio">
                                    <input type="radio" name="event_type" value="hybrid">
                                    <span><i class="fas fa-layer-group"></i> Hybrid Event</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group" id="locationGroup" style="display: none;">
                            <label>Physical Location</label>
                            <input type="text" class="form-control" name="location" id="location" 
                                   placeholder="Enter venue address">
                            <small class="help-text">e.g., Lagos Business School, Victoria Island</small>
                        </div>

                        <div class="form-group" id="meetingLinkGroup">
                            <label>Meeting Link (Online)</label>
                            <input type="url" class="form-control" name="meeting_link" id="meeting_link" 
                                   placeholder="https://zoom.us/j/123456789">
                            <small class="help-text">Zoom, Google Meet, or any video conferencing link</small>
                        </div>
                    </div>

                    <!-- Attendees -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-users"></i> Attendees
                        </h3>

                        <div class="form-group">
                            <label>Maximum Attendees (Optional)</label>
                            <input type="number" class="form-control" name="max_attendees" id="max_attendees" 
                                   placeholder="Leave empty for unlimited" min="1">
                            <small class="help-text">Set a limit if venue has capacity restrictions</small>
                        </div>
                    </div>

                    <!-- Event Image -->
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="far fa-image"></i> Event Image (Optional)
                        </h3>

                        <input type="file" id="imageInput" accept="image/*" style="display: none;">
                        <input type="hidden" name="event_image" id="event_image">
                        
                        <div class="image-upload-area" onclick="document.getElementById('imageInput').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <h5>Click to upload event image</h5>
                            <p class="text-muted">JPG, PNG or GIF (Max 5MB)</p>
                        </div>
                        
                        <img id="imagePreview" class="image-preview" alt="Event Preview">
                    </div>

                    <!-- Submit Buttons -->
                    <div class="text-center mt-4">
                        <a href="{{ route('events.index') }}" class="btn btn-secondary-custom btn-custom mr-3">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary-custom btn-custom" id="submitBtn">
                            <i class="fas fa-check"></i> Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->

<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';

        $(document).ready(function() {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            $('#event_date').attr('min', today);

            // Event type change handler
            $('input[name="event_type"]').on('change', function() {
                const type = $(this).val();
                
                if (type === 'physical') {
                    $('#locationGroup').show();
                    $('#meetingLinkGroup').hide();
                } else if (type === 'online') {
                    $('#locationGroup').hide();
                    $('#meetingLinkGroup').show();
                } else { // hybrid
                    $('#locationGroup').show();
                    $('#meetingLinkGroup').show();
                }
            });

            // Image upload handler
            $('#imageInput').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);

                    // Upload to Cloudinary
                    uploadImage(file);
                }
            });

            // Form submission
            $('#createEventForm').on('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');

                const formData = new FormData(this);

                $.ajax({
                    url: '{{ route("events.store") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            alert('Event created successfully!');
                            window.location.href = '{{ route("events.index") }}';
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html('<i class="fas fa-check"></i> Create Event');
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errorMsg = '';
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMsg += value[0] + '\n';
                            });
                            alert('Please fix the following errors:\n\n' + errorMsg);
                        } else {
                            alert('Failed to create event. Please try again.');
                        }
                    }
                });
            });
        });

        function uploadImage(file) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('upload_preset', 'francis');

    $('.image-upload-area').html('<i class="fas fa-spinner fa-spin fa-2x"></i><p>Uploading...</p>');

    // ✅ FIX: Remove jQuery ajax and use fetch to avoid Laravel Echo headers
    fetch('https://api.cloudinary.com/v1_1/djaqqrwoi/image/upload', {
        method: 'POST',
        body: formData
        // Don't include any headers
    })
    .then(response => response.json())
    .then(data => {
        if (data.secure_url) {
            $('#event_image').val(data.secure_url);
            $('.image-upload-area').html('<i class="fas fa-check-circle fa-2x text-success"></i><p>Image uploaded!</p>');
        } else {
            throw new Error('Upload failed');
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        alert('Failed to upload image. Please try again.');
        $('.image-upload-area').html('<i class="fas fa-cloud-upload-alt"></i><h5>Click to upload event image</h5><p class="text-muted">JPG, PNG or GIF (Max 5MB)</p>');
    });
}
    </script>

    @endsection
</body>
</html>