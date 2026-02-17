<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\BadgeVerification;
use App\Models\UserRecord;
use Carbon\Carbon; // ✅ Add this line

class BadgeVerificationController extends Controller
{
    
   public function store(Request $request)
{
    $validated = $request->validate([
        'gov_id'       => 'required|file|max:5120',
        'profile_pic'  => 'required|image|max:3072',
        'notes'        => 'nullable|string|max:1000',
    ]);

    $username = Session::get('username');
    $user = $username
        ? UserRecord::where('username', $username)->first()
        : null;

    if (!$user) {
        return back()->withErrors(['error' => 'User not found.']);
    }

    // Store files
    $govIdPath = $request->file('gov_id')->store('badge_verification', 'public');
    $profilePicPath = $request->file('profile_pic')->store('badge_verification', 'public');

    // Save verification record
    BadgeVerification::create([
        'user_id'            => $user->id,
        'full_name'          => $user->name,
        'gov_id_path'        => $govIdPath,
        'profile_pic_path' => $profilePicPath,
        'notes'              => $validated['notes'] ?? null,
        'status'             => 'pending',
    ]);

    // ✅ Now activate badge
    $user->badge_status = 'images/pending2.png';
    $user->badge_expires_at = Carbon::now()->addDays(30);
    $user->save();

    // ✅ Redirect somewhere safe — NOT to /badge/verify-from-wallet
    return redirect()->route('blue-badge')->with('submitted', true);
}

}
