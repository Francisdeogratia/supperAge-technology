<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\UserRecord;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = UserRecord::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($user->status === 'disabled') {
            return response()->json(['message' => 'Account is disabled'], 403);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $this->formatUser($user),
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users_record,username',
            'email'    => 'required|email|unique:users_record,email',
            'password' => 'required|string|min:6|confirmed',
            'gender'   => 'nullable|string',
            'dob'      => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = UserRecord::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'gender'       => $request->gender    ?? '',
            'dob'          => $request->dob        ?? '2000-01-01',
            'phone'        => $request->phone      ?? '',
            'continent'    => '',
            'country'      => $request->country    ?? '',
            'phone_status' => 'unverified',
            'unsetacct'    => '0',
            'specialcode'  => Str::random(19),
            'status'       => 'active',
            'email_status' => 'unverified',
            'created'      => now()->format('Y-m-d'),
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $this->formatUser($user),
        ], 201);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Always return success — never reveal whether an email exists
        $user = UserRecord::where('email', $request->email)->first();

        if ($user) {
            $token  = bin2hex(random_bytes(32));
            $expiry = now()->addMinutes(30);

            $user->token       = $token;
            $user->tokenExpire = $expiry;
            $user->save();

            $resetLink = 'https://www.supperage.com/reset-password'
                . '?email=' . urlencode($request->email)
                . '&token=' . $token;

            try {
                Mail::send([], [], function ($message) use ($request, $resetLink) {
                    $message->to($request->email)
                        ->subject('Reset Your SupperAge Password')
                        ->html(
                            "<p>You requested a password reset for your SupperAge account.</p>
                             <p><a href='{$resetLink}'><strong>Click here to reset your password</strong></a></p>
                             <p>This link expires in 30 minutes. If you did not request this, you can safely ignore this email.</p>
                             <br>Regards,<br>SupperAge"
                        )
                        ->from('info@supperage.com', 'SupperAge');
                });
            } catch (\Exception $e) {
                // Do not reveal mail errors to the client
            }
        }

        return response()->json(['message' => 'If an account exists for that email, a reset link has been sent.']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $this->formatUser($request->user())]);
    }

    public function savePushToken(Request $request)
    {
        $token = $request->input('token');
        if ($token) {
            $request->user()->update(['expo_push_token' => $token]);
        }
        return response()->json(['message' => 'Push token saved']);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name'      => 'nullable|string|max:255',
            'username'  => 'nullable|string|max:255|unique:users_record,username,' . $user->id,
            'email'     => 'nullable|email|unique:users_record,email,' . $user->id,
            'bio'       => 'nullable|string|max:500',
            'continent' => 'nullable|string',
            'country'   => 'nullable|string',
            'state'     => 'nullable|string|max:255',
            'city'      => 'nullable|string|max:255',
            'dob'       => 'nullable|date',
            'gender'    => 'nullable|string',
            'phone'     => 'nullable|string',
            'profileimg'=> 'nullable',
            'bgimg'     => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Fill standard VARCHAR fields — empty string is safe for these
        $user->fill($request->only(['name', 'username', 'email', 'bio', 'continent', 'country', 'gender', 'phone']));

        // Only update state/city if non-empty (guards against missing columns)
        if ($request->filled('state')) $user->state = $request->state;
        if ($request->filled('city'))  $user->city  = $request->city;

        // Only update dob if non-empty (DATE column rejects empty string in strict MySQL)
        if ($request->filled('dob')) $user->dob = $request->dob;

        // Handle profile image upload
        if ($request->hasFile('profileimg')) {
            $path = $request->file('profileimg')->store('uploads/profiles', 'public');
            $user->profileimg = '/storage/' . $path;
        } elseif ($request->filled('profileimg')) {
            $user->profileimg = $request->profileimg;
        }

        // Handle cover image upload
        if ($request->hasFile('bgimg')) {
            $path = $request->file('bgimg')->store('uploads/covers', 'public');
            $user->bgimg = '/storage/' . $path;
        } elseif ($request->filled('bgimg')) {
            $user->bgimg = $request->bgimg;
        }

        $user->save();

        return response()->json(['user' => $this->formatUser($user)]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }

    private function formatUser(UserRecord $user): array
    {
        // Auto-expire badge if the expiry date has passed
        if (
            $user->badge_expires_at
            && now()->gte(Carbon::parse($user->badge_expires_at))
            && $user->badge_status
            && !in_array($user->badge_status, ['expired', 'images/pending2.png'])
        ) {
            $user->badge_status = 'expired';
            $user->save();
        }

        return [
            'id'               => (int) $user->id,
            'name'             => $user->name,
            'username'         => $user->username,
            'email'            => $user->email,
            'bio'              => $user->bio,
            'profileimg'       => $user->profileimg ? (filter_var($user->profileimg, FILTER_VALIDATE_URL) ? $user->profileimg : url($user->profileimg)) : null,
            'bgimg'            => $user->bgimg ? (filter_var($user->bgimg, FILTER_VALIDATE_URL) ? $user->bgimg : url($user->bgimg)) : null,
            'gender'           => $user->gender,
            'dob'              => $user->dob,
            'country'          => $user->country,
            'state'            => $user->state,
            'city'             => $user->city,
            'phone'            => $user->phone,
            'role'             => $user->role,
            'status'           => $user->status,
            'badge_status'     => $user->badge_status,
            'badge_expires_at' => $user->badge_expires_at,
            'specialcode'      => $user->specialcode,
            'number_followers' => $user->number_followers ?? 0,
            'created_at'       => $user->created_at,
        ];
    }
}
