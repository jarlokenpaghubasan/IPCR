<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the login selection page.
     */
    public function showLoginSelection(): View
    {
        return view('auth.login-selection');
    }

    /**
     * Show the login form for the given role.
     */
    public function showLoginForm($role): View
    {
        // Validate the role
        $validRoles = ['faculty', 'dean', 'director', 'admin'];
        if (!in_array($role, $validRoles)) {
            return redirect()->route('login.selection');
        }

        return view('auth.login', ['role' => $role]);
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $role = $request->input('role');

        // Validate the role
        $validRoles = ['faculty', 'dean', 'director', 'admin'];
        if (!in_array($role, $validRoles)) {
            return redirect()->route('login.selection')->withErrors(['role' => 'Invalid role']);
        }

        // Validate credentials
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Attempt to authenticate user with the given role
        $user = \App\Models\User::where('username', $credentials['username'])
            ->where('role', $role)
            ->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            // Check if user is active
            if (!$user->isActive()) {
                return back()->withErrors(['username' => 'Your account is inactive']);
            }

            Auth::login($user, $request->boolean('remember'));

            return $this->redirectBasedOnRole($user->role);
        }

        return back()->withErrors([
            'username' => 'Invalid username or password for this role',
        ]);
    }

    /**
     * Redirect user based on their role.
     */
    private function redirectBasedOnRole($role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'director' => redirect()->route('director.dashboard'),
            'dean' => redirect()->route('dean.dashboard'),
            'faculty' => redirect()->route('faculty.dashboard'),
            default => redirect()->route('login.selection'),
        };
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.selection');
    }
}