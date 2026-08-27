<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
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
}