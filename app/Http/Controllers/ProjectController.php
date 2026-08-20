<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public $service;
    public function __construct(ProjectService $service)
    {
      $this->service = $service;
    }

    public function index(){
        return view('projects.index');
    }
    public function create(){
      $data = $this->service->getCreateFormData();
        return view('projects.create', compact('data'));
    }
}
