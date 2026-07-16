<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupCompanyStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('manage_companies');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:group_companies,code',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100|exists:countries,name',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
            'is_active' => 'boolean',
        ];
    }
}
