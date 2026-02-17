<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller; // <-- THIS is the fix
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\LoginDetail;



class AccountController extends Controller
{
    public function show(Request $request)
    {
        
        $userAgent = $request->header('User-Agent');
        $isBot = preg_match('/facebookexternalhit|Facebot|Twitterbot|WhatsApp|LinkedInBot/i', $userAgent);

        // Redirect logged-in users if not a bot
        if (!$isBot && Session::has('specialcode')) {
            return redirect('update');
        }

        $mpost_id = $request->query('eachpost', 0);
        $title = "SupperAge Post";
        $desc = "This post could not be found.";
        $image = asset('images/best3.png');
       

        if ($mpost_id > 0) {
            $post = DB::table('sample_post')->where('post_id', $mpost_id)->first();

            if ($post) {
                $title = substr($post->post_content, 0, 60);
                $desc = substr($post->post_content, 0, 150);
                $imageList = json_decode($post->file_path, true);
                $image = is_array($imageList) ? $imageList[0] : $post->file_path;
            } else {
                return response("<pre>Post not found for ID: $mpost_id</pre>");
            }
        }

        return view('account', compact('title', 'desc', 'image'));
    }

public function showAccountSettings()
{
    $userId = Session::get('id');

    if (!$userId) {
        return redirect('/account');
    }

    $user = DB::table('users_record')->where('id', $userId)->first();

    if (!$user) {
        return redirect('/account');
    }

    return view('account-settings', compact('user'));
}

public function deactivateAccount(Request $request)
{
    $userId = Session::get('id');

    if (!$userId) {
        return response()->json(['status' => 'error', 'message' => 'Not authenticated.'], 401);
    }

    $request->validate(['password' => 'required|string']);

    $user = DB::table('users_record')->where('id', $userId)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['status' => 'error', 'message' => 'Incorrect password.'], 400);
    }

    DB::table('users_record')->where('id', $userId)->update([
        'status' => 'deactivated',
        'updated_at' => now(),
    ]);

    // Log the user out
    LoginDetail::where('user_record_id', $userId)
        ->latest()
        ->update(['logout_at' => now()]);

    Session::flush();
    Cookie::queue(Cookie::forget('username'));

    return response()->json([
        'status' => 'success',
        'message' => 'Your account has been deactivated. You can reactivate it by logging in again.',
    ]);
}

public function activateAccount(Request $request)
{
    $userId = Session::get('id');

    if (!$userId) {
        return response()->json(['status' => 'error', 'message' => 'Not authenticated.'], 401);
    }

    $request->validate(['password' => 'required|string']);

    $user = DB::table('users_record')->where('id', $userId)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['status' => 'error', 'message' => 'Incorrect password.'], 400);
    }

    DB::table('users_record')->where('id', $userId)->update([
        'status' => 'active',
        'updated_at' => now(),
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Your account has been activated successfully!',
        'redirect' => route('account.settings'),
    ]);
}

public function deleteAccount(Request $request)
{
    $userId = Session::get('id');

    if (!$userId) {
        return response()->json(['status' => 'error', 'message' => 'Not authenticated.'], 401);
    }

    $request->validate([
        'password' => 'required|string',
        'reason' => 'required|string',
    ]);

    $user = DB::table('users_record')->where('id', $userId)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['status' => 'error', 'message' => 'Incorrect password.'], 400);
    }

    // Soft delete: mark as deleted with a timestamp so it can be recovered within 30 days
    DB::table('users_record')->where('id', $userId)->update([
        'status' => 'deleted',
        'updated_at' => now(),
    ]);

    // Log the user out
    LoginDetail::where('user_record_id', $userId)
        ->latest()
        ->update(['logout_at' => now()]);

    Session::flush();
    Cookie::queue(Cookie::forget('username'));

    return response()->json([
        'status' => 'success',
        'message' => 'Your account has been scheduled for deletion. You have 30 days to contact support if you change your mind.',
        'redirect' => '/account',
    ]);
}

