<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\UserRecord;

class ProfileController extends Controller
{
    public function edit()
    {
        $username = Session::get('username');
        $user = UserRecord::where('username', $username)->first();

        if (!$user) {
            return redirect('/login');
        }

        $unseenMessageCount = 0;
        $unseenFileCount = 0;
        $totalNotifications = 0;

        return view('profile.edit', compact(
            'user',
            'unseenMessageCount',
            'unseenFileCount',
            'totalNotifications'
        ));
    }

    public function update(Request $request)
    {
        $username = Session::get('username');
        $user = UserRecord::where('username', $username)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'continent' => 'required|string',
            'country' => 'required|string',
            'state' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'bio' => 'nullable|string',
            'profileimg_url' => 'nullable|url',
            'bgimg_url' => 'nullable|url',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->continent = $request->continent;
        $user->country = $request->country;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->bio = $request->bio;
        $user->state = $request->state;
        $user->city = $request->city;

        if ($request->filled('profileimg_url')) {
            $user->profileimg = $request->profileimg_url;
        }

        if ($request->filled('bgimg_url')) {
            $user->bgimg = $request->bgimg_url;
        }

        $user->save();
// return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');

        return response()->json(['message' => 'Profile updated successfully!']);
    }


    

    
}
