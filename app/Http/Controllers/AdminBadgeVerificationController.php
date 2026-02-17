<?php

namespace App\Http\Controllers;

use App\Models\BadgeVerification;
use App\Models\UserRecord; // <-- Make sure you have this model for users_record table
use Illuminate\Http\Request;

class AdminBadgeVerificationController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // 1️⃣ Update badge_verifications table
        $verification = BadgeVerification::findOrFail($id);
        $verification->status = $request->status;
        $verification->save();

        // 2️⃣ Update users_record table
        $userRecord = UserRecord::find($verification->user_id);
        if ($userRecord) {
            // Always update status_one
            $userRecord->status_one = $request->status;

            // If approved, set badge_status to verification icon path
            if ($request->status === 'approved') {
                $userRecord->badge_status = 'images/verified.png'; 
                // Place verified.png in public/images/
            } else {
                $userRecord->badge_status = null; // Clear if not approved
            }

            $userRecord->save();
        }

        return back()->with('success', 'Badge verification status updated.');
    }
}
