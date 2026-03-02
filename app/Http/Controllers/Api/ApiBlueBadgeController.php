<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiBlueBadgeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'gov_id'      => 'required|file|max:5120',
            'profile_pic' => 'required|file|max:3072',
        ]);

        $user = $request->user();

        $govIdPath     = $request->file('gov_id')->store('badge_verification', 'public');
        $profilePicPath = $request->file('profile_pic')->store('badge_verification', 'public');

        \DB::table('badge_verifications')->insert([
            'user_id'          => $user->id,
            'full_name'        => $user->name,
            'gov_id_path'      => $govIdPath,
            'profile_pic_path' => $profilePicPath,
            'status'           => 'pending',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // Mark badge as pending
        $user->badge_status    = 'images/pending2.png';
        $user->badge_expires_at = Carbon::now()->addDays(30);
        $user->save();

        return response()->json(['message' => 'Verification submitted. We will review it within 3-5 business days.']);
    }
}
