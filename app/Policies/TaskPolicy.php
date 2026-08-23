<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Everyone in the tasks section can see a (filtered) task list —
     * the actual "member only sees their own" restriction happens in
     * TaskService::fetchTasks(), not here, since this only gates single models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'member']);
    }

    /**
     * Admin/manager can view any task. A member can only view a task assigned to them.
     */
    public function view(User $user, Task $task): bool
    {
        if (in_array($user->role, ['admin', 'manager'])) {
            return true;
        }

        return $task->member_id === $user->id;
    }

    /**
     * Only admin/manager create tasks.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Admin/manager can update any task. A member can only update
     * (e.g. drag-drop status, edit) a task assigned to them.
     */
    public function update(User $user, Task $task): bool
    {
        if (in_array($user->role, ['admin', 'manager'])) {
            return true;
        }

        return $task->member_id === $user->id;
    }

    /**
     * Only admin/manager delete tasks — a member can't delete their own.
     */
    public function delete(User $user, Task $task): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function restore(User $user, Task $task): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $user->role === 'admin';
    }
}
