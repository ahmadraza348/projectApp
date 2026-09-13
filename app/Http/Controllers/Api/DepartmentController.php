<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Services\DepartmentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    use ApiResponse;

    public function __construct(protected DepartmentService $service) {}

    public function index(): JsonResponse
    {
        $departments = $this->service->fetchData();

        return $this->successResponse(
            DepartmentResource::collection($departments)->response()->getData(true),
            'Departments fetched successfully.'
        );
    }

    public function show(Department $department): JsonResponse
    {
        return $this->successResponse(new DepartmentResource($department->load('user')));
    }

    public function store(DepartmentRequest $request): JsonResponse
    {
        $department = $this->service->store($request->validated());

        return $this->successResponse(new DepartmentResource($department), 'Department created.', 201);
    }

    public function update(DepartmentRequest $request, Department $department): JsonResponse
    {
        $department = $this->service->update($department, $request->validated());

        return $this->successResponse(new DepartmentResource($department), 'Department updated.');
    }

    public function destroy(Department $department): JsonResponse
    {
        $this->service->destroy($department);

        return $this->successResponse(null, 'Department deleted.');
    }
}
