<head>
    <meta charset="UTF-8">
    <title>User Management</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-pVrmA4+z0mZyZl7ZVZl5ZVZl5ZVZl5ZVZl5ZVZl5ZVZl5ZVZl5ZVZl5ZVZl5ZVZl5ZVZl5ZVZl5Z"
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />

    <!-- Your custom styles -->
    <style>
        /* your CSS here */
   .user-page-container {
        max-width: 1300px;
        margin: 20px auto;
        font-family: 'Segoe UI', sans-serif;
        padding: 1rem;
    }

    .search-bar {
        text-align: right;
        margin-bottom: 12px;
    }

    .search-bar input[type="text"] {
        padding: 8px;
        width: 280px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    th {
        background-color: #2c3e50;
        color: white;
        padding: 10px;
        text-align: left;
    }

    td {
        padding: 10px;
        border-bottom: 1px solid #e1e1e1;
        vertical-align: middle;
    }

    tr:hover {
        background-color: #f4f4f4;
    }

    select, button {
        padding: 5px;
        margin-right: 5px;
        border-radius: 3px;
        border: 1px solid #ccc;
        font-size: 13px;
    }

    button {
        background-color: #3498db;
        color: white;
        cursor: pointer;
    }

    
.success-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    animation: fadeIn 0.4s ease-in-out;
}

.success-modal-content {
    background-color: #2ecc71;
    color: white;
    padding: 20px 30px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    font-size: 16px;
    position: relative;
    min-width: 280px;
}

.success-icon {
    font-size: 30px;
    display: block;
    margin-bottom: 10px;
    animation: pop 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes pop {
    0% { transform: scale(0.6); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
/* spinner */
.spinner {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid #ccc;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
    vertical-align: middle;
    margin-left: 5px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* profile */
.profile-img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    vertical-align: middle;
    margin-right: 8px;
    border: 1px solid #ccc;
}
    </style>
</head>



<!-- success msg -->
@if(session('success'))
<div id="successModal" class="success-modal">
    <div class="success-modal-content">
        <span class="success-icon">✅</span>
        <p>{{ session('success') }}</p>
    </div>
</div>

<script>
    setTimeout(() => {
        document.getElementById('successModal').style.display = 'none';
    }, 3000);
</script>
@endif


<div class="user-page-container">
    <div class="search-bar">
        🔎 <input type="text" id="searchInput" placeholder="Search by username...">
    </div>

    <table id="userTable">
        <thead>
            <tr>
                <th>Profile</th>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
                <th>Badge Status</th>
                <th>Enable/Disable</th>
            </tr>
        </thead>
        <tbody>
@foreach($users as $user)
<tr>
    <!-- Profile Image Column -->
    <td>
    @if($user->profileimg)
        <a href="{{ route('admin.verifications.show', $user->badgeVerifications->first()->id ?? 0) }}">
            <img src="{{ str_replace('/upload/', '/upload/w_60,h_60,c_fill,r_max,q_70/', $user->profileimg) }}"
                 alt="{{ $user->name }}'s profile"
                 class="profile-img">
        </a>
    @else
        <a href="{{ route('admin.verifications.show', $user->badgeVerifications->first()->id ?? 0) }}">
            <i class="fa fa-user-circle" style="font-size:40px; color:#555;"></i>
        </a>
    @endif
</td>


    <!-- Username Column -->
    <td>
    <a href="{{ route('admin.verifications.show', $user->badgeVerifications->first()->id ?? 0) }}" style="text-decoration:none;">
        {{ $user->name }}
    </a>
    @if($user->badge_status)
        <img src="{{ asset($user->badge_status) }}" 
             alt="Verified" 
             title="Verified User" 
             style="width:16px;height:16px;margin-left:5px;vertical-align:middle;">
    @endif
</td>


    <td>{{ $user->role }}</td>
    <td>{{ $user->status }}</td>

    <!-- Actions Column -->
    <td>
        <form method="POST" action="/admin/users/update">
            @csrf
            <input type="hidden" name="id" value="{{ $user->id }}">
            <select name="role">
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <select name="status">
                <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="locked" {{ $user->status == 'locked' ? 'selected' : '' }}>Locked</option>
            </select>
            <button type="submit">Update</button>
        </form>
    </td>

    <!-- Badge Status Column -->
    <td>
        @if($user->badgeVerifications->isNotEmpty())
            {{ ucfirst($user->badgeVerifications->first()->status ?? 'none') }}
            <form method="POST" action="{{ route('admin.badge.update', $user->badgeVerifications->first()->id) }}" class="badge-form">
                @csrf
                <select name="status" class="badge-status">
                    <option value="pending" {{ $user->badgeVerifications->first()->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $user->badgeVerifications->first()->status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $user->badgeVerifications->first()->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <span class="spinner" style="display:none;"></span>
            </form>
        @else
            —
        @endif
    </td>

<!-- for enable and disable -->
 <td>
    @if($user->disabled_until && now()->lt($user->disabled_until))
        @php
            $endDate = \Carbon\Carbon::parse($user->disabled_until);
            $startDate = $endDate->copy()->subDays($user->disabled_days ?? 0);
        @endphp

        <small class="text-danger d-block mb-1">
            Your account has been disabled for {{ $user->disabled_days }} {{ Str::plural('day', $user->disabled_days) }}
            from {{ $startDate->format('M j, Y') }},
            it will be enabled and active on {{ $endDate->format('Y-m-d H:i:s') }}
            ({{ $endDate->diffForHumans() }}) if requirements are met.
        </small>

        {{-- ✅ FIXED: Pass user ID in route, not in form --}}
        <form method="POST" action="{{ route('admin.users.enable', $user->id) }}">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">Enable Now</button>
        </form>
    @else
        {{-- Active --}}
        {{-- ✅ FIXED: Pass user ID in route, not in form --}}
        <form method="POST" action="{{ route('admin.users.disable', $user->id) }}">
            @csrf
            <select name="days" required>
                <option value="">-- Select Days --</option>
                <option value="1">1 day</option>
                <option value="2">2 days</option>
                <option value="3">3 days</option>
                <option value="5">5 days</option>
                <option value="7">7 days</option>
                <option value="10">10 days</option>
                <option value="15">15 days</option>
                <option value="25">25 days</option>
                <option value="30">30 days</option>
            </select>
            <button type="submit" class="btn btn-danger btn-sm">Disable</button>
        </form>
    @endif
</td>

</tr>
@endforeach
</tbody>


    </table>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let searchTerm = this.value.toLowerCase();
        let rows = document.querySelectorAll('#userTable tbody tr');

        rows.forEach(row => {
            let username = row.children[1].textContent.toLowerCase(); // Changed from [0] to [1] for username column
            row.style.display = username.includes(searchTerm) ? '' : 'none';
        });
    });

    
document.querySelectorAll('.badge-status').forEach(function(select) {
    select.addEventListener('change', function() {
        let form = this.closest('form');
        let spinner = form.querySelector('.spinner');
        spinner.style.display = 'inline-block';
        form.submit();
    });
});


</script>