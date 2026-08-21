<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ProfilePasswordRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;
    public $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        // Authorize viewAny action
        $this->authorize('viewAny', User::class);

        $users = $this->service->fetchUsers(
            $request->input('search'),
            $request->input('role')
        );

        return view('user', compact('users'));
    }

    public function submit(UserRequest $request)
    {
        // Authorize create action
        $this->authorize('create', User::class);

        $this->service->submitUser($request->validated());
        
        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    public function update(UserRequest $request, User $user)
    {
        // Authorize update action against the specific user model
        $this->authorize('update', $user);

        $this->service->updateUser($user, $request->validated());

        return redirect()
            ->route('user.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Authorize delete action against the specific user model
        $this->authorize('delete', $user);

        $this->service->deleteUser($user);

        return redirect()
            ->route('user.index')
            ->with('success', 'User deleted successfully.');
    }

    public function profile()
    {
        // Profile viewing is typically open to any authenticated user
        $user = $this->service->getProfileData();
        return view('profile', compact('user'));
    }

    public function profile_update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        $this->service->updateProfile($user, $request->validated());

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
    }

    public function profile_password(ProfilePasswordRequest $request)
    {
        $user = auth()->user();
        $this->service->updatePassword($user, $request->validated());

        return redirect()->route('user.profile')->with('success', 'Password updated successfully.');
    }
}