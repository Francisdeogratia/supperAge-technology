<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\UserRecord;

class ReferralController extends Controller
{

public function index(Request $request)
{
    $userId = Session::get('id');
    $user   = $userId ? UserRecord::find($userId) : null;

    if (!$user) {
        return redirect('/login')->with('error', 'Please log in to get your referral link.');
    }
// Get task type from query string (default to 'invite')
    $taskType = $request->get('task', 'invite');
// Generate referral link based on task type 
    if ($taskType === 'app') {
// Link to Play Store with referral tracking ,Replace com.supperage.app with your actual app package name on the Play Store.
        $androidLink = 'https://play.google.com/store/apps/details?id=com.supperage.app&referrer=' . $user->id;
        $iosLink = 'https://apps.apple.com/app/id1234567890?referrer=' . $user->id; // Replace with your actual App Store ID
        $inviteLink = [
            'android' => $androidLink,
            'ios' => $iosLink
        ];

        // ✅ Add click tracking link
        $clickTrackLink = route('referral.click', ['id' => $user->id]);

        // ✅ Fetch click logs
        $clicks = DB::table('referral_clicks')
            ->where('referrer_id', $user->id)
            ->orderBy('clicked_at', 'desc')
            ->get();
    } else {
        // Link to your registration page
        $inviteLink = url('/register?ref=' . $user->id);
        $clickTrackLink = null;
        $clicks = collect(); // empty collection
    }
// Count installs for app-sharing task
    $installCount = DB::table('referral_installs')
        ->where('referrer_id', $user->id)
        ->count();
// Get install records for progress tracking
    $installs = DB::table('referral_installs')
        ->where('referrer_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('invite', [
        'inviteLink'     => $inviteLink,
        'user'           => $user,
        'taskType'       => $taskType,
        'installCount'   => $installCount,
        'installs'       => $installs,
        'clickTrackLink' => $clickTrackLink,
        'clicks'         => $clicks
    ]);
}




    public function trackInstall(Request $request)
    {
        $referrerId = $request->input('ref');

        if (!$referrerId || !UserRecord::find($referrerId)) {
            return response()->json(['error' => 'Invalid referral'], 400);
        }

        // Prevent duplicate installs from same device
        $alreadyInstalled = DB::table('referral_installs')
            ->where('referrer_id', $referrerId)
            ->where('device_id', $request->input('device_id'))
            ->exists();

        if ($alreadyInstalled) {
            return response()->json(['message' => 'Already counted'], 200);
        }

        DB::table('referral_installs')->insert([
            'referrer_id' => $referrerId,
            'device_id'   => $request->input('device_id'),
            'platform'    => $request->input('platform'),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Check if referrer has reached 5 installs
        $count = DB::table('referral_installs')
            ->where('referrer_id', $referrerId)
            ->count();

        if ($count === 5) {
            // Reward the referrer
            DB::table('wallet_transactions')->insert([
                'wallet_owner_id' => $referrerId,
                'payer_id'        => $referrerId,
                'amount'          => 1000,
                'status'          => 'successful',
                'type'            => 'task_reward',
                'currency'        => 'NGN',
                'description'     => "Reward for completing task: 5 app downloads",
                'transaction_id'  => uniqid('txn_appshare_'),
                'tx_ref'          => Str::uuid(),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        return response()->json(['message' => 'Install tracked'], 200);
    }

    public function logClick($id)
{
    DB::table('referral_clicks')->insert([
        'referrer_id' => $id,
        'ip_address'  => request()->ip(),
        'user_agent'  => request()->userAgent(),
        'clicked_at'  => now(),
    ]);

    return redirect('https://play.google.com/store/apps/details?id=com.supperage.app&referrer=' . $id);
    
}

}
