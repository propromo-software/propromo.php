<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'auth_type' => ['required', 'string', 'in:google,github,email'],
            'password' => [
                'required',
                'string',
                'min:8', // At least 8 characters
                'regex:/[A-Z]/', // At least 1 uppercase letter
                'regex:/[a-z]/', // At least 1 lowercase letter
                'regex:/[0-9]/', // At least 1 number
                'regex:/[@$!%*?&#]/' // At least 1 special character
            ]
        ];
    }

    /**
     * Automatically convert request attributes before validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'auth_type' => $this->authType, // Convert camelCase to snake_case
        ]);
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'auth_type.required' => 'Auth type is required.',
            'auth_type.in' => 'Auth type must be one of: google, github, email.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least 1 uppercase letter, 1 number, and 1 special character.'
        ];
    }
}
