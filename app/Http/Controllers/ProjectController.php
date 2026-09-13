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

        return redirect()->route('project.index')->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        // Added tasks.assignee — real tasks now feed the Tasks table on this page
        $project->load(['members', 'department', 'client', 'manager', 'tasks.assignee']);
        $formData = $this->service->getCreateFormData();
        return view('projects.show', compact('project', 'formData'));
    }

    public function edit(Project $project)
    {
        $project->load(['members', 'department', 'client', 'manager']);
        $formData = $this->service->getCreateFormData();
        return view('projects.edit', compact('project', 'formData'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $this->service->update($project, $request->validated());
        toastr()->success('Project updated successfully');
        return redirect()->route('project.index');
    }

    public function destroy(Project $project)
    {
        $this->service->delete($project);
        toastr()->success('Project deleted successfully');
        return redirect()->route('project.index');
    }

    public function addMember(Request $request, Project $project)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $this->service->addMember($project, (int) $validated['user_id']);

        toastr()->success('Member added to project.');
        return redirect()->back();  
    }
}
