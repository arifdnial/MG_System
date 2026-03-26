<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->dashboardRoute());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'ic' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:admin,teacher,student',
        ]);

        $user = User::where('ic', $request->ic)->where('role', $request->role)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['ic' => 'Invalid IC number, password, or role selection.'])->withInput();
        }

        if (!$user->is_active) {
            return back()->withErrors(['ic' => 'Your account has been deactivated. Please contact an administrator.'])->withInput();
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route($user->dashboardRoute());
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'ic' => 'required|string|unique:users,ic',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,teacher',
            'form_class' => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'ic' => $request->ic,
            'phone' => $request->phone,
            'role' => $request->role,
            'form_class' => $request->role === 'student' ? $request->form_class : null,
            'password' => $request->password,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($user->dashboardRoute())->with('success', 'Registration successful! Welcome aboard.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['ic' => 'required|string']);

        $user = User::where('ic', $request->ic)->first();
        if (!$user) {
            // Don't reveal whether user exists
            return back()->with('success', 'If an account with that IC exists, a password reset email has been sent.');
        }

        // Generate token and store it
        $token = Str::random(64);
        \DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        ['token' => Hash::make($token), 'created_at' => now()]
        );

        // In production, send email. For dev, show link in flash message.
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

        return back()->with('success', 'Password reset email has been sent.')
            ->with('debug_reset_url', $resetUrl);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = \DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $user->update(['password' => $request->password]);
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password successfully updated. Please login with your new password.');
    }
}
