<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\TeamMember;
use App\Http\Controllers\TeamController;
use App\Models\Project;
use App\Models\Partner;
use App\Http\Controllers\Admin\AdminTeamController;
use App\Http\Controllers\TeamMemberController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'projects' => Project::whereNull('parent_id')->orderBy('sort_order')->get(),
        'clubPartners' => Partner::where('type', 'clubs')->get(),
        'companyPartners' => Partner::where('type', 'company')->get(),
        'team' => TeamMember::orderBy('id')->get(),
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/team/{slug}', [TeamController::class, 'show']);

// Admin team routes
Route::prefix('admin/team')->name('admin.team.')->group(function () {
    Route::get('/', [AdminTeamController::class, 'index'])->name('index');
    Route::get('/manage', [AdminTeamController::class, 'manage'])->name('manage');
    Route::get('/{id}/edit', [AdminTeamController::class, 'edit'])->name('edit');
    Route::match(['post', 'put'],'/{id}', [AdminTeamController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminTeamController::class, 'destroy'])->name('destroy');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
