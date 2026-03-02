<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            'gender'       => $request->gender,
            'dob'          => $request->dob,
            'specialcode'  => Str::random(20),
            'status'       => 'active',
            'email_status' => 'unverified',
            'created'      => now(),
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $this->formatUser($user),
        ], 201);
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

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name'       => 'nullable|string|max:255',
            'bio'        => 'nullable|string|max:500',
            'profileimg' => 'nullable|string',
            'bgimg'      => 'nullable|string',
            'country'    => 'nullable|string',
            'phone'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->fill($request->only(['name', 'bio', 'profileimg', 'bgimg', 'country', 'phone']));
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
        return [
            'id'             => $user->id,
            'name'           => $user->name,
            'username'       => $user->username,
            'email'          => $user->email,
            'bio'            => $user->bio,
            'profileimg'     => $user->profileimg ? (filter_var($user->profileimg, FILTER_VALIDATE_URL) ? $user->profileimg : url($user->profileimg)) : null,
            'bgimg'          => $user->bgimg ? (filter_var($user->bgimg, FILTER_VALIDATE_URL) ? $user->bgimg : url($user->bgimg)) : null,
            'gender'         => $user->gender,
            'dob'            => $user->dob,
            'country'        => $user->country,
            'phone'          => $user->phone,
            'role'           => $user->role,
            'status'         => $user->status,
            'badge_status'   => $user->badge_status,
            'specialcode'    => $user->specialcode,
            'number_followers' => $user->number_followers ?? 0,
            'created_at'     => $user->created_at,
        ];
    }
}
