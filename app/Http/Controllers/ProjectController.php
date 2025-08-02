<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        // Fetch all projects with parent relationship
        $projects = Project::with('parent')->get();

        return Inertia::render('Projects', [
            'projects' => $projects,
        ]);
    }
}
