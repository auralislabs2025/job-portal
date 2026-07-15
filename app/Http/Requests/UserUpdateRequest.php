<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('manage_users');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->route('user')->id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'group_company_id' => 'nullable|exists:group_companies,id',
            'status' => 'required|in:active,inactive',
        ];
    }
}
