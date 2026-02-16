<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\ActivityLogService;

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

        return redirect()->route('password.verify.form')
            ->with('email', $request->email)
            ->with('success', 'A verification code has been sent to your email.');
    }

    /**
     * Show the verify code form.
     */
    public function showVerifyCodeForm(Request $request)
    {
        $email = session('email') ?? $request->get('email');
        
        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please enter your email address first.']);
        }

        return view('auth.verify-code', ['email' => $email]);
    }

    /**
     * Verify the reset code.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
        ]);

        // Get the reset token from database
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()
                ->with('email', $request->email)
                ->withErrors(['code' => 'Invalid or expired reset code. Please request a new one.']);
        }

        // Check if token has expired (15 minutes)
        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()
                ->with('email', $request->email)
                ->withErrors(['code' => 'The reset code has expired. Please request a new one.']);
        }

        // Verify the code
        if (!Hash::check($request->code, $resetRecord->token)) {
            return back()
                ->with('email', $request->email)
                ->withErrors(['code' => 'The verification code is incorrect.']);
        }

        // Generate a one-time reset token for the password reset form
        $resetToken = Str::random(64);
        
        // Update the record with the reset token (code verified)
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->update(['token' => Hash::make($resetToken), 'created_at' => Carbon::now()]);

        return redirect()->route('password.reset.form')
            ->with('verified_email', $request->email)
            ->with('reset_token', $resetToken)
            ->with('success', 'Code verified! Please set your new password.');
    }

    /**
     * Show the reset password form.
     */
    public function showResetPasswordForm(Request $request)
    {
        $email = session('verified_email');
        $resetToken = session('reset_token');
        
        if (!$email || !$resetToken) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please complete the verification process first.']);
        }

        return view('auth.reset-password', [
            'email' => $email,
            'reset_token' => $resetToken
        ]);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get the reset token from database
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Invalid reset session. Please start over.']);
        }

        // Check if token has expired (15 minutes from verification)
        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Your session has expired. Please start over.']);
        }

        // Verify the reset token
        if (!Hash::check($request->reset_token, $resetRecord->token)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Invalid reset session. Please start over.']);
        }

        // Update the user's password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Log the password reset
        ActivityLogService::log('password_reset', 'Reset password via email verification', $user);

        // Delete the reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Your password has been successfully reset. Please login with your new password.');
    }
}
