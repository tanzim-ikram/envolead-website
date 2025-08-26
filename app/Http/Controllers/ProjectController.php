<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
            ->active()
            ->firstOrFail();

        // If project has children, show children grid
        if ($project->hasChildren()) {
            return Inertia::render('Projects/Children', [
                'project' => $project,
                'children' => $project->children()->active()->orderBy('sort_order')->get(),
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
        $projects = Project::with(['children:id,parent_id,sort_order'])
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'project_name' => $project->project_name,
                    'slug' => $project->slug,
                    'icon' => $project->icon,
                    'short_description' => $project->short_description,
                    'parent_id' => $project->parent_id,
                    'sort_order' => $project->sort_order,
                    'status' => $project->status,
                    'updated_at' => optional($project->updated_at)->toISOString(),
                    // Keep children lightweight for UI checks (delete-disable and indentation)
                    'children' => $project->children->map(fn ($c) => [
                        'id' => $c->id,
                        'parent_id' => $c->parent_id,
                        'sort_order' => $c->sort_order,
                    ])->values(),
                ];
            });

        return Inertia::render('Admin/Projects/Index', [
            'projects' => $projects,
        ]);
    }


    // Show create form
    public function create()
    {
        $parentProjects = Project::topLevel()
            ->active()
            ->orderBy('project_name')
            ->get(['id', 'project_name']);

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
            'detail_title' => 'nullable|string|max:255',
            'detail_content' => 'nullable|string',
        ]);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('projects/icons', 'public');
        }

        // Auto assign sort_order if empty
        if (!isset($validated['sort_order'])) {
            $maxOrder = Project::where('parent_id', $validated['parent_id'] ?? null)->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        // Create project
        $project = Project::create($validated);

        // Create detail if provided
        if (!empty($validated['detail_title']) || !empty($validated['detail_content'])) {
            $project->detail()->create([
                'title' => $validated['detail_title'] ?: $project->project_name,
                'content' => $validated['detail_content'],
            ]);
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully!');
    }

    // Show edit form
    public function edit(Project $project)
    {
        $parentProjects = Project::topLevel()
            ->where('id', '!=', $project->id)
            ->active()
            ->orderBy('project_name')
            ->get(['id', 'project_name']);

        return Inertia::render('Admin/Projects/Edit', [
            'project' => $project->load(['parent', 'detail']),
            'parentProjects' => $parentProjects
        ]);
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'project_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects', 'project_name')->ignore($project->id)
            ],
            'short_description' => 'nullable|string|max:500',
            'parent_id' => [
                'nullable',
                'exists:projects,id',
                function ($attribute, $value, $fail) use ($project) {
                    if ($value == $project->id) {
                        $fail('A project cannot be its own parent.');
                    }

                    // Check for circular references
                    if ($value && $this->wouldCreateCircularReference($project, $value)) {
                        $fail('This would create a circular reference.');
                    }
                }
            ],
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,archived'
        ]);

        // Update slug if project name changed
        if ($validated['project_name'] !== $project->project_name) {
            $baseSlug = Str::slug($validated['project_name']);
            $slug = $baseSlug;
            $counter = 1;

            while (Project::where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $validated['slug'] = $slug;
        }

        // Handle icon upload
        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($project->icon && Storage::disk('public')->exists($project->icon)) {
                Storage::disk('public')->delete($project->icon);
            }

            $iconPath = $request->file('icon')->store('projects/icons', 'public');
            $validated['icon'] = $iconPath;
        }

        $project->update($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project "' . $project->project_name . '" updated successfully!');
    }

    // Delete project
    public function destroy(Project $project)
    {
        // Check if project has children
        if ($project->hasChildren()) {
            return redirect()->route('admin.projects.index')
                ->with('error', 'Cannot delete project "' . $project->project_name . '" because it has child projects. Please delete or reassign child projects first.');
        }

        $projectName = $project->project_name;

        // Delete associated icon
        if ($project->icon && Storage::disk('public')->exists($project->icon)) {
            Storage::disk('public')->delete($project->icon);
        }

        // Delete project detail if exists
        if ($project->detail) {
            $project->detail->delete();
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project "' . $projectName . '" deleted successfully!');
    }

    // Update project details (content)
    public function updateDetails(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10'
        ]);

        $validated['project_id'] = $project->id;

        ProjectDetail::updateOrCreate(
            ['project_id' => $project->id],
            $validated
        );

        return back()->with('success', 'Project details updated successfully!');
    }

    // Helper method to check for circular references
    private function wouldCreateCircularReference(Project $project, $parentId)
    {
        $current = Project::find($parentId);

        while ($current) {
            if ($current->id === $project->id) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }
}
