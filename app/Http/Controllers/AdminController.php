<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserRecord;
use App\Models\BadgeVerification;


class AdminController extends Controller
{
    public function showLogins()
    {
        $logins = DB::table('login_details')
            ->join('users_record', 'login_details.user_record_id', '=', 'users_record.id')
            ->select('users_record.username', 'login_details.ip_address', 'login_details.created_at')
            ->orderByDesc('login_details.created_at')
            ->limit(100)
            ->get();

        return view('admin.logins', compact('logins'));
    }

    public function manageUsers()
    {
        $users = UserRecord::with(['badgeVerifications' => function ($query) {
            $query->latest();
        }])->get();

        foreach ($users as $user) {
            if ($user->disabled_until && now()->gte($user->disabled_until)) {
                $user->disabled_until = null;
                $user->disabled_days = null;
                $user->save();
            }
        }

        return view('admin.manage_users', compact('users'));
    }

    public function updateUser(Request $request)
    {
        DB::table('users_record')
            ->where('id', $request->input('id'))
            ->update([
                'role' => $request->input('role'),
                'status' => $request->input('status'),
                'updated_at' => now()
            ]);

        return redirect('/admin/manage_users')->with('success', 'User updated successfully.');
    }

    public function index()
    {
        $verifications = BadgeVerification::latest()->paginate(20);
        return view('admin.verifications.index', compact('verifications'));
    }

    public function show($id)
    {
        $verification = BadgeVerification::findOrFail($id);
        return view('admin.verifications.show', compact('verification'));
    }

    // ✅ FIXED: Disable User - Accept ID from URL
    public function disableUser(Request $request, $id)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:30',
        ]);

        $user = UserRecord::findOrFail($id);
        $user->disabled_until = now()->addDays($request->days);
        $user->disabled_days = $request->days;
        $user->save();

        return back()->with('success', "User {$user->username} disabled for {$request->days} day(s).");
    }

    // ✅ FIXED: Enable User - Accept ID from URL and redirect properly
    public function enableUser(Request $request, $id)
    {
        $user = UserRecord::findOrFail($id);
        $user->disabled_until = null;
        $user->disabled_days = null;
        $user->save();

        return back()->with('success', "User {$user->username} has been enabled.");
    }
}