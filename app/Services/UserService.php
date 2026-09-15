<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Events\UserSubmitEmail;

class UserService
{

    public function submitUser(array $data): User
    {
        $plainPassword = $data['password'];

        $user = DB::transaction(function () use ($data) {
            $data['password'] = Hash::make($data['password']);
            return User::create($data);
        });
        UserSubmitEmail::dispatch(
            $user,
            $plainPassword
        );

        return $user;
    }



    public function updateUser(User $user, array $data): User
    {
        if (filled($data['password'] ?? null)) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return $user->refresh();
    }

    public function fetchUsers(?string $search = null, ?string $role = null): LengthAwarePaginator
    {
        return User::query()
            ->when(filled($search), function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(filled($role), function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function deleteUser(User $user): User
    {
        $user->delete();

        return $user;
    }


    public function getProfileData(): User
    {
        return auth()->user();
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);
        return $user->refresh();
    }

    public function updatePassword(User $user, array $data): User
    {
        $user->password = Hash::make($data['password']);
        $user->save();
        return $user->refresh();
    }
}
