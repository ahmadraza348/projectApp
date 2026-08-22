<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;

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
}