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
        toastr()->success('User created successfully!');
        
        return redirect()->route('user.index');
    }

    public function update(UserRequest $request, User $user)
    {
        // Authorize update action against the specific user model
        $this->authorize('update', $user);

        $this->service->updateUser($user, $request->validated());

        toastr()->success('User updated successfully!');
        return redirect()->route('user.index');
    }

    public function destroy(User $user)
    {
        // Authorize delete action against the specific user model
        $this->authorize('delete', $user);

        $this->service->deleteUser($user);

        toastr()->success('User deleted successfully!');
        return redirect()->route('user.index');
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

        toastr()->success('Profile updated successfully!');
        return redirect()->route('user.profile');
    }

    public function profile_password(ProfilePasswordRequest $request)
    {
        $user = auth()->user();
        $this->service->updatePassword($user, $request->validated());

        toastr()->success('Password updated successfully!');
        return redirect()->route('user.profile');
    }
}