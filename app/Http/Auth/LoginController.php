<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    protected $redirectTo = '/admin';

    protected $middleware = ['guest'];

    protected $except = ['logout'];

    public function __construct()
    {
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        try {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
     
                return redirect()->intended($this->redirectTo);
            }
        } catch (\RuntimeException $e) {
            if (str_contains($e->getMessage(), 'does not use the Bcrypt algorithm')) {
                Log::warning('Login attempt with non-Bcrypt password detected', [
                    'email' => $request->email,
                    'ip' => $request->ip()
                ]);
                
                return back()->withErrors([
                    'email' => 'Your account requires a password update. Please contact an administrator.',
                ])->onlyInput('email');
            }
            
            throw $e;
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
 
        $request->session()->regenerateToken();
 
        return redirect('/');
    }
}
