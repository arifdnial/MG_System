<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminInvitation;
use App\Models\User;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_subjects' => Subject::count(),
            'total_exams' => Exam::count(),
        ];

        $recentUsers = User::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }

    // User Management
    public function usersIndex(string $role)
    {
        if (!in_array($role, ['admin', 'teacher', 'student'])) {
            abort(404);
        }

        $users = User::where('role', $role)->latest()->paginate(20);
        return view('admin.users.index', compact('users', 'role'));
    }

    public function usersCreate(string $role)
    {
        if (!in_array($role, ['admin', 'teacher', 'student'])) {
            abort(404);
        }
        return view('admin.users.create', compact('role'));
    }

    public function usersStore(Request $request, string $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ic' => 'required|string|unique:users,ic',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'form_class' => 'nullable|string|max:50',
        ]);

        User::create([
            'name' => $request->name,
            'ic' => $request->ic,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $role,
            'form_class' => $role === 'student' ? $request->form_class : null,
            'password' => $request->password,
        ]);

        return redirect()->route('admin.users.index', $role)
            ->with('success', ucfirst($role) . ' created successfully.');
    }

    public function usersEdit(string $role, User $user)
    {
        return view('admin.users.edit', compact('user', 'role'));
    }

    public function usersUpdate(Request $request, string $role, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ic' => 'required|string|unique:users,ic,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'form_class' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'ic', 'email', 'phone', 'form_class']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('admin.users.index', $role)
            ->with('success', ucfirst($role) . ' updated successfully.');
    }

    public function usersDestroy(string $role, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index', $role)
            ->with('success', ucfirst($role) . ' deleted successfully.');
    }

    // Admin Invitation
    public function showInvite()
    {
        $invitations = AdminInvitation::latest()->take(10)->get();
        return view('admin.invite', compact('invitations'));
    }

    public function sendInvite(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'ic' => 'required|string|unique:users,ic',
            'name' => 'required|string|max:255',
        ]);

        $token = Str::random(64);

        AdminInvitation::create([
            'email' => $request->email,
            'ic' => $request->ic,
            'token' => $token,
        ]);

        $inviteUrl = route('admin.accept-invite', ['token' => $token]);

        // In production, send email. For dev, show in flash message.
        return back()->with('success', 'Invitation sent successfully!')
            ->with('invite_url', $inviteUrl)
            ->with('invite_name', $request->name);
    }

    public function showAcceptInvite(string $token)
    {
        $invitation = AdminInvitation::where('token', $token)->where('used', false)->firstOrFail();
        return view('admin.accept-invite', compact('invitation', 'token'));
    }

    public function acceptInvite(Request $request, string $token)
    {
        $invitation = AdminInvitation::where('token', $token)->where('used', false)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'ic' => $invitation->ic,
            'email' => $invitation->email,
            'role' => 'admin',
            'password' => $request->password,
        ]);

        $invitation->update(['used' => true]);

        return redirect()->route('login')->with('success', 'Admin account created! You can now login.');
    }
}
