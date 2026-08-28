<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id'      => ['required', 'exists:projects,id'],
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'member_id'       => ['nullable', 'exists:users,id'],
            'priority'        => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'status'          => ['nullable', Rule::in(['todo', 'in_progress', 'review', 'completed'])],
            'due_date'        => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
        ];
    }

    public function attributes(): array
    {
        return [
            'project_id' => 'project',
            'member_id'  => 'assigned member',
        ];
    }
}
