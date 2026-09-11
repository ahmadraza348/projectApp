<?php

namespace App\Services;

use App\Models\Department;

class DepartmentService
{
    public function fetchData()
    {
        return Department::with('user')->paginate(10);
    }

    public function store(array $data): Department
    {
        return Department::create($data);
    }

    public function update(Department $department, array $data): Department
    {
        $department->update($data);

        return $department;
    }

    public function destroy(Department $department): void
    {
        $department->delete();
    }
}
