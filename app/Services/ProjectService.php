<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use App\Events\ProjectCreated;

class ProjectService
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {}

    public function fetchProjects($request = null)
    {
        $query = Project::with(['category', 'members'])
            ->withCount('members');

        if ($request) {

            // Search by name or description
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
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

        $projects = $query
            ->latest()
            ->paginate(9)
            ->withQueryString();

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

            // Admins cannot be assigned to projects
            'users' => User::where('role', '!=', 'admin')->get(),
        ];
    }


    public function store(array $data): Project
    {
        $project = DB::transaction(function () use ($data) {

            $members = $data['members'] ?? [];

            unset($data['members']);

            $project = Project::create($data);

            if (!empty($data['assigned_user_id'])) {
                $members[] = $data['assigned_user_id'];
            }

            $members = array_unique($members);

            if (!empty($members)) {
                $project->members()->sync($members);
            }

            $this->activityLogService->log(
                'created',
                $project,
                'Created project "' . $project->name . '"'
            );

            return $project;
        });

        ProjectCreated::dispatch($project);

        return $project;
    }


    public function update(Project $project, array $data): Project
    {
        return DB::transaction(function () use ($project, $data) {

            $members = $data['members'] ?? [];
            unset($data['members']);
            $project->update($data);
            if (!empty($data['assigned_user_id'])) {
                $members[] = $data['assigned_user_id'];
            }
            $members = array_unique($members);
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
        $project->members()->syncWithoutDetaching([
            $userId
        ]);

        return $project;
    }
}
