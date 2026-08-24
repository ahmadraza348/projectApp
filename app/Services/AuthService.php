<?php

namespace App\Services;

class AuthService
{
    public function authenticate(array $credentials, bool $remember = false): array
    {
        // Build the attempt array explicitly from known keys so a stray/extra
        // value (e.g. the remember flag) accidentally merged into $credentials
        // can never leak in as a bogus '0'/'1' column.
        $attempt = [
            'email'    => $credentials['email'] ?? null,
            'password' => $credentials['password'] ?? null,
        ];

        if (auth()->attempt($attempt, $remember)) {
            return [
                'success' => true,
                'message' => 'Login successful.',
                'user' => auth()->user(),
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid credentials. Please try again.',
        ];
    }

    public function logout(): void
    {
        auth()->logout();
    }
}
