<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }
        if (! Auth::user()->isCustomer()) {
            abort(403, 'Unauthorized access. Customer privileges required.');
        }
        return $next($request);
    }
}