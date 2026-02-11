<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()
                ->intended(route('shop.index'))
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
    }
    private function redirectBasedOnRole($user, $request)
    {
        $request->session()->forget('url.intended');
        switch ($user->role) {
            case 'admin':
                return redirect()->route('shop.index')->with('success', 'Welcome back, Admin!');
            case 'manager':
                return redirect()->route('shop.index')->with('success', 'Welcome back, Manager!');
            case 'customer':
            case 'user':
                return redirect()->route('shop.index')->with('success', 'Welcome back!');
            default:
                return redirect()->route('shop.index')->with('success', 'Welcome back!');
        }
    }
    public function showRegister()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Default role for registration
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        $request->session()->regenerate();
        Auth::login($user);
        return redirect()->route('shop.index')->with('success', 'Registration successful!');
    }
public function showAdminLogin()
    {
        return view('admin-login');
    }
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->role === 'admin') {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Welcome to Admin Panel, ' . Auth::user()->name . '!');
            }
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Access denied. Admin privileges required.');
        }
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid credentials');
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
        $oldSessionId = session()->getId();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Log::info('User logged out', [
            'user_id' => $user?->id,
            'email' => $user?->email,
            'old_session' => $oldSessionId,
            'new_session' => session()->getId()
        ]);
        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }
    public function showMinimalWorkingLogin()
    {
        return view('minimal-working-login');
    }
    public function minimalWorkingLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()
                ->route('minimal.working.login')
                ->with('success', 'Successfully logged in as ' . Auth::user()->email . '!');
        }
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid credentials');
    }
}