<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationGroupStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('manage_notification_groups');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'group_company_id' => 'required|exists:group_companies,id',
            'description' => 'nullable|string|max:500',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ];
    }
}
