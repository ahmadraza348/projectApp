<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Department;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function fetchProjects($request = null)
    {
        $query = Project::with(['department', 'client', 'manager', 'members'])->withCount('members');

        if ($request) {
            // Search by name or description
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by department
            if ($request->filled('department')) {
                $query->where('department_id', $request->department);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }

        $projects = $query->latest()->paginate(9)->withQueryString();
        $departments = Department::where('status', true)->get();

        return [
            'projects'    => $projects,
            'departments' => $departments,
        ];
    }

    public function getCreateFormData(): array
    {
        return [
            'departments' => Department::where('status', true)->get(),
            'clients'     => Client::orderBy('name')->get(),
            'users'       => User::where('role', '!=', 'admin')->get(),
        ];
    }

    public function store(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            $members = $data['members'] ?? [];
            unset($data['members']);

            $data['created_by'] = $data['created_by'] ?? auth()->id();

            $project = Project::create($data);

            if (!empty($members)) {
                $project->members()->sync($members);
            }

            return $project;
        });
    }

    public function update(Project $project, array $data): Project
    {
        return DB::transaction(function () use ($project, $data) {
            $members = $data['members'] ?? [];
            unset($data['members']);

            $project->update($data);

            $project->members()->sync($members);

            return $project;
        });
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }

    public function addMember(Project $project, int $userId): Project
    {
        $project->members()->syncWithoutDetaching([$userId]);
        return $project;
    }
}
