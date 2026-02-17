<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\UserRecord;
use App\Models\WalletTransaction;
use Carbon\Carbon;

class GeneralAdminController extends Controller
{
    // Middleware to check if user is admin
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Session::get('role') !== 'admin') {
                return redirect('/')->with('error', 'Unauthorized access');
            }
            return $next($request);
        });
    }

    // Main admin dashboard
    public function dashboardNow()
{
    $totalUsers = UserRecord::count();
    $activeUsers = UserRecord::where('status', 'active')->count();
    $suspendedUsers = UserRecord::where('status', 'suspended')->count();
    $totalPosts = DB::table('sample_posts')->count();
    $totalTales = DB::table('tales_extens')->count();
    $pendingPayments = DB::table('payment_applications')->where('status', 'pending')->count();
    $pendingReports = DB::table('user_reports')->where('status', 'pending')->count();

    return view('admin.dashboard', compact(
        'totalUsers',
        'activeUsers',
        'suspendedUsers',
        'totalPosts',
        'totalTales',
        'pendingPayments',
        'pendingReports'
    ));
}

    // Users management
    public function usersNow(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $users = UserRecord::query()
            ->when($search, function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->when($status, function($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    // Edit user
    public function editUserNow($id)
    {
        $user = UserRecord::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }

    // Update user
    public function updateUserNow(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'status' => 'required|in:active,suspended',
        ]);

        $user = UserRecord::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'gender' => $request->gender,
            'country' => $request->country,
        ]);

        return redirect()->route('admin.users.now')->with('success', 'User updated successfully');
    }

    // Delete user
    public function deleteUserNow($id)
    {
        $user = UserRecord::findOrFail($id);
        
        // Delete related data
        DB::table('sample_posts')->where('user_id', $id)->delete();
        DB::table('tales_extens')->where('specialcode', $user->specialcode)->delete();
        DB::table('wallet_transactions')->where('wallet_owner_id', $id)->delete();
        
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    // Suspend user
    public function suspendUserNow($id, Request $request)
    {
        $user = UserRecord::findOrFail($id);
        $days = $request->input('days', 7);

        $user->update([
            'status' => 'suspended',
            'disabled_until' => now()->addDays($days),
            'disabled_days' => $days
        ]);

        return response()->json(['success' => true, 'message' => "User suspended for {$days} days"]);
    }

    // Enable user
    public function enableUserNow($id)
    {
        $user = UserRecord::findOrFail($id);
        $user->update([
            'status' => 'active',
            'disabled_until' => null,
            'disabled_days' => null
        ]);

        return response()->json(['success' => true, 'message' => 'User enabled successfully']);
    }

    // Lock/Unlock account
    public function toggleLockNow($id)
    {
        $user = UserRecord::findOrFail($id);
        $newStatus = $user->unsetacct === 'locked' ? 'unlocked' : 'locked';
        
        $user->update(['unsetacct' => $newStatus]);

        return response()->json(['success' => true, 'message' => "Account {$newStatus} successfully"]);
    }

    // Access user account (login as user)
    public function accessUserAccountNow($id)
    {
        $user = UserRecord::findOrFail($id);
        
        // Store admin session
        Session::put('admin_id', Session::get('id'));
        Session::put('admin_mode', true);
        
        // Login as user
        Session::put('specialcode', $user->specialcode);
        Session::put('id', $user->id);
        Session::put('username', $user->username);
        Session::put('role', $user->role);

        return redirect()->route('update')->with('info', 'Viewing as ' . $user->name);
    }

    // View user posts
    public function userPostsNow($id)
    {
        $user = UserRecord::findOrFail($id);
        
        // Get posts - check which ID column exists
        $posts = DB::table('sample_posts')
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.user_posts', compact('user', 'posts'));
    }

    // Delete post
    public function deletePostNow($id)
    {
        // Try both possible ID column names
        $deleted = DB::table('sample_posts')->where('post_id', $id)->delete();
        if (!$deleted) {
            $deleted = DB::table('sample_posts')->where('id', $id)->delete();
        }
        
        return response()->json(['success' => true, 'message' => 'Post deleted successfully']);
    }

    // Suspend post
    public function suspendPostNow($id)
    {
        // Try both possible ID column names
        $updated = DB::table('sample_posts')->where('post_id', $id)->update([
            'status' => 'suspended',
            'deleted_at' => now()
        ]);
        
        if (!$updated) {
            DB::table('sample_posts')->where('id', $id)->update([
                'status' => 'suspended',
                'deleted_at' => now()
            ]);
        }
        
        return response()->json(['success' => true, 'message' => 'Post suspended successfully']);
    }

    // View user tales
    public function userTalesNow($id)
    {
        $user = UserRecord::findOrFail($id);
        $tales = DB::table('tales_extens')
            ->where('specialcode', $user->specialcode)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.user_tales', compact('user', 'tales'));
    }

    // Delete tale
    public function deleteTaleNow($id)
    {
        DB::table('tales_extens')->where('tales_id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Tale deleted successfully']);
    }

    // Suspend tale
    public function suspendTaleNow($id)
    {
        DB::table('tales_extens')->where('tales_id', $id)->update([
            'deleted_at' => now()
        ]);
        return response()->json(['success' => true, 'message' => 'Tale suspended successfully']);
    }

    // Payment applications
    public function paymentApplicationsNow()
    {
        $applications = DB::table('payment_applications')
            ->join('users_record', 'payment_applications.user_id', '=', 'users_record.id')
            ->select('payment_applications.*', 'users_record.name', 'users_record.username', 'users_record.email')
            ->orderBy('payment_applications.created_at', 'desc')
            ->paginate(20);

        return view('admin.payment_applications', compact('applications'));
    }

    // Process payment to user
    // Replace the payUserNow() method in GeneralAdminController.php with this:

