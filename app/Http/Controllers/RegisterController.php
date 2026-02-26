<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\UserRecord;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    // Show registration form
    public function showForm()
    {
        return view('auth.register');
    }

    // Handle registration (renamed to registertwo)
    public function registertwo(Request $request)
    {
        // Preserve referral ID in session if present
        if ($request->has('ref')) {
            Session::put('ref', $request->get('ref'));
        }

        // Validate input
        $request->validate([
            'name'      => 'required|string|min:3',
            'username'  => 'required|string|min:3|unique:users_record,username',
            'email'     => 'required|email|unique:users_record,email',
            'phone'     => 'required|string|max:15',
            'password'  => 'required|string|min:6|confirmed',
            'gender'    => 'required|string',
            'dob'       => 'required|date',
            'continent' => 'required|string',
            'country'   => 'required|string',
        ]);

        // Generate specialcode and defaults
        $specialcode  = generateSpecialcode();
        $created      = now()->format('Y-m-d');
        $status       = "active";
        $emailstatus  = "email not verified";
        $phonestatus  = "verified";
        $unsetacct    = "locked";

        // Determine referral status
        $refId = $request->get('ref') ?? Session::get('ref');
        $registrationType = $refId ? 'referral' : 'normal';

        // Create the new user
        $user = UserRecord::create([
            'specialcode'       => $specialcode,
            'name'              => $request->name,
            'username'          => $request->username,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => Hash::make($request->password),
            'gender'            => $request->gender,
            'dob'               => $request->dob,
            'continent'         => $request->continent,
            'country'           => $request->country,
            'created'           => $created,
            'status'            => $status,
            'email_status'      => $emailstatus,
            'phone_status'      => $phonestatus,
            'unsetacct'         => $unsetacct,
            'registration_type' => $registrationType,
            'invited_by'        => $refId ?: null,
        ]);

        // Handle referral reward
        if ($refId) {
            $referrer = UserRecord::find($refId);
            if ($referrer) {
                WalletTransaction::create([
                    'wallet_owner_id' => $referrer->id,
                    'payer_id'        => $referrer->id,
                    'amount'          => 100,
                    'status'          => 'successful',
                    'type'            => 'task_reward',
                    'currency'        => 'NGN',
                    'description'     => "Reward for completing task: Invite a friend",
                    'transaction_id'  => uniqid('txn_invite_'),
                    'tx_ref'          => Str::uuid(),
                ]);
            }
        }

        // Log the user in
        // Session::put('id', $user->id);
        // Session::put('username', $user->username);
        // Session::put('specialcode', $user->specialcode);
        // Session::put('role', $user->role ?? 'user');

        // Redirect to account page
        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'redirect' => url('/account')]);
        } else {
            return redirect('/account')->with('success', 'Welcome to your dashboard!');
        }
    }
}
