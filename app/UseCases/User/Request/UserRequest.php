<?php

namespace App\UseCases\User\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $id = $this->route('id');

        return $id === null
            || ($this->user()?->hasRole('admin') || ($this->user() && method_exists($this->user(), 'hasApplicationRole') && $this->user()->hasApplicationRole('admin')))
            || (string) $this->user()?->getAuthIdentifier() === (string) $id;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        $uniqueEmployeeId = Rule::unique('users', 'employee_id');

        if ($this->route('id') !== null) {
            $uniqueEmployeeId->ignore($this->route('id'));
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users,email,'.$this->route('id')],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:8'],
            'employee_id' => [
                $isUpdate ? 'nullable' : 'required',
                'string',
                Rule::exists('employees', 'employee_id'),
                $uniqueEmployeeId,
            ],
        ];

        if ((string) $this->user()?->getAuthIdentifier() === (string) $this->route('id') && $this->filled('password')) {
            $rules['current_password'] = ['required', 'string', 'current_password:web'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please select an employee.',
            'name.string' => 'The name must be a valid text string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'email.required' => 'The username field is required.',
            'email.unique' => 'This username is already taken. Please choose another.',
            'email.max' => 'The username may not be greater than 255 characters.',
            'employee_id.required' => 'Please select an employee who does not already have a login account.',
            'employee_id.exists' => 'The selected employee ID is invalid.',
            'employee_id.unique' => 'This employee already has a login account.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters long.',
            'current_password.required' => 'Your current password is required.',
            'current_password.current_password' => 'The current password provided is incorrect.',
        ];
    }
}
