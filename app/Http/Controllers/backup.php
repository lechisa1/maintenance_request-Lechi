<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show the login form view.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt(['email' => $incomingFields['email'], 'password' => $incomingFields['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();
            $role = optional($user->roles()->first())->name;

            if (!$role) {
                Auth::logout();
                return redirect()->route('loginForm')->withErrors([
                    'email' => 'Your account does not have an assigned role.',
                ]);
            }

            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->withSuccess(['notification' => 'Logged in Successfully!']);
                case 'director':
                    return redirect()->route('director.dashboard')->withSuccess(['notification' => 'Logged in Successfully!']);
                case 'technician':
                    return redirect()->route('technician.dashboard')->withSuccess(['notification' => 'Logged in Successfully!']);
                case 'employer':
                    return redirect()->route('employer.dashboard')->withSuccess(['notification' => 'Logged in Successfully!']);
                default:
                    Auth::logout();
                    return redirect()->route('loginForm')->withErrors([
                        'email' => 'Unauthorized role.',
                    ]);
            }
        }

        return redirect()->route('loginForm')->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('loginForm');
    }
}