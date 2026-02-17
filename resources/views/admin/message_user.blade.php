@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Send Message to {{ $user->name }}</h2>
        <a href="{{ route('admin.users.now') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-envelope"></i> Compose Message</h5>
                </div>
                <div class="card-body">
                    <form id="messageForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="recipient" class="form-label">Recipient</label>
                            <input type="text" class="form-control" id="recipient" 
                                   value="{{ $user->name }} ({{ '@' . $user->username }})" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="8" 
                                      placeholder="Type your message here..." required></textarea>
                            <small class="text-muted">This message will be sent directly to the user.</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Note:</strong> The user will receive this message as an admin notification.
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.now') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="sendBtn">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Message History (if you want to show previous messages) -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Previous Messages</h5>
                </div>
                <div class="card-body">
                    <div id="messageHistory">
                        @php
                            $previousMessages = DB::table('admin_messages')
                                ->where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();
                        @endphp

                        @if($previousMessages->count() > 0)
                            @foreach($previousMessages as $msg)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <strong>Admin Message</strong>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($msg->created_at)->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 mt-2">{{ $msg->message }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-4">No previous messages</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- User Info Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> User Information</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $user->profileimg ?? asset('images/default-avatar.png') }}" 
                         class="rounded-circle mb-3" width="100" height="100" alt="Profile">
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted mb-2">{{ '@' . $user->username }}</p>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                    
                    <div class="mt-3">
                        <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> User Stats</h5>
                </div>
                <div class="card-body">
                    @php
                        $postsCount = DB::table('sample_posts')->where('user_id', $user->id)->count();
                        $talesCount = DB::table('tales_extens')->where('specialcode', $user->specialcode)->count();
                    @endphp
                    
                    <div class="mb-2">
                        <small class="text-muted">Total Posts:</small>
                        <div><strong>{{ $postsCount }}</strong></div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Total Tales:</small>
                        <div><strong>{{ $talesCount }}</strong></div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Joined:</small>
                        <div><strong>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</strong></div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.users.edit.now', $user->id) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                    <a href="{{ route('admin.users.posts.now', $user->id) }}" class="btn btn-outline-info btn-sm w-100 mb-2">
                        <i class="fas fa-images"></i> View Posts
                    </a>
                    <a href="{{ route('admin.users.tales.now', $user->id) }}" class="btn btn-outline-success btn-sm w-100">
                        <i class="fas fa-book"></i> View Tales
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="messageToast" class="toast hide" role="alert">
        <div class="toast-header" id="toastHeader">
            <strong class="me-auto" id="toastTitle">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastBody">
            Message content here
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const message = document.getElementById('message').value;
    const sendBtn = document.getElementById('sendBtn');
    
    if (!message.trim()) {
        showToast('Error', 'Please enter a message', 'danger');
        return;
    }
    
    // Disable button and show loading
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    
    fetch('{{ route("admin.users.send-message.now", $user->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message: message })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Success', data.message, 'success');
            document.getElementById('message').value = '';
            
            // Reload message history after 1 second
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast('Error', data.message || 'Failed to send message', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while sending the message', 'danger');
    })
    .finally(() => {
        // Re-enable button
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
    });
});

function showToast(title, message, type) {
    const toast = document.getElementById('messageToast');
    const toastHeader = document.getElementById('toastHeader');
    const toastTitle = document.getElementById('toastTitle');
    const toastBody = document.getElementById('toastBody');
    
    // Set colors
    toastHeader.className = 'toast-header bg-' + type + ' text-white';
    
    // Set content
    toastTitle.textContent = title;
    toastBody.textContent = message;
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}
</script>
@endsection