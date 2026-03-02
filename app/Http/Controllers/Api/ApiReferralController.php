<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserRecord;

class ApiReferralController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $referralLink = url('/register?ref=' . $user->id);

        $invitedUsers = UserRecord::where('invited_by', $user->id)
            ->select('id', 'name', 'username', 'profileimg', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'username'   => $u->username,
                'profileimg' => $u->profileimg
                    ? asset('storage/' . ltrim($u->profileimg, '/'))
                    : null,
                'created_at' => $u->created_at,
            ]);

        $totalInvited = $invitedUsers->count();
        $perUserBonus = 100;
        $totalEarned  = $totalInvited * $perUserBonus;

        return response()->json([
            'referral_link' => $referralLink,
            'total_invited' => $totalInvited,
            'total_earned'  => $totalEarned,
            'invited_users' => $invitedUsers,
        ]);
    }
}
