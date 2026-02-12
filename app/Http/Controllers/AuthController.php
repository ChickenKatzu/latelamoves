<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($request->username === 'aldmic' && $request->password === '123abc123') {
            Session::put('authenticated', true);
            Session::put('username', $request->username);
            
            return redirect()->route('movies.index');
        }

        return back()->withErrors([
            'login' => __('messages.invalid_credentials')
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Session::forget('authenticated');
        Session::forget('username');
        
        return redirect()->route('login');
    }
}