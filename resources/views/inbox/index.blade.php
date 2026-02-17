<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inbox - Supperage</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    
    <style>
        .inbox-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            margin-bottom: 80px;
        }
        
        .inbox-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
            margin-bottom: 0;
        }
        
        .message-list {
            background: white;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .message-item {
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .message-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }
        
        .message-item.unread {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
        }
        
        .message-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }
        
        .message-content {
            flex: 1;
        }
        
        .message-preview {
            color: #666;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .message-time {
            font-size: 12px;
            color: #999;
        }
        
        .unread-badge {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .no-messages {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }
        
        .no-messages i {
            font-size: 80px;
            color: #e0e0e0;
            margin-bottom: 20px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
    </style>
</head>
<body>

@include('layouts.navbar')

@extends('layouts.app')

@section('content')
<div class="inbox-container">
    <div class="inbox-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 style="margin: 0;"><i class="fa fa-inbox"></i> Your Inbox</h2>
                <p style="margin: 10px 0 0 0; opacity: 0.9;">Messages from SupperAge Team</p>
            </div>
            @if($unreadCount > 0)
                <span class="unread-badge">{{ $unreadCount }} Unread</span>
            @endif
        </div>
        
        <div class="action-buttons">
            @if($unreadCount > 0)
                <button class="btn btn-light btn-sm" onclick="markAllAsRead()">
                    <i class="fa fa-check-double"></i> Mark All as Read
                </button>
            @endif
            <a href="{{ route('update') }}" class="btn btn-light btn-sm">
                <i class="fa fa-home"></i> Back to Home
            </a>
        </div>
    </div>
    
    <div class="message-list">
        @if($messages->count() > 0)
            @foreach($messages as $message)
                <div class="message-item {{ $message->is_read ? '' : 'unread' }}" 
                     onclick="window.location='{{ route('inbox.show', $message->id) }}'">
                    <div class="message-icon">
                        <i class="fa fa-envelope{{ $message->is_read ? '-open' : '' }}"></i>
                    </div>
                    
                    <div class="message-content">
                        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 5px;">
                            <strong style="color: #667eea;">
                                <i class="fa fa-shield-alt"></i> SupperAge Team
                            </strong>
                            @if(!$message->is_read)
                                <span style="background: #667eea; color: white; padding: 2px 8px; border-radius: 10px; font-size: 10px; margin-left: 10px;">NEW</span>
                            @endif
                        </div>
                        
                        <div class="message-preview">{{ $message->message }}</div>
                        
                        <div class="message-time">
                            <i class="fa fa-clock"></i> 
                            {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                        </div>
                    </div>
                    
                    <div>
                        <button class="btn btn-sm btn-danger" onclick="event.stopPropagation(); deleteMessage({{ $message->id }})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
            
            <!-- Pagination -->
            <div style="padding: 20px;">
                {{ $messages->links() }}
            </div>
        @else
            <div class="no-messages">
                <i class="fa fa-inbox"></i>
                <h4>No Messages Yet</h4>
                <p>You don't have any messages from the admin team.</p>
                <a href="{{ route('update') }}" class="btn btn-primary mt-3">
                    <i class="fa fa-home"></i> Go to Home
                </a>
            </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('myjs/bar.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>

<script>
function markAllAsRead() {
    if (!confirm('Mark all messages as read?')) return;
    
    fetch('{{ route("inbox.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    });
}

function deleteMessage(id) {
    if (!confirm('Are you sure you want to delete this message?')) return;
    
    fetch(`/inbox/${id}`, {
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
            location.reload();
        }
    });
}

// Update unread count in navbar (if you have a badge there)
function updateUnreadCount() {
    fetch('{{ route("inbox.unread-count") }}')
        .then(res => res.json())
        .then(data => {
            const badge = document.getElementById('inbox-badge');
            if (badge && data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'inline';
            } else if (badge) {
                badge.style.display = 'none';
            }
        });
}

// Check for new messages every 30 seconds
setInterval(updateUnreadCount, 30000);
</script>
@endsection

</body>
</html>