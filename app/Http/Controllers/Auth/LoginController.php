<?php

namespace App\Http\Controllers\Auth;

use session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;



use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{


    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/loginForm';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        // $this->middleware('auth')->only('logout');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function loginMethod(Request $request)
    {
        $incomingFields = $request->validate([
            "email" => ['required', 'email'],
            "password" => "required",
        ]);

        if (auth()->attempt(['email' => $incomingFields['email'], 'password' => $incomingFields['password']])) {
            $user = Auth::user();

            $role = $user->getRoleNames()->first(); // Spatie method, returns string like 'admin'

            if (!$role) {
                return redirect()->route('loginForm')->with('error', 'No role assigned.');
            }

            $request->session()->regenerate();

            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('success', 'Logged in Successfully!');
                case 'Ict_director':
                    return redirect()->route('director.dashboard')->with('success', 'Logged in Successfully!');
                case 'technician':
                    return redirect()->route('technician.dashboard')->with('success', 'Logged in Successfully!');
                case 'Employee':
                case 'general_director':
                case 'division_manager':
                case 'department_manager':
                    return redirect()->route('employer.dashboard')->with('success', 'Logged in Successfully!');
                case 'Division Manager':
                    return redirect()->route('employer.dashboard')->with('success', 'Logged in Successfully!');
                default:
                    return redirect()->route('loginForm')->with('error', 'Unknown role.');
            }
        }

        return redirect()->route('loginForm')->with('error', 'Invalid email or password.');
    }



    protected function handleInvalidRole()
    {
        auth()->logout();
        return redirect()->route('loginForm')->with('error', 'Your account is not associated with any valid role.');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('loginForm')->with('success', "Successfully Logout!!!");
    }
}
