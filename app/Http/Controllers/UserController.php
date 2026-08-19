<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $users = $this->service->fetchUsers(
            $request->input('search'),
            $request->input('role')
        );

        return view('user', compact('users'));
    }

    public function submit(UserRequest $request)
    {
         $this->service->submitUser($request->validated());
         return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

public function update(UserRequest $request, User $user)
{
    $this->service->updateUser($user, $request->validated());

    return redirect()
        ->route('user.index')
        ->with('success', 'User updated successfully.');
}

public function destroy(User $user)
{
    $this->service->deleteUser($user);

    return redirect()
        ->route('user.index')
        ->with('success', 'User deleted successfully.');
}

    public function profile()
    {
       $user = $this->service->getProfileData();
        return view('profile', compact('user'));
    }
}
