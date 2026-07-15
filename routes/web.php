<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Guest — public application form
Route::get('apply/{jobPosting}', [App\Http\Controllers\PublicApplicationController::class, 'create'])->name('apply.create');
Route::post('apply/{jobPosting}', [App\Http\Controllers\PublicApplicationController::class, 'store'])->name('apply.store');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Module routes (scaffold)
Route::middleware('auth')->group(function () {
    Route::resource('group-companies', App\Http\Controllers\GroupCompanyController::class)->except(['show', 'edit']);
    Route::get('users/data', [App\Http\Controllers\UserController::class, 'data'])->name('users.data');
    Route::put('users/permissions', [App\Http\Controllers\UserController::class, 'updatePermissions'])->name('users.permissions');
    Route::patch('users/{user}/status', [App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.status');
    Route::resource('users', App\Http\Controllers\UserController::class)->except(['show', 'edit']);
    Route::get('jobs/data', [App\Http\Controllers\JobPostingController::class, 'data'])->name('jobs.data');
    Route::resource('jobs', App\Http\Controllers\JobPostingController::class);
    Route::get('applications/data', [App\Http\Controllers\ApplicationController::class, 'data'])->name('applications.data');
    Route::resource('applications', App\Http\Controllers\ApplicationController::class)->only(['index', 'show']);
    Route::patch('applications/{application}/status', [App\Http\Controllers\ApplicationController::class, 'updateStatus'])->name('applications.status');
    Route::get('documents/{document}/download', [App\Http\Controllers\ApplicationController::class, 'downloadDocument'])->name('documents.download');
    Route::patch('jobs/{jobPosting}/status', [App\Http\Controllers\JobPostingController::class, 'updateStatus'])->name('jobs.status');
    Route::resource('notification-groups', App\Http\Controllers\NotificationGroupController::class)->except(['show', 'edit']);
    Route::resource('activity-logs', App\Http\Controllers\ActivityLogController::class)->only(['index']);
    Route::get('settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
