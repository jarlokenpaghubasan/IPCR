<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset code via email.
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate a 6-digit verification code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any existing reset tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Store the reset code
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($code),
            'created_at' => Carbon::now(),
        ]);

        // Get user and send notification
        $user = User::where('email', $request->email)->first();
        $user->notify(new PasswordResetNotification($code));

        return redirect()->route('password.reset.form')
            ->with('email', $request->email)
            ->with('success', 'A verification code has been sent to your email.');
    }

    /**
     * Show the reset password form.
     */
    public function showResetPasswordForm(Request $request)
    {
        return view('auth.reset-password', ['email' => session('email')]);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get the reset token from database
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['code' => 'Invalid or expired reset code.']);
        }

        // Check if token has expired (15 minutes)
        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['code' => 'The reset code has expired. Please request a new one.']);
        }

        // Verify the code
        if (!Hash::check($request->code, $resetRecord->token)) {
            return back()->withErrors(['code' => 'The verification code is incorrect.']);
        }

        // Update the user's password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Your password has been successfully reset. Please login with your new password.');
    }
}
