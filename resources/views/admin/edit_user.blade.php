@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Edit User: {{ $user->name }}</h2>
        <a href="{{ route('admin.users.now') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-edit"></i> User Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update.now', $user->id) }}">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" 
                                       value="{{ $user->username }}" disabled>
                                <small class="text-muted">Username cannot be changed</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country" 
                                       value="{{ old('country', $user->country) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Account Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.now') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- User Profile Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-id-card"></i> Profile</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $user->profileimg ?? asset('images/default-avatar.png') }}" 
                         class="rounded-circle mb-3" width="120" height="120" alt="Profile">
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ '@' . $user->username }}</p>
                    
                    <div class="mt-3">
                        <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }} mb-2">
                            {{ ucfirst($user->status) }}
                        </span>
                        @if($user->unsetacct == 'locked')
                            <span class="badge bg-warning mb-2">Locked</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Details Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Account Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">User ID:</small>
                        <div><strong>{{ $user->id }}</strong></div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Special Code:</small>
                        <div><strong>{{ $user->specialcode }}</strong></div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Joined:</small>
                        <div><strong>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</strong></div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Last Updated:</small>
                        <div><strong>{{ \Carbon\Carbon::parse($user->updated_at)->diffForHumans() }}</strong></div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.users.posts.now', $user->id) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="fas fa-images"></i> View Posts
                    </a>
                    <a href="{{ route('admin.users.tales.now', $user->id) }}" class="btn btn-outline-info btn-sm w-100 mb-2">
                        <i class="fas fa-book"></i> View Tales
                    </a>
                    <a href="{{ route('admin.users.message.now', $user->id) }}" class="btn btn-outline-success btn-sm w-100 mb-2">
                        <i class="fas fa-envelope"></i> Send Message
                    </a>
                    <a href="{{ route('admin.users.access.now', $user->id) }}" class="btn btn-outline-warning btn-sm w-100">
                        <i class="fas fa-sign-in-alt"></i> Access Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="toast show" role="alert">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif
@endsection