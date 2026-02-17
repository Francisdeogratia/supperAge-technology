<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Event - Supperage</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/post.css') }}">

    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">

    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">


    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    
    
    <style>
        .edit-event-container {
            background: #f5f7fa;
            min-height: 100vh;
            padding: 30px 0;
        }

        .edit-event-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            margin-bottom: 30px;
            border-radius: 15px;
            text-align: center;
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
            color: #667eea;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 12px 15px;
        }

        .btn-custom {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            border: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .image-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 15px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <!-- Your msg  navbar content  -->

 @include('layouts.navbar')
    @extends('layouts.app')
    @section('content')

    <div class="edit-event-container">
        <div class="container">
            <div class="edit-event-header">
                <h1><i class="fas fa-edit"></i> Edit Event</h1>
                <p>Update your event details</p>
            </div>

            <div class="form-container">
                <form id="editEventForm">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-info-circle"></i> Basic Information
                        </h3>

                        <div class="form-group">
                            <label>Event Title *</label>
                            <input type="text" class="form-control" name="title" value="{{ $event->title }}" required>
                        </div>

                        <div class="form-group">
                            <label>Description *</label>
                            <textarea class="form-control" name="description" rows="5" required>{{ $event->description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category *</label>
                                    <select class="form-control" name="category" required>
                                        <option value="technology" {{ $event->category == 'technology' ? 'selected' : '' }}>Technology</option>
                                        <option value="business" {{ $event->category == 'business' ? 'selected' : '' }}>Business</option>
                                        <option value="education" {{ $event->category == 'education' ? 'selected' : '' }}>Education</option>
                                        <option value="entertainment" {{ $event->category == 'entertainment' ? 'selected' : '' }}>Entertainment</option>
                                        <option value="sports" {{ $event->category == 'sports' ? 'selected' : '' }}>Sports</option>
                                        <option value="health" {{ $event->category == 'health' ? 'selected' : '' }}>Health & Wellness</option>
                                        <option value="networking" {{ $event->category == 'networking' ? 'selected' : '' }}>Networking</option>
                                        <option value="social" {{ $event->category == 'social' ? 'selected' : '' }}>Social</option>
                                        <option value="other" {{ $event->category == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Privacy *</label>
                                    <select class="form-control" name="privacy" required>
                                        <option value="public" {{ $event->privacy == 'public' ? 'selected' : '' }}>Public</option>
                                        <option value="private" {{ $event->privacy == 'private' ? 'selected' : '' }}>Private</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="far fa-calendar-alt"></i> Date & Time
                        </h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Date *</label>
                                    <input type="date" class="form-control" name="event_date" 
                                           value="{{ $event->event_date->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Time *</label>
                                    <input type="time" class="form-control" name="event_time" 
                                           value="{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-map-marker-alt"></i> Location & Type
                        </h3>

                        <div class="form-group">
                            <label>Event Type *</label>
                            <div>
                                <label class="mr-3">
                                    <input type="radio" name="event_type" value="online" 
                                           {{ $event->event_type == 'online' ? 'checked' : '' }}> Online
                                </label>
                                <label class="mr-3">
                                    <input type="radio" name="event_type" value="physical" 
                                           {{ $event->event_type == 'physical' ? 'checked' : '' }}> Physical
                                </label>
                                <label>
                                    <input type="radio" name="event_type" value="hybrid" 
                                           {{ $event->event_type == 'hybrid' ? 'checked' : '' }}> Hybrid
                                </label>
                            </div>
                        </div>

                        <div class="form-group" id="locationGroup">
                            <label>Physical Location</label>
                            <input type="text" class="form-control" name="location" value="{{ $event->location }}">
                        </div>

                        <div class="form-group" id="meetingLinkGroup">
                            <label>Meeting Link</label>
                            <input type="url" class="form-control" name="meeting_link" value="{{ $event->meeting_link }}">
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-users"></i> Attendees
                        </h3>

                        <div class="form-group">
                            <label>Maximum Attendees (Optional)</label>
                            <input type="number" class="form-control" name="max_attendees" 
                                   value="{{ $event->max_attendees }}" min="1">
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-secondary btn-custom mr-3">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary-custom btn-custom" id="submitBtn">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('myjs/allpost.js') }}"></script>

    <script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->

    <script src="{{ asset('myjs/tales.js') }}"></script>

    <script src="{{ asset('myjs/mobilenavbar.js') }}"></script>

    <script src="{{ asset('myjs/searchuser.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Show/hide location/meeting link based on event type
            function toggleLocationFields() {
                const type = $('input[name="event_type"]:checked').val();
                
                if (type === 'physical') {
                    $('#locationGroup').show();
                    $('#meetingLinkGroup').hide();
                } else if (type === 'online') {
                    $('#locationGroup').hide();
                    $('#meetingLinkGroup').show();
                } else {
                    $('#locationGroup').show();
                    $('#meetingLinkGroup').show();
                }
            }

            // Initialize on load
            toggleLocationFields();

            // Update on change
            $('input[name="event_type"]').on('change', toggleLocationFields);

            // Form submission
            $('#editEventForm').on('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

                $.ajax({
                    url: '{{ route("events.update", $event->id) }}',
                    method: 'PUT',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Event updated successfully!');
                            window.location.href = '{{ route("events.show", $event->id) }}';
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Update Event');
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errorMsg = '';
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMsg += value[0] + '\n';
                            });
                            alert('Please fix the following errors:\n\n' + errorMsg);
                        } else {
                            alert('Failed to update event. Please try again.');
                        }
                    }
                });
            });
        });
    </script>

    @endsection
</body>
</html>