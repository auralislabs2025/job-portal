<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->hasPermission('manage_settings'), 403);
        $settings = UserSetting::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'notify_new_applications' => true,
                'notify_job_approvals' => true,
                'notify_weekly_reports' => false,
                'dark_mode' => false,
            ]
        );

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_settings'), 403);
        $data = $request->validate([
            'notify_new_applications' => 'boolean',
            'notify_job_approvals' => 'boolean',
            'notify_weekly_reports' => 'boolean',
            'dark_mode' => 'boolean',
        ]);

        UserSetting::updateOrCreate(
            ['user_id' => auth()->id()],
            $data,
        );

        return redirect()->route('settings.index')
            ->with('success', 'Settings saved successfully.');
    }
}
