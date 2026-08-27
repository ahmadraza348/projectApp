<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function fetchProjects($request = null)
    {
        $query = Project::with(['category', 'members'])->withCount('members');

        if ($request) {
            // Search by name or description
            if ($request->filled('search')) {           
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');               
            }

            // Filter by category
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }

        $projects = $query->latest()->paginate(9)->withQueryString();
        $categories = Category::where('status', true)->get();

        return [
            'projects'   => $projects,
            'categories' => $categories,
        ];
    }

    public function getCreateFormData(): array
    {
        return [
            'category' => Category::where('status', true)->get(),
            'users'    => User::where('role',  '!=',  'admin')->get(),
        ];
    }

    public function store(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            $members = $data['members'] ?? [];
            unset($data['members']);

            if (!isset($data['assigned_user_id']) && !empty($members)) {
                $data['assigned_user_id'] = $members[0];
            }

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

            if (!isset($data['assigned_user_id']) && !empty($members)) {
                $data['assigned_user_id'] = $members[0];
            }

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
