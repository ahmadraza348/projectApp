<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $service;
    public function __construct(AuthService $authService)
    {
        $this->service = $authService;
    }

    public function login()
    {
        return view('login');
    }

    public function authenticate(LoginRequest $request)
    {
        $result = $this->service->authenticate(
            $request->only('email', 'password'),
            $request->boolean('remember')
        );
        if ($result['success']) {
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => $result['message'],
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        $this->service->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
