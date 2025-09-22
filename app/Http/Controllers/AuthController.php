<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AuthController extends Controller
{
    /**
     * Show the login/register view with QR code to register as intern
     */
    public function showLoginRegister()
    {
        return view('login-and-register');
    }

    /**
     * Register a new admin user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    /**
     * Login logic for admin
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    /**
     * Logout the user
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
