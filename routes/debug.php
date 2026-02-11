<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
Route::get('/debug-auth', function () {
    return view('debug-auth');
});
Route::get('/debug-api', function () {
    return [
        'session_id' => session()->getId(),
        'session_started' => session()->isStarted(),
        'session_has_token' => session()->has('_token'),
        'csrf_token' => csrf_token(),
        'auth_check' => Auth::check(),
        'auth_user' => Auth::user() ? [
            'id' => Auth::user()->id,
            'email' => Auth::user()->email,
            'name' => Auth::user()->name
        ] : null,
        'session_data' => session()->all(),
        'cookies' => request()->cookie()
    ];
});
Route::post('/debug-login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);
    $result = [
        'attempt_start' => now(),
        'session_id_before' => session()->getId(),
        'auth_check_before' => Auth::check(),
        'credentials' => [
            'email' => $credentials['email'],
            'password_length' => strlen($credentials['password'])
        ]
    ];
    if (Auth::attempt($credentials)) {
        session()->regenerate();
        $result['attempt_success'] = true;
        $result['session_id_after'] = session()->getId();
        $result['auth_check_after'] = Auth::check();
        $result['user'] = [
            'id' => Auth::user()->id,
            'email' => Auth::user()->email,
            'name' => Auth::user()->name
        ];
        return response()->json($result);
    } else {
        $result['attempt_success'] = false;
        $result['error'] = 'Authentication failed';
        return response()->json($result, 401);
    }
});
Route::get('/csrf-test', function () {
    return view('csrf-test');
});
Route::post('/test-csrf-post', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Simple POST successful!',
        'data' => $request->all(),
        'session_id' => session()->getId(),
        'csrf_token_valid' => $request->session()->token() === $request->get('_token')
    ]);
});
Route::post('/test-csrf-ajax', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'AJAX POST successful!',
        'data' => $request->all(),
        'session_id' => session()->getId(),
        'csrf_token_valid' => $request->session()->token() === $request->get('_token')
    ]);
});