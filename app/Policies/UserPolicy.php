<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, User $model): bool
    {
        // Only admins can update users
        if ($user->role !== 'admin') {
            return false;
        }

        // Admin accounts cannot be edited
        if ($model->role === 'admin') {
            return false;
        }

        // Prevent modifying yourself
        if ($user->is($model)) {
            return false;
        }

        return true;
    }

    public function delete(User $user, User $model): bool
    {
        // Only admins can delete users
        if ($user->role !== 'admin') {
            return false;
        }

        // Admin accounts cannot be deleted
        if ($model->role === 'admin') {
            return false;
        }

        // Prevent deleting yourself
        if ($user->is($model)) {
            return false;
        }

        return true;
    }
}