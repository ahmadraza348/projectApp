<?php

namespace App\Services;

class AuthService
{
    public function authenticate(array $credentials): array
    {
        if (Auth()->attempt($credentials)) {
            return [
                'success' => true,
                'message' => 'Login successful.',
                'user' => Auth()->user(),
                ];
        }
        else {
            return [
                'success' => false,
                'message' => 'Invalid credentials. Please try again.',
            ];
        }

    }

    public function logout() :void
    {
        Auth()->logout();
    }
}
