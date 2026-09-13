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
        toastr()->success('Department added successfully');

        return redirect()->back();
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        $this->service->update($department, $request->validated());
        toastr()->success('Department updated successfully');

        return redirect()->back();
    }

    public function destroy(Department $department)
    {
        $this->service->destroy($department);
        toastr()->success('Department deleted successfully');

        return redirect()->back();
    }
}
