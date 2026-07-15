<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobPostingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $job = $this->route('job');

        if ($user->hasPermission('approve_jobs')) {
            return true;
        }

        return $user->hasPermission('post_jobs')
            && $job?->created_by === $user->id
            && $job?->status === 'draft';
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