public function payUserNow(Request $request, $userId)
{
    $request->validate([
        'amount' => 'required|numeric|min:1',
        'currency' => 'required|string',
        'payment_app_id' => 'required|exists:payment_applications,id'
    ]);

    DB::beginTransaction();
    try {
        // Get the payment application details
        $application = DB::table('payment_applications')->where('id', $request->payment_app_id)->first();
        
        if (!$application) {
            return response()->json(['success' => false, 'message' => 'Application not found'], 404);
        }
        
        // Calculate amounts
        $requestedAmount = $application->amount_requested;
        $userReceives = $application->amount_to_receive ?? ($requestedAmount * 0.70);
        $platformFee = $application->platform_fee ?? ($requestedAmount * 0.30);
        
        // 🔴 IMPORTANT: DEBIT the user's wallet (NEGATIVE amount)
        WalletTransaction::create([
            'wallet_owner_id' => $userId,
            'payer_id' => Session::get('id'),
            'transaction_id' => uniqid('admin_debit_'),
            'tx_ref' => uniqid('tx_'),
            'amount' => -$requestedAmount, // 👈 NEGATIVE = DEBIT
            'currency' => $request->currency,
            'status' => 'successful',
            'type' => 'withdrawal',
            'description' => "Withdrawal request approved - User receives: {$userReceives} {$request->currency}, Platform fee: {$platformFee} {$request->currency}"
        ]);
        
        // Record platform fee (credited to platform)
        WalletTransaction::create([
            'wallet_owner_id' => 1, // Admin/Platform wallet (change this to your admin user ID)
            'payer_id' => $userId,
            'transaction_id' => uniqid('platform_fee_'),
            'tx_ref' => uniqid('tx_'),
            'amount' => $platformFee, // POSITIVE = CREDIT to platform
            'currency' => $request->currency,
            'status' => 'successful',
            'type' => 'platform_fee',
            'description' => "Platform fee from withdrawal by user #{$userId}"
        ]);

        // Update payment application status
        DB::table('payment_applications')
            ->where('id', $request->payment_app_id)
            ->update([
                'status' => 'paid',
                'paid_at' => now(),
                'paid_by' => Session::get('id')
            ]);

        DB::commit();
        
        return response()->json([
            'success' => true, 
            'message' => "Payment processed! User's wallet debited {$requestedAmount} {$request->currency}. User receives: {$userReceives}, Platform fee: {$platformFee}"
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false, 
            'message' => 'Payment failed: ' . $e->getMessage()
        ], 500);
    }
}

    // Send message to user
    public function messageUserNow($id)
    {
        $user = UserRecord::findOrFail($id);
        return view('admin.message_user', compact('user'));
    }

    // Send message
    public function sendMessageNow(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        DB::table('admin_messages')->insert([
            'user_id' => $id,
            'admin_id' => Session::get('id'),
            'message' => $request->message,
            'created_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }

    // Approve payment application
    public function approveApplicationNow($id)
    {
        DB::table('payment_applications')
            ->where('id', $id)
            ->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Session::get('id')
            ]);

        return response()->json(['success' => true, 'message' => 'Application approved successfully']);
    }

    // Reject payment application
    public function rejectApplicationNow(Request $request, $id)
    {
        DB::table('payment_applications')
            ->where('id', $id)
            ->update([
                'status' => 'rejected',
                'admin_note' => $request->reason
            ]);

        return response()->json(['success' => true, 'message' => 'Application rejected']);
    }


    public function applicationDetailsNow($id)
{
    $app = DB::table('payment_applications')
        ->join('users_record', 'payment_applications.user_id', '=', 'users_record.id')
        ->select('payment_applications.*', 'users_record.name', 'users_record.username', 'users_record.email', 'users_record.phone')
        ->where('payment_applications.id', $id)
        ->first();
    
    if (!$app) {
        return response()->json(['success' => false, 'message' => 'Application not found'], 404);
    }
    
    $html = view('admin.partials.application_details', compact('app'))->render();
    
    return response()->json(['success' => true, 'html' => $html]);
  }

  public function getPendingApplication($userId)
{
    $application = DB::table('payment_applications')
        ->where('user_id', $userId)
        ->whereIn('status', ['pending', 'approved'])
        ->orderBy('created_at', 'desc')
        ->first();
    
    return response()->json([
        'success' => true,
        'application' => $application
    ]);
}

// userreport

// View user reports
public function userReportsNow(Request $request)
{
    $status = $request->get('status', 'pending');
    
    $reports = DB::table('user_reports')
        ->join('users_record as reporter', 'user_reports.reporter_id', '=', 'reporter.id')
        ->join('users_record as reported', 'user_reports.reported_user_id', '=', 'reported.id')
        ->leftJoin('users_record as reviewer', 'user_reports.reviewed_by', '=', 'reviewer.id')
        ->select(
            'user_reports.*',
            'reporter.name as reporter_name',
            'reporter.username as reporter_username',
            'reporter.email as reporter_email',
            'reported.name as reported_name',
            'reported.username as reported_username',
            'reported.email as reported_email',
            'reported.status as reported_status',
            'reviewer.name as reviewer_name'
        )
        ->when($status !== 'all', function($q) use ($status) {
            $q->where('user_reports.status', $status);
        })
        ->orderBy('user_reports.created_at', 'desc')
        ->paginate(20);

    return view('admin.user_reports', compact('reports', 'status'));
}

// Warn user
public function warnUserNow(Request $request, $reportId)
{
    $request->validate([
        'warning_message' => 'required|string|max:1000'
    ]);

    DB::beginTransaction();
    try {
        $report = DB::table('user_reports')->where('id', $reportId)->first();
        
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }

        // Update report
        DB::table('user_reports')->where('id', $reportId)->update([
            'status' => 'action_taken',
            'action_taken' => 'warned',
            'admin_note' => $request->warning_message,
            'reviewed_by' => Session::get('id'),
            'reviewed_at' => now()
        ]);

        // Send notification to reported user with ALL required fields
        DB::table('notifications')->insert([
            'user_id' => Session::get('id'),
            'notification_reciever_id' => $report->reported_user_id,
            'type' => 'warning',
            'message' => "⚠️ WARNING: Your account has been flagged. Reason: " . $request->warning_message,
            'link' => 'http://127.0.0.1:8000/profile/' . $report->reported_user_id,
            'notifiable_type' => 'App\Models\UserRecord',
            'notifiable_id' => $report->reported_user_id,
            'data' => json_encode([
                'report_id' => $reportId,
                'warning_message' => $request->warning_message,
                'warned_by' => Session::get('username')
            ]),
            'read_notification' => 'no',
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::commit();
        return response()->json(['success' => true, 'message' => 'User warned successfully']);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()], 500);
    }
}

