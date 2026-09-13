<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Matches the current projects migration: department_id/client_id/
     * project_manager_id/code/slug/deadline/priority/health_status/progress/
     * is_billable — there is no category_id or end_date column anymore.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $project = $this->route('project');

        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('projects', 'name')->ignore($project ? $project->id : null),
            ],
            'description' => ['nullable', 'string'],

            'department_id'       => ['nullable', 'exists:departments,id'],
            'client_id'           => ['nullable', 'exists:clients,id'],
            'project_manager_id'  => ['nullable', 'exists:users,id'],

            'code' => [
                'nullable', 'string', 'max:50',
                Rule::unique('projects', 'code')->ignore($project ? $project->id : null),
            ],

            'status'   => ['required', Rule::in(['planning', 'in_progress', 'review', 'completed'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],

            'start_date' => ['nullable', 'date'],
            'deadline'   => ['nullable', 'date', 'after_or_equal:start_date'],

            'budget'        => ['nullable', 'numeric', 'min:0'],
            'health_status' => ['nullable', Rule::in(['on_track', 'at_risk', 'off_track'])],
            'progress'      => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_billable'   => ['nullable', 'boolean'],
            'notes'         => ['nullable', 'string'],

            'members'   => ['nullable', 'array'],
            'members.*' => ['exists:users,id'],
        ];
    }
}
