<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data = $this->service->fetchProjects($request);
        return view('projects.index', compact('data'));
    }

    public function create()
    {
        $data = $this->service->getCreateFormData();
        return view('projects.create', compact('data'));
    }

    public function store(ProjectRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('project.index')
            ->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        $project->load(['members', 'category']);
        $formData = $this->service->getCreateFormData();
        return view('projects.show', compact('project', 'formData'));
    }


    public function edit(Project $project)
    {
        // Eager load relations so members and category populate cleanly in the edit form
        $project->load(['members', 'category']);

        // Pass necessary form options (categories, users) along with the project instance
        $formData = $this->service->getCreateFormData();

        return view('projects.edit', compact('project', 'formData'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $this->service->update($project, $request->validated());

        return redirect()
            ->route('project.index')
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project){
         $this->service->delete($project);
           return redirect()
            ->route('project.index')
            ->with('success', 'Project Deleted successfully!');
    }
}
