<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobPostingStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('post_jobs');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'group_company_id' => 'required|exists:group_companies,id',
            'employment_type' => 'nullable|in:full_time,part_time,contract,internship',
            'location' => 'nullable|string|max:255',
            'salary' => 'nullable|string|max:100',
            'deadline' => 'nullable|date',
            'description' => 'nullable|string',
            'notification_group_ids' => 'nullable|array',
            'notification_group_ids.*' => 'exists:notification_groups,id',
        ];
    }
}
