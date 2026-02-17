@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Tales by {{ $user->name }} ({{ '@' . $user->username }})</h2>
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
                    <p class="mb-0"><strong>Total Tales:</strong> {{ $tales->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($tales as $tale)
        <div class="col-md-6 col-lg-4 mb-4" id="tale-{{ $tale->tales_id }}">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="badge {{ !$tale->deleted_at ? 'bg-success' : 'bg-danger' }}">
                        {{ !$tale->deleted_at ? 'Active' : 'Suspended' }}
                    </span>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($tale->created_at)->format('M d, Y') }}</small>
                </div>
                
                @if($tale->files_talesexten)
                    <img src="{{ $tale->files_talesexten }}" class="card-img-top" alt="Tale media" style="height: 200px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">{{ $tale->tales_types }}</h6>
                    <p class="card-text small">{{ Str::limit($tale->tales_content, 150) }}</p>
                    
                    <div class="d-flex justify-content-between text-muted small">
                        <span><i class="fas fa-eye"></i> {{ $tale->views ?? 0 }}</span>
                        <span><i class="fas fa-heart"></i> {{ $tale->likes ?? 0 }}</span>
                        <span><i class="fas fa-share"></i> {{ $tale->shares ?? 0 }}</span>
                    </div>
                </div>
                
                <div class="card-footer bg-white">
                    <div class="btn-group w-100" role="group">
                        @if(!$tale->deleted_at)
                            <button class="btn btn-sm btn-warning" onclick="suspendTale({{ $tale->tales_id }})">
                                <i class="fas fa-ban"></i> Suspend
                            </button>
                        @endif
                        <button class="btn btn-sm btn-danger" onclick="deleteTale({{ $tale->tales_id }})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> This user has no tales yet.
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $tales->links() }}
    </div>
</div>

<script>
function deleteTale(id) {
    if(confirm('Are you sure you want to delete this tale? This action cannot be undone.')) {
        fetch(`/admin/tales/${id}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                document.getElementById(`tale-${id}`).remove();
                alert(data.message);
            }
        });
    }
}

function suspendTale(id) {
    if(confirm('Suspend this tale?')) {
        fetch(`/admin/tales/${id}/suspend`, {
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
</script>
@endsection