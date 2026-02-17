<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Message - Supperage</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    
    <style>
        .message-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            margin-bottom: 80px;
        }
        
        .message-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .message-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
        }
        
        .message-body {
            padding: 30px;
        }
        
        .sender-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .sender-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .message-content {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
            line-height: 1.8;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        .message-footer {
            padding: 20px 30px;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .back-link:hover {
            color: #764ba2;
            transform: translateX(-5px);
        }
    </style>
</head>
<body>

@include('layouts.navbar')

@extends('layouts.app')

@section('content')
<div class="message-container">
    <a href="{{ route('inbox.index') }}" class="back-link" style="display: block; margin-bottom: 20px;">
        <i class="fa fa-arrow-left"></i> Back to Inbox
    </a>
    
    <div class="message-card">
        <div class="message-header">
            <h3 style="margin: 0;"><i class="fa fa-envelope-open"></i> Message from SupperAge Team</h3>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">
                <i class="fa fa-clock"></i> 
                Received {{ \Carbon\Carbon::parse($message->created_at)->format('F j, Y \a\t g:i A') }}
                <span style="opacity: 0.7;">({{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }})</span>
            </p>
        </div>
        
        <div class="message-body">
            <div class="sender-info">
                <div class="sender-avatar">
                    <i class="fa fa-shield-alt"></i>
                </div>
                <div>
                    <strong style="font-size: 18px;">SupperAge Administration</strong>
                    <div style="color: #666; font-size: 14px;">
                        @if($admin)
                            Admin: {{ $admin->name }}
                        @else
                            Official Message
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="message-content">
                {{ $message->message }}
            </div>
            
            @if($message->is_read)
                <div style="padding: 15px; background: #e8f5e9; border-radius: 10px; color: #2e7d32;">
                    <i class="fa fa-check-circle"></i> 
                    <strong>Read</strong> on {{ \Carbon\Carbon::parse($message->read_at)->format('F j, Y \a\t g:i A') }}
                </div>
            @endif
        </div>
        
        <div class="message-footer">
            <a href="{{ route('inbox.index') }}" class="btn btn-secondary">
                <i class="fa fa-inbox"></i> Back to Inbox
            </a>
            
            <button class="btn btn-danger" onclick="deleteThisMessage()">
                <i class="fa fa-trash"></i> Delete Message
            </button>
        </div>
    </div>
    
    <!-- Related Actions Card -->
    <div class="card mt-4">
        <div class="card-header" style="background: #f8f9fa;">
            <h5 style="margin: 0;"><i class="fa fa-question-circle"></i> Need Help?</h5>
        </div>
        <div class="card-body">
            <p>If you have any questions or concerns about this message, you can:</p>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                    <i class="fa fa-envelope"></i> Contact Support
                </a>
                <a href="{{ route('faq') }}" class="btn btn-outline-info">
                    <i class="fa fa-book"></i> View FAQ
                </a>
                <a href="{{ route('update') }}" class="btn btn-outline-success">
                    <i class="fa fa-home"></i> Go to Home
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>

<script>
function deleteThisMessage() {
    if (!confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
        return;
    }
    
    fetch('/inbox/{{ $message->id }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.href = '{{ route("inbox.index") }}';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete message. Please try again.');
    });
}
</script>
@endsection

</body>
</html>