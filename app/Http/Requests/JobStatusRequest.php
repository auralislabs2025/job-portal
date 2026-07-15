<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $action = $this->input('action');

        if ($action === 'submit') {
            return $user->hasPermission('post_jobs');
        }

        return $user->hasPermission('approve_jobs');
    }

    public function rules(): array
    {
        return [
            'action' => 'required|in:submit,approve,revert,reject',
            'rejected_reason' => 'required_if:action,reject|string|max:1000',
        ];
    }
}
