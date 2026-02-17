<p>
    🔔 We noticed a new login on your account from a device or location we don't recognize.<br><br>
    If this was you, feel free to ignore this message. ✅<br><br>
    But if this wasn't you, please take action immediately to protect your account:<br>
    👉 <a href="{{ url('/reset-password?email=' . urlencode($user->email) . '&token=' . $token) }}" style="color:#007BFF; font-weight:bold;">Click here to reset your password</a><br><br>
    Your account's safety is our top priority. 🛡️
</p>

