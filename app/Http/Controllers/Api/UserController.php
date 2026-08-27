<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilePasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    use AuthorizesRequests, ApiResponse;

    public function __construct(protected UserService $service) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $users = $this->service->fetchUsers(
            $request->input('search'),
            $request->input('role')
        );

        return $this->successResponse(
            UserResource::collection($users)->response()->getData(true),
            'Users fetched successfully.'
        );
    }

    public function store(UserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $user = $this->service->submitUser($request->validated());

        return $this->successResponse(
            new UserResource($user),
            'User created successfully.',
            201
        );
    }

    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return $this->successResponse(
            new UserResource($user),
            'User details fetched successfully.'
        );
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $updatedUser = $this->service->updateUser($user, $request->validated());

        return $this->successResponse(
            new UserResource($updatedUser),
            'User updated successfully.'
        );
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $this->service->deleteUser($user);

        return $this->successResponse(null, 'User deleted successfully.');
    }
    public function profile(): JsonResponse
    {
        $user = $this->service->getProfileData();

        return $this->successResponse(
            new UserResource($user),
            'Profile data fetched successfully.'
        );
    }

    public function updateProfile(ProfileUpdateRequest $request): JsonResponse
    {
        $user = auth()->user();
        $updatedUser = $this->service->updateProfile($user, $request->validated());

        return $this->successResponse(
            new UserResource($updatedUser),
            'Profile updated successfully.'
        );
    }

    public function updatePassword(ProfilePasswordRequest $request): JsonResponse
    {
        $user = auth()->user();
        $this->service->updatePassword($user, $request->validated());

        return $this->successResponse(null, 'Password updated successfully.');
    }
}
