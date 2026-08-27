<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $service) {}
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request): JsonResponse
    {
        $data = $this->service->fetchProjects($request);

        return response()->json([
            'success' => true,
            'message' => 'Projects fetched successfully',
            'data'    => ProjectResource::collection($data['projects'])->response()->getData(true),
        ], 200);
    }


    public function store(ProjectRequest $request): JsonResponse
    {
        $project = $this->service->store($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data'    => new ProjectResource($project),
        ], 201);
    }

    public function show(Project $project): JsonResponse
    {
        $project->load(['members', 'category', 'tasks.assignee']);

        return response()->json([
            'success' => true,
            'message' => 'Project fetched successfully',
            'data'    => new ProjectResource($project),
        ], 200);
    }

    public function edit(Project $project)
    {
        $data['project'] = $project->load(['members', 'category']);
        $data['formData'] = $this->service->getCreateFormData();
        return response()->json([
            'success' => true,
            'message' => "Data Fetched Successfully",
            'data' => $data,
        ], 200);
    }


    /**
     * Update the specified project.
     */
    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        $updatedProject = $this->service->update($project, $request->validated());
        $updatedProject->load(['members', 'category']);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data'    => new ProjectResource($updatedProject),
        ], 200);
    }


    public function destroy(Project $project): JsonResponse
    {
        $this->service->delete($project);

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
        ], 200);
    }
}
