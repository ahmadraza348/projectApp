<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserService
{
    public function submitUser(array $data): User
    {      
       return DB::transaction(function () use ($data) {
            $data['password'] = Hash::make(
                $data['password']
            );
            return User::create($data);
        });
    }

    public function getProfileData(){
        $user = auth()->user();
        return $user;     
    }
}