// Block/Suspend user
public function blockUserNow(Request $request, $reportId)
{
    $request->validate([
        'block_reason' => 'required|string|max:1000',
        'block_days' => 'required|integer|min:1|max:365'
    ]);

    DB::beginTransaction();
    try {
        $report = DB::table('user_reports')->where('id', $reportId)->first();
        
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }

        $user = UserRecord::find($report->reported_user_id);
        
        // Suspend user
        $user->update([
            'status' => 'suspended',
            'disabled_until' => now()->addDays($request->block_days),
            'disabled_days' => $request->block_days
        ]);

        // Update report
        DB::table('user_reports')->where('id', $reportId)->update([
            'status' => 'action_taken',
            'action_taken' => 'suspended',
            'admin_note' => $request->block_reason,
            'reviewed_by' => Session::get('id'),
            'reviewed_at' => now()
        ]);

        // Send notification with ALL required fields
        DB::table('notifications')->insert([
            'user_id' => Session::get('id'),
            'notification_reciever_id' => $report->reported_user_id,
            'type' => 'suspension',
            'message' => "🚫 Your account has been suspended for {$request->block_days} days. Reason: " . $request->block_reason,
            'link' => 'http://127.0.0.1:8000/profile/' . $report->reported_user_id,
            'notifiable_type' => 'App\Models\UserRecord',
            'notifiable_id' => $report->reported_user_id,
            'data' => json_encode([
                'report_id' => $reportId,
                'block_days' => $request->block_days,
                'block_reason' => $request->block_reason,
                'blocked_by' => Session::get('username'),
                'disabled_until' => now()->addDays($request->block_days)->toDateTimeString()
            ]),
            'read_notification' => 'no',
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::commit();
        return response()->json(['success' => true, 'message' => "User suspended for {$request->block_days} days"]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()], 500);
    }
}

