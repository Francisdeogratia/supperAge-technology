<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />
    <title>Account Settings - SupperAge</title>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --danger-color: #dc2626;
            --danger-dark: #b91c1c;
            --success-color: #16a34a;
            --success-dark: #15803d;
            --warning-color: #ea580c;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: var(--text-primary);
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 32px;
            box-shadow: var(--shadow-lg);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid transparent;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .card-success .card-icon {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
        }

        .card-warning .card-icon {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            color: white;
        }

        .card-danger .card-icon {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
        }

        .card h2 {
            font-size: 1.5rem;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .card p {
            color: var(--text-secondary);
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .btn {
            width: 100%;
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            color: white;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
        }

        .btn-success:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(22, 163, 74, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #c2410c 100%);
        }

        .btn-warning:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(234, 88, 12, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
        }

        .btn-danger:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(220, 38, 38, 0.3);
        }

        .user-info {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-lg);
        }

        .user-info h3 {
            font-size: 1.25rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .info-item {
            padding: 12px;
            background: var(--bg-color);
            border-radius: 8px;
        }

        .info-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }

        .info-value {
            font-weight: 600;
            color: var(--text-primary);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .status-active {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-deactivated {
            background: #fed7aa;
            color: #ea580c;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 40px;
            border-radius: 16px;
            max-width: 500px;
            box-shadow: var(--shadow-lg);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .modal-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .modal h3 {
            font-size: 1.5rem;
            color: var(--text-primary);
        }

        .modal p {
            color: var(--text-secondary);
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .modal-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-secondary {
            background: var(--bg-color);
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: var(--border-color);
        }

        .alert {
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }

        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .back-link:hover {
            transform: translateX(-5px);
        }

        /* Loading Spinner */
        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .settings-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                margin: 20% 20px;
                padding: 24px;
            }

            .modal-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/update') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        <div class="header">
            <h1><i class="fas fa-cog"></i> Account Settings</h1>
            <p>Manage your account preferences and security</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        <!-- User Information -->
        <div class="user-info">
            <h3><i class="fas fa-user"></i> Account Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Username</div>
                    <div class="info-value">@<span>{{ $user->username }}</span></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Account Status</div>
                    <div class="info-value">
                        <span class="status-badge {{ $user->status === 'active' ? 'status-active' : 'status-deactivated' }}">
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="settings-grid">
            <!-- Activate Account Card -->
            @if(isset($user->status) && $user->status === 'deactivated')
            <div class="card card-success">
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Activate Account</h2>
                <p>Restore full access to your account and all features. You'll be able to login and use all services.</p>
                <button class="btn btn-success" onclick="openActivateModal()">
                    <i class="fas fa-unlock"></i> Activate Account
                </button>
            </div>
            @endif

            <!-- Deactivate Account Card -->
            @if(!isset($user->status) || $user->status === 'active')
            <div class="card card-warning">
                <div class="card-icon">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <h2>Deactivate Account</h2>
                <p>Temporarily disable your account. You won't be able to login, but your data will be preserved.</p>
                <button class="btn btn-warning" onclick="openDeactivateModal()">
                    <i class="fas fa-lock"></i> Deactivate Account
                </button>
            </div>
            @endif

            <!-- Delete Account Card -->
            <div class="card card-danger">
                <div class="card-icon">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h2>Delete Account</h2>
                <p>Permanently delete your account and all associated data. This action cannot be undone after 30 days.</p>
                <button class="btn btn-danger" onclick="openDeleteModal()">
                    <i class="fas fa-exclamation-triangle"></i> Delete Account
                </button>
            </div>
        </div>
    </div>

    <!-- Activate Account Modal -->
    <div id="activateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                    <i class="fas fa-unlock"></i>
                </div>
                <h3>Activate Account</h3>
            </div>
            <p>Enter your password to activate your account and restore full access.</p>
            <form id="activateForm">
                @csrf
                <div class="form-group">
                    <label for="activate-password">
                        <i class="fas fa-key"></i> Confirm Password
                    </label>
                    <input type="password" id="activate-password" name="password" required placeholder="Enter your password">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeActivateModal()">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Confirm Activation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Deactivate Account Modal -->
    <div id="deactivateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);">
                    <i class="fas fa-lock"></i>
                </div>
                <h3>Deactivate Account</h3>
            </div>
            <p>Your account will be temporarily disabled. You can reactivate it anytime by logging in.</p>
            <form id="deactivateForm">
                @csrf
                <div class="form-group">
                    <label for="deactivate-password">
                        <i class="fas fa-key"></i> Confirm Password
                    </label>
                    <input type="password" id="deactivate-password" name="password" required placeholder="Enter your password">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeDeactivateModal()">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-lock"></i> Confirm Deactivation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>Delete Account</h3>
            </div>
            <p><strong>Warning:</strong> This action will permanently delete your account. You have 30 days to contact support if you change your mind.</p>
            <form id="deleteForm">
                @csrf
                <div class="form-group">
                    <label for="delete-reason">
                        <i class="fas fa-comment"></i> Reason for Deletion
                    </label>
                    <select id="delete-reason" name="reason" required>
                        <option value="">Select a reason...</option>
                        <option value="no_longer_needed">No longer needed</option>
                        <option value="privacy_concerns">Privacy concerns</option>
                        <option value="switching_platform">Switching to another platform</option>
                        <option value="too_many_emails">Too many emails</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="delete-password">
                        <i class="fas fa-key"></i> Confirm Password
                    </label>
                    <input type="password" id="delete-password" name="password" required placeholder="Enter your password">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Confirm Deletion
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Modal Functions
        function openActivateModal() {
            $('#activateModal').fadeIn(300);
        }

        function closeActivateModal() {
            $('#activateModal').fadeOut(300);
            $('#activateForm')[0].reset();
        }

        function openDeactivateModal() {
            $('#deactivateModal').fadeIn(300);
        }

        function closeDeactivateModal() {
            $('#deactivateModal').fadeOut(300);
            $('#deactivateForm')[0].reset();
        }

        function openDeleteModal() {
            $('#deleteModal').fadeIn(300);
        }

        function closeDeleteModal() {
            $('#deleteModal').fadeOut(300);
            $('#deleteForm')[0].reset();
        }

        // Close modals when clicking outside
        $(window).click(function(event) {
            if ($(event.target).hasClass('modal')) {
                $(event.target).fadeOut(300);
            }
        });

        // Activate Account
        $('#activateForm').submit(function(e) {
            e.preventDefault();
            
            const $btn = $(this).find('button[type="submit"]');
            const originalHtml = $btn.html();
            $btn.html('<div class="spinner"></div> Processing...').prop('disabled', true);

            $.ajax({
                url: '{{ route("account.activate") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        alert('✅ ' + response.message);
                        window.location.href = response.redirect;
                    }
                },
                error: function(xhr) {
                    $btn.html(originalHtml).prop('disabled', false);
                    const message = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                    alert('❌ ' + message);
                }
            });
        });

        // Deactivate Account
        $('#deactivateForm').submit(function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to deactivate your account? You can reactivate it anytime.')) {
                return;
            }

            const $btn = $(this).find('button[type="submit"]');
            const originalHtml = $btn.html();
            $btn.html('<div class="spinner"></div> Processing...').prop('disabled', true);

            $.ajax({
                url: '{{ route("account.deactivate") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        alert('✅ ' + response.message);
                        window.location.href = '/account';
                    }
                },
                error: function(xhr) {
                    $btn.html(originalHtml).prop('disabled', false);
                    const message = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                    alert('❌ ' + message);
                }
            });
        });

        // Delete Account
        $('#deleteForm').submit(function(e) {
            e.preventDefault();
            
            if (!confirm('⚠️ FINAL WARNING: This will permanently delete your account. Are you absolutely sure?')) {
                return;
            }

            const $btn = $(this).find('button[type="submit"]');
            const originalHtml = $btn.html();
            $btn.html('<div class="spinner"></div> Deleting...').prop('disabled', true);

            $.ajax({
                url: '{{ route("account.delete") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        alert('✅ ' + response.message);
                        window.location.href = response.redirect;
                    }
                },
                error: function(xhr) {
                    $btn.html(originalHtml).prop('disabled', false);
                    const message = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                    alert('❌ ' + message);
                }
            });
        });
    </script>

@include('partials.global-calls')
</body>
</html>