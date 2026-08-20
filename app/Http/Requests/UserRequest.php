<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users', 'email')->ignore($user ? $user->id : null),
            ],
            
            // Password is required only if it's a new user; optional on update
            'password' => $user ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'],
            
            'role' => ['required', 'in:admin,manager,member'],
        ];
    }
}