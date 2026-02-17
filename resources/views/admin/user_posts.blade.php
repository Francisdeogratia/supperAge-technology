@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Posts by {{ $user->name }} ({{ '@' . $user->username }})</h2>
        <a href="{{ route('admin.users.now') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <img src="{{ $user->profileimg ?? asset('images/default-avatar.png') }}" 
                         class="img-fluid rounded-circle" alt="Profile">
                </div>
                <div class="col-md-10">
                    <h4>{{ $user->name }}</h4>
                    <p class="mb-1"><strong>Username:</strong> {{ '@' . $user->username }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="mb-0"><strong>Total Posts:</strong> {{ $posts->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($posts as $post)
        @php
            // Handle both 'id' and 'post_id' column names
            $postId = $post->post_id ?? $post->id;
        @endphp
        <div class="col-md-6 mb-4" id="post-{{ $postId }}">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="badge {{ ($post->status ?? 'published') == 'published' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($post->status ?? 'published') }}
                    </span>
                    <small class="text-muted">
                        {{ $post->created_at ? \Carbon\Carbon::parse($post->created_at)->format('M d, Y H:i') : 'N/A' }}
                    </small>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ Str::limit($post->post_content ?? '', 200) }}</p>
                    
                    @if($post->file_path ?? false)
                        @php
                            $files = json_decode($post->file_path, true);
                            $firstFile = is_array($files) ? ($files[0] ?? null) : $post->file_path;
                        @endphp
                        @if($firstFile)
                            <img src="{{ $firstFile }}" class="img-fluid rounded mb-2" alt="Post image" style="max-height: 300px; object-fit: cover;">
                        @endif
                    @endif

                    <div class="d-flex justify-content-between text-muted small">
                        <span><i class="fas fa-eye"></i> {{ $post->views ?? 0 }}</span>
                        <span><i class="fas fa-heart"></i> {{ $post->likes ?? 0 }}</span>
                        <span><i class="fas fa-share"></i> {{ $post->shares ?? 0 }}</span>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="btn-group w-100" role="group">
                        @if(($post->status ?? 'published') == 'published')
                            <button class="btn btn-sm btn-warning" onclick="suspendPost({{ $postId }})">
                                <i class="fas fa-ban"></i> Suspend
                            </button>
                        @endif
                        <button class="btn btn-sm btn-danger" onclick="deletePost({{ $postId }})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        <button class="btn btn-sm btn-info" onclick="viewPost({{ $postId }})">
                            <i class="fas fa-eye"></i> View Full
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> This user has no posts yet.
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>

<!-- View Post Modal -->
<div class="modal fade" id="viewPostModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Full Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="post-content">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<script>
function deletePost(id) {
    if(confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        fetch(`/admin/posts/${id}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                document.getElementById(`post-${id}`).remove();
                alert(data.message);
            }
        });
    }
}

function suspendPost(id) {
    if(confirm('Suspend this post?')) {
        fetch(`/admin/posts/${id}/suspend`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                location.reload();
            }
        });
    }
}

function viewPost(id) {
    // You can load full post content here via AJAX
    new bootstrap.Modal(document.getElementById('viewPostModal')).show();
}
</script>
@endsection