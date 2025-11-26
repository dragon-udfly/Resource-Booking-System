<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nic_number' => ['required'],
            'passcode' => ['required'],
        ]);

        $user = User::where('nic_number', $credentials['nic_number'])->first();

        if ($user && $user->passcode == $credentials['passcode']) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->role == 'admin') {
                return redirect()->intended('admin');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'nic_number' => 'The provided credentials do not match our records.',
        ])->onlyInput('nic_number');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function index()
    {
        $users = User::where('role', 'user')->get();
        return view('officers', ['users' => $users]);
    }
}

