<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Project;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    $projects = Project::all();
    return Inertia::render('Welcome', [
        'projects' => $projects
    ]);
})->name('home');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