public function register(Request $request)
{
    // Validate input
    $validator = Validator::make($request->all(), [
    'name'                  => 'required|string|min:3',
    'username'              => 'required|string|min:3|unique:users_record,username',
    'email'                 => 'required|email|unique:users_record,email',
    'phone'                 => 'required|string|max:15',
    'password'              => 'required|string|min:6|confirmed',
    'gender'                => 'required|string',
    'dob'                   => 'required|date',
    'continent'             => 'required|string',
    'country'               => 'required|string',
]);


    if ($validator->fails()) {
        return Response::make($validator->errors()->first(), 400);
    }

    // Prepare data
    $specialcode = generateSpecialcode(); // helper function, you can import yours
    $created = now()->format('Y-m-d');
    $status = "active";
    $emailstatus = "email not verified";
    $phonestatus = "verified";
    $unsetacct = "locked";


//     $refId = $request->get('ref') ?? Session::get('ref');
// $registrationType = $refId ? 'referral' : 'normal';

    try {
       DB::table('users_record')->insert([
    'specialcode' => $specialcode,
    'name'        => $request->input('name'),
    'username'    => $request->input('username'),
    'email'       => $request->input('email'),
    'phone'       => $request->input('phone'),
    'password'    => Hash::make($request->input('password')),
    'gender'      => $request->input('gender'),
    'dob'         => $request->input('dob'),
    'continent'   => $request->input('continent'),
    'country'     => $request->input('country'),
    'created'     => $created,
    'status'      => $status,
    'email_status'=> $emailstatus,
    'phone_status'=> $phonestatus,
    'unsetacct'   => $unsetacct,
    'registration_type' => 'normal',
    'created_at'  => now(),
    'updated_at'  => now(),
]);


       // Set cookie to remember user has registered (expires in 5 years)
       $cookie = cookie('has_account', 'true', 60 * 24 * 365 * 5);

       return response()->json([
           'status' => 'success',
           'message' => '✅ Account created successfully! You can now log in.'
       ], 200)->withCookie($cookie);



    } catch (\Exception $e) {
        return Response::make("Something went wrong. Please try again.");
    }
   }





   // Replace your login method with this improved version

