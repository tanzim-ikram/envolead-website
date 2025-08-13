<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\TeamMember;
use App\Http\Controllers\TeamController;
use App\Models\Project;
use App\Models\Partner;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'projects' => Project::whereNull('parent_id')->orderBy('sort_order')->get(),
        'clubPartners' => Partner::where('type', 'clubs')->get(),
        'companyPartners' => Partner::where('type', 'company')->get(),
        'team' => TeamMember::orderBy('id')->get(), // ✅ Correct line
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/team/{slug}', [TeamController::class, 'show']);


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
