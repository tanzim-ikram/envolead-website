<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    // Dashboard projects view - showing only top-level projects
    public function index()
    {
        $projects = Project::with(['parent', 'children'])
            ->topLevel()
            ->active()
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
        ]);
    }

    // Show project details or children projects
    public function show($slug)
    {
        $project = Project::with(['parent', 'children', 'detail'])
            ->where('slug', $slug)
            ->firstOrFail();

        // If project has children, show children grid
        if ($project->hasChildren()) {
            return Inertia::render('Projects/Children', [
                'project' => $project,
                'children' => $project->children()->active()->get(),
                'breadcrumb' => $project->getProjectPath()
            ]);
        }

        // If no children, show project details
        return Inertia::render('Projects/Details', [
            'project' => $project,
            'breadcrumb' => $project->getProjectPath()
        ]);
    }

    // Admin management view - all projects in a table
    public function manage()
    {
        $projects = Project::with(['parent', 'children'])
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Admin/Projects/Index', [
            'projects' => $projects,
        ]);
    }

    // Show create form
    public function create()
    {
        $parentProjects = Project::topLevel()->active()->get();

        return Inertia::render('Admin/Projects/Create', [
            'parentProjects' => $parentProjects
        ]);
    }

    // Store new project
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'parent_id' => 'nullable|exists:projects,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('projects/icons', 'public');
        }

        // Set sort order if not provided
        if (!isset($validated['sort_order'])) {
            $maxOrder = Project::where('parent_id', $validated['parent_id'] ?? null)->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        $project = Project::create($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully!');
    }

    // Show edit form
    public function edit(Project $project)
    {
        $parentProjects = Project::topLevel()
            ->where('id', '!=', $project->id)
            ->active()
            ->get();

        return Inertia::render('Admin/Projects/Edit', [
            'project' => $project->load(['parent', 'detail']),
            'parentProjects' => $parentProjects
        ]);
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'parent_id' => 'nullable|exists:projects,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'in:active,inactive,archived'
        ]);

        // Prevent self-referencing or circular references
        if (isset($validated['parent_id']) && $validated['parent_id'] == $project->id) {
            return back()->withErrors(['parent_id' => 'A project cannot be its own parent.']);
        }

        // Handle icon upload
        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($project->icon) {
                Storage::disk('public')->delete($project->icon);
            }
            $validated['icon'] = $request->file('icon')->store('projects/icons', 'public');
        }

        $project->update($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project updated successfully!');
    }

    // Delete project
    public function destroy(Project $project)
    {
        // Check if project has children
        if ($project->hasChildren()) {
            return back()->withErrors(['error' => 'Cannot delete project with child projects. Please delete or reassign child projects first.']);
        }

        // Delete associated icon
        if ($project->icon) {
            Storage::disk('public')->delete($project->icon);
        }

        // Delete project detail if exists
        if ($project->detail) {
            $project->detail->delete();
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully!');
    }

    // Update project details (content)
    public function updateDetails(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $validated['project_id'] = $project->id;

        ProjectDetail::updateOrCreate(
            ['project_id' => $project->id],
            $validated
        );

        return back()->with('success', 'Project details updated successfully!');
    }
}
