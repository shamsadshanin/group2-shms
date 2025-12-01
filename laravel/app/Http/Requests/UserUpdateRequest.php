<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user');

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
            'role' => 'required|in:admin,doctor,patient,lab_technician,receptionist',
            'is_active' => 'boolean',
            'timezone' => 'required|timezone',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already taken.',
            'role.required' => 'The role field is required.',
            'role.in' => 'The selected role is invalid.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Prevent users from demoting themselves from admin
            if ($this->user()->id == $this->route('user') && $this->role !== 'admin') {
                $validator->errors()->add(
                    'role',
                    'You cannot change your own role from admin.'
                );
            }

            // Prevent deactivating own account
            if ($this->user()->id == $this->route('user') && !$this->is_active) {
                $validator->errors()->add(
                    'is_active',
                    'You cannot deactivate your own account.'
                );
            }
        });
    }
}
