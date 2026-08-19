<?php

namespace App\Services;

class AuthService
{
    public function authenticate($request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            session()->flash('error', 'Invalid credentials. Please try again.'),
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
