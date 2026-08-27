<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\UserResource;
use App\Models\Project;
use App\Services\ProjectService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use ApiResponse;

    public function __construct(protected ProjectService $service) {}

    public function index(Request $request): JsonResponse
    {
        $data = $this->service->fetchProjects($request);
        
        return $this->successResponse(
            ProjectResource::collection($data['projects'])->response()->getData(true), 
            'Projects fetched successfully'
        );
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $project = $this->service->store($request->validated());
        return $this->successResponse(new ProjectResource($project), 'Project created successfully', 201);
    }

    public function show(Project $project): JsonResponse
    {
        $project->load(['members', 'category', 'tasks.assignee']);
        return $this->successResponse(new ProjectResource($project), 'Project fetched successfully');
    }

    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        $updatedProject = $this->service->update($project, $request->validated());
        $updatedProject->load(['members', 'category']);

        return $this->successResponse(new ProjectResource($updatedProject), 'Project updated successfully');
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->service->delete($project);
        return $this->successResponse(null, 'Project deleted successfully');
    }

    /**
     * List members currently assigned to the project.
     */
    public function members(Project $project): JsonResponse
    {
        return $this->successResponse(
            UserResource::collection($project->members),
            'Project members fetched successfully'
        );
    }

    /**
     * Add a member to the project.
     */
    public function addMember(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $project = $this->service->addMember($project, (int) $validated['user_id']);

        return $this->successResponse(
            UserResource::collection($project->members()->get()),
            'Member added to project.'
        );
    }
}