<?php

namespace App\Http\Controllers;

use App\Notifications\EmailVerificationNotification;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmailVerificationController extends Controller
{
    /**
     * Send verification code to user's email
     */
    public function sendVerificationCode(Request $request)
    {
        $user = auth()->user();

        // Check if email is already verified
        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Your email is already verified.'
            ], 400);
        }

        // Rate limiting: Check if user has requested verification code recently (within 1 minute)
        $recentCode = DB::table('email_verifications')
            ->where('user_id', $user->id)
            ->where('created_at', '>', Carbon::now()->subMinute())
            ->first();

        if ($recentCode) {
            return response()->json([
                'success' => false,
                'message' => 'Please wait before requesting another verification code.'
            ], 429);
        }

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store code in database
        DB::table('email_verifications')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'code' => $code,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Send notification
        try {
            $user->notify(new EmailVerificationNotification($code));

            ActivityLogService::log('email_verification_sent', 'Requested email verification code', $user);

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your email address.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email. Please try again later.'
            ], 500);
        }
    }

    /**
     * Verify email with code
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = auth()->user();

        // Check if email is already verified
        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Your email is already verified.'
            ], 400);
        }

        // Get verification code
        $verification = DB::table('email_verifications')
            ->where('user_id', $user->id)
            ->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'No verification code found. Please request a new code.'
            ], 404);
        }

        // Check if code has expired (30 minutes)
        if (Carbon::parse($verification->created_at)->addMinutes(30)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired. Please request a new code.'
            ], 400);
        }

        // Verify code
        if ($verification->code !== $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code. Please check and try again.'
            ], 400);
        }

        // Mark email as verified
        $user->email_verified_at = Carbon::now();
        $user->save();

        // Delete verification code
        DB::table('email_verifications')
            ->where('user_id', $user->id)
            ->delete();

        ActivityLogService::log('email_verified', 'Verified email address', $user);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!'
        ]);
    }

    /**
     * Show verification form (optional - for standalone verification page)
     */
    public function showVerificationForm()
    {
        if (auth()->user()->email_verified_at) {
            return redirect()->route(auth()->user()->getPrimaryRole() . '.dashboard')
                ->with('info', 'Your email is already verified.');
        }

        return view('auth.verify-email');
    }
}
