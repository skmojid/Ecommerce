<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }
    public function create()
    {
        return view('admin.users.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,user',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,manager,user',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user->update(['password' => bcrypt($request->password)]);
        }
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
    public function profile(User $user)
    {
        return view('admin.users.profile', compact('user'));
    }
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,manager,user',
        ]);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'User role updated successfully.');
    }
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'users' => ['required', 'array'],
            'action' => ['required', 'in:activate,deactivate,delete,admin,manager,user'],
        ]);
        $action = $request->action;
        $userIds = $request->users;
        switch ($action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['email_verified_at' => now()]);
                $message = 'Users activated successfully.';
                break;
            case 'deactivate':
                User::whereIn('id', $userIds)->update(['email_verified_at' => null]);
                $message = 'Users deactivated successfully.';
                break;
            case 'admin':
                User::whereIn('id', $userIds)->update(['role' => 'admin']);
                $message = 'Users promoted to admin successfully.';
                break;
            case 'manager':
                User::whereIn('id', $userIds)->update(['role' => 'manager']);
                $message = 'Users promoted to manager successfully.';
                break;
            case 'user':
                User::whereIn('id', $userIds)->update(['role' => 'user']);
                $message = 'Users changed to user role successfully.';
                break;
            case 'delete':
                $cannotDelete = User::whereIn('id', $userIds)
                    ->where('role', 'admin')
                    ->exists();
                if ($cannotDelete) {
                    return back()->with('error', 'Cannot delete admin users.');
                }
                User::whereIn('id', $userIds)->delete();
                $message = 'Users deleted successfully.';
                break;
            default:
                return back()->with('error', 'Invalid action selected.');
        }
        return back()->with('success', $message);
    }
    public function toggleStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => ['required', 'in:active,inactive'],
        ]);
        $user->update(['email_verified_at' => $request->status === 'active' ? now() : null]);
        $message = $request->status === 'active' ? 'User activated successfully.' : 'User deactivated successfully.';
        return back()->with('success', $message);
    }
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);
        $user->update([
            'password' => bcrypt($request->password),
        ]);
        return back()->with('success', 'Password reset successfully.');
    }
}