// Delete reported user
public function deleteReportedUserNow(Request $request, $reportId)
{
    $request->validate([
        'delete_reason' => 'required|string|max:1000'
    ]);

    DB::beginTransaction();
    try {
        $report = DB::table('user_reports')->where('id', $reportId)->first();
        
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }

        $user = UserRecord::find($report->reported_user_id);
        
        // Send notification before deleting with ALL required fields
        DB::table('notifications')->insert([
            'user_id' => Session::get('id'),
            'notification_reciever_id' => $report->reported_user_id,
            'type' => 'account_deletion',
            'message' => "🚫 Your account has been permanently deleted. Reason: " . $request->delete_reason,
            'link' => 'http://127.0.0.1:8000/',
            'notifiable_type' => 'App\Models\UserRecord',
            'notifiable_id' => $report->reported_user_id,
            'data' => json_encode([
                'report_id' => $reportId,
                'delete_reason' => $request->delete_reason,
                'deleted_by' => Session::get('username'),
                'deleted_at' => now()->toDateTimeString()
            ]),
            'read_notification' => 'no',
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Delete user's content
        DB::table('sample_posts')->where('user_id', $user->id)->delete();
        DB::table('tales_extens')->where('specialcode', $user->specialcode)->delete();
        DB::table('wallet_transactions')->where('wallet_owner_id', $user->id)->delete();
        
        // Update report before deleting user
        DB::table('user_reports')->where('id', $reportId)->update([
            'status' => 'action_taken',
            'action_taken' => 'deleted',
            'admin_note' => $request->delete_reason,
            'reviewed_by' => Session::get('id'),
            'reviewed_at' => now()
        ]);

        // Delete user
        $user->delete();

        DB::commit();
        return response()->json(['success' => true, 'message' => 'User account deleted permanently']);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()], 500);
    }
}
// Dismiss report
public function dismissReportNow(Request $request, $reportId)
{
    DB::table('user_reports')->where('id', $reportId)->update([
        'status' => 'dismissed',
        'admin_note' => $request->note ?? 'Report dismissed - no action needed',
        'reviewed_by' => Session::get('id'),
        'reviewed_at' => now()
    ]);

    return response()->json(['success' => true, 'message' => 'Report dismissed']);
}

public function reportDetailsNow($id)
{
    $report = DB::table('user_reports')
        ->join('users_record as reporter', 'user_reports.reporter_id', '=', 'reporter.id')
        ->join('users_record as reported', 'user_reports.reported_user_id', '=', 'reported.id')
        ->leftJoin('users_record as reviewer', 'user_reports.reviewed_by', '=', 'reviewer.id')
        ->select(
            'user_reports.*',
            'reporter.name as reporter_name',
            'reporter.username as reporter_username',
            'reporter.email as reporter_email',
            'reporter.profileimg as reporter_img',
            'reported.name as reported_name',
            'reported.username as reported_username',
            'reported.email as reported_email',
            'reported.profileimg as reported_img',
            'reported.status as reported_status',
            'reported.disabled_until',
            'reviewer.name as reviewer_name'
        )
        ->where('user_reports.id', $id)
        ->first();
    
    if (!$report) {
        return response()->json(['success' => false, 'message' => 'Report not found'], 404);
    }
    
    $html = view('admin.partials.report_details', compact('report'))->render();
    
    return response()->json(['success' => true, 'html' => $html]);
}

}