public function login(Request $request)
{
    $username = $request->input('username');
    $password = $request->input('password');
    $remember = $request->has('rem');

    $ipAddress = $request->ip();
    $userAgent = $request->header('User-Agent');

    // 🛑 Check if IP is already blocked
    $blocked = DB::table('blocked_ips')
        ->where('ip_address', $ipAddress)
        ->where('blocked_until', '>', now())
        ->exists();

    if ($blocked) {
        return response()->json([
            'status' => 'error',
            'message' => 'Access denied: IP temporarily blocked. Try again later.'
        ], 403);
    }

    // ⏳ Count recent failed attempts
    $recentAttempts = DB::table('login_attempts')
        ->where('ip_address', $ipAddress)
        ->where('created_at', '>=', now()->subMinutes(15))
        ->count();

    if ($recentAttempts >= 5) {
        // 🚫 Block IP for 15 minutes
        DB::table('blocked_ips')->updateOrInsert(
            ['ip_address' => $ipAddress],
            ['blocked_until' => now()->addMinutes(15)]
        );
        
        return response()->json([
            'status' => 'error',
            'message' => 'Too many failed attempts. Try again after 15 minutes.'
        ], 429);
    }

    // 📋 Input validation
    $validator = Validator::make($request->all(), [
        'username' => 'required|string|min:3',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first()
        ], 400);
    }

    // Find user
    $user = DB::table('users_record')->where('username', $username)->first();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Username not found.'
        ], 400);
    }

    // 🚫 Check if user is temporarily disabled
    if ($user->disabled_until && now()->lt($user->disabled_until)) {
        $endDate = \Carbon\Carbon::parse($user->disabled_until);
        $startDate = $endDate->copy()->subDays($user->disabled_days ?? 0);

        return response()->json([
            'status' => 'error',
            'message' => "Your account has been disabled for {$user->disabled_days} " . 
                        \Illuminate\Support\Str::plural('day', $user->disabled_days) .
                        " from {$startDate->format('M j, Y')}. " .
                        "It will be enabled on {$endDate->format('Y-m-d H:i:s')} " .
                        "({$endDate->diffForHumans()})."
        ], 403);
    }

    // ✅ Auto-enable if time has passed
    if ($user->disabled_until && now()->gte($user->disabled_until)) {
        DB::table('users_record')->where('id', $user->id)->update([
            'disabled_until' => null,
            'disabled_days' => null
        ]);
    }

    // 🔐 Check password
    if (!property_exists($user, 'password') || !Hash::check($password, $user->password)) {
        DB::table('login_attempts')->insert([
            'username' => $username,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'status' => 'failed',
            'created_at' => now(),
        ]);
        
        return response()->json([
            'status' => 'error',
            'message' => 'Incorrect password.'
        ], 400);
    }

    // 🗝️ Generate device token if it's a new login
    $deviceId = hash('sha256', $userAgent . $ipAddress);
    $knownDevice = DB::table('known_devices')->where([
        ['user_id', '=', $user->id],
        ['device_hash', '=', $deviceId]
    ])->exists();

    if (!$knownDevice) {
        $resetToken = bin2hex(random_bytes(32));
        $tokenExpire = now()->addMinutes(30);

        DB::table('users_record')->where('id', $user->id)->update([
            'token' => $resetToken,
            'tokenExpire' => $tokenExpire
        ]);

        Mail::to($user->email)->send(new \App\Mail\NewDeviceLoginAlert(
            $ipAddress, $userAgent, $user, $resetToken
        ));

        DB::table('known_devices')->insert([
            'user_id' => $user->id,
            'device_hash' => $deviceId,
            'created_at' => now()
        ]);
    }

    // 🧠 Store session
    Session::put('specialcode', $user->specialcode);
    Session::put('id', $user->id);
    Session::put('username', $user->username);
    Session::put('role', $user->role);

    // 📊 Log successful login
    $loginId = DB::table('login_details')->insertGetId([
        'user_record_id' => $user->id,
        'specialcode' => $user->specialcode,
        'ip_address' => $ipAddress,
        'user_agent' => $userAgent,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    DB::table('login_attempts')->insert([
        'username' => $user->username,
        'ip_address' => $ipAddress,
        'user_agent' => $userAgent,
        'status' => 'success',
        'created_at' => now()
    ]);

    Session::put('login_details_id', $loginId);

    // 🍪 "Remember me" cookie
    if ($remember) {
        cookie()->queue('username', $user->username, 1440);
    } else {
        cookie()->queue(cookie()->forget('username'));
    }

    // ✅ Daily login reward
    $alreadyRewarded = DB::table('wallet_transactions')
        ->where('wallet_owner_id', $user->id)
        ->where('type', 'task_reward')
        ->where('description', 'LIKE', 'Reward for completing task: Daily login%')
        ->whereDate('created_at', now()->toDateString())
        ->exists();

    if (!$alreadyRewarded) {
        DB::table('wallet_transactions')->insert([
            'wallet_owner_id' => $user->id,
            'payer_id'        => $user->id,
            'amount'          => 20,
            'status'          => 'successful',
            'type'            => 'task_reward',
            'currency'        => 'NGN',
            'description'     => "Reward for completing task: Daily login check-in",
            'transaction_id'  => uniqid('txn_login_'),
            'tx_ref'          => \Illuminate\Support\Str::uuid(),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        Session::flash('reward', '🎉 You earned 20 NGN for daily login!');
    }

    $redirectRoute = $user->registration_type === 'referral'
        ? route('tasks.index')
        : route('update');

    // Set cookie to remember user has an account (expires in 5 years)
    $cookie = cookie('has_account', 'true', 60 * 24 * 365 * 5);

    return response()->json([
        'status' => 'success',
        'message' => 'Welcome back!',
        'redirect' => $redirectRoute
    ])->withCookie($cookie);
}

// logout user
public function logout()
{
    // Update logout time for the latest login session
    LoginDetail::where('user_record_id', Session::get('id'))
        ->latest()
        ->update(['logout_at' => now()]);

    // Clear all session data
    Session::flush();

    // Optionally clear cookies
    Cookie::queue(Cookie::forget('username'));

    // Redirect to login or home page
    return redirect('/account')->with('message', 'You have been logged out.');
}




public function forgot(Request $request)
{
    $request->validate([
        'femail' => 'required|email'
    ]);

    $femail = $request->input('femail');

    $user = DB::table('users_record')->where('email', $femail)->first();

    if (!$user) {
        return Response::make("<p style='color:red;'>Email not found!</p>", 400);
    }

    $token = bin2hex(random_bytes(8));
    $expiry = Carbon::now()->addMinutes(30);

    DB::table('users_record')
      ->where('email', $femail)
      ->update([
          'token' => $token,
          'tokenExpire' => $expiry
      ]);

   $resetLink = url('/reset-password') . "?email=$femail&token=$token";


    try {
        Mail::send([], [], function ($message) use ($femail, $resetLink) {
    $message->to($femail)
            ->subject('Reset Your Password')
            ->html(
                "<h3>Click the link to reset your password:</h3><br>
                 <a href='$resetLink'><strong>Reset Password Now</strong></a><br><br>
                 Regards,<br>SupperAge (OMOHA FRANCIS EKENE : the ceo)"
            )
            ->from('info@supperage.com', 'SupperAge');
});


        return Response::make("<p style='color:green;'>Reset link sent to your email.</p>", 200);
    } catch (\Exception $e) {
        return Response::make("<p style='color:red;'>Email could not be sent.</p>", 500);
    }
}






public function updatePassword(Request $request)
{
    // Validate incoming data
    $request->validate([
        'email' => 'required|email',
        'token' => 'required|string',
        // 'new_password' => 'required|min:6|confirmed',
        'new_password' => 'required|string|min:6|confirmed',
    ]);

    // Safely retrieve input values
    $email = $request->input('email');
    $token = $request->input('token');
    $newPassword = $request->input('new_password');

    // Find the user by email and token
    $user = DB::table('users_record')
        ->where('email', $email)
        ->where('token', $token)
        ->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Invalid token or email.']);
    }

    // Check if the token is expired
    if (!isset($user->tokenExpire) || Carbon::now()->greaterThan(Carbon::parse($user->tokenExpire))) {
        return back()->withErrors(['email' => 'Reset token expired. Please try again.']);
    }

    // Update the password and clear the token
    DB::table('users_record')
        ->where('email', $email)
        ->update([
            'password' => Hash::make($newPassword),
            'token' => null,
            'tokenExpire' => null,
        ]);

    return redirect('/reset-password-success')->with('status', '✅ Your password has been updated successfully. You’ll be redirected to login.');
    

}

}