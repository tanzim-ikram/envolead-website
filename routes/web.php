<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Project;
use App\Models\Partner;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/', function () {
//     $projects = Project::whereNull('parent_id')
//         ->orderBy('sort_order')
//         ->get();

//     return Inertia::render('Welcome', [
//         'projects' => $projects
//     ]);
// })->name('home');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'projects' => Project::whereNull('parent_id')->orderBy('sort_order')->get(),
        'clubPartners' => Partner::where('type', 'clubs')->get(),
        'companyPartners' => Partner::where('type', 'company')->get(),
    ]);
});



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
