<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Models\User;
use App\Services\DepartmentService;

class DepartmentController extends Controller
{
    public function __construct(private readonly DepartmentService $service) {}

    public function index()
    {
        $departments = $this->service->fetchData();
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('department', compact('departments', 'users'));
    }

    public function store(DepartmentRequest $request)
    {
        $this->service->store($request->validated());

        return back()->with('success', 'Department Added');
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        $this->service->update($department, $request->validated());

        return back()->with('success', 'Department Updated');
    }

    public function destroy(Department $department)
    {
        $this->service->destroy($department);

        return back()->with('success', 'Department Deleted');
    }
}
