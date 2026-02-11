<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }
        $user = Auth::user();
        $userRole = $user->role ?? 'user';
        if (! in_array($userRole, $roles)) {
            $requiredRoles = implode(', ', $roles);
            abort(403, "Unauthorized access. Required role(s): {$requiredRoles}. Current role: {$userRole}");
        }
        return $next($request);
    }
}