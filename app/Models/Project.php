<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'slug',
        'icon',
        'short_description',
        'parent_id',
        'sort_order',
        'status'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'parent_id' => 'integer'
    ];

    protected $attributes = [
        'status' => 'active',
        'sort_order' => 0
    ];

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = static::generateUniqueSlug($project->project_name);
            }
        });

        static::updating(function ($project) {
            if ($project->isDirty('project_name')) {
                $project->slug = static::generateUniqueSlug($project->project_name, $project->id);
            }
        });

        // Clean up associated files when deleting
        static::deleting(function ($project) {
            // Delete icon file if exists
            if ($project->icon && Storage::disk('public')->exists($project->icon)) {
                Storage::disk('public')->delete($project->icon);
            }

            // Delete project detail
            if ($project->detail) {
                $project->detail->delete();
            }
        });
    }

    // Generate unique slug
    protected static function generateUniqueSlug($name, $ignoreId = null)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        $query = static::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;

            $query = static::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }

    // Self-referencing relationship for parent (mother project)
    public function parent()
    {
        return $this->belongsTo(Project::class, 'parent_id');
    }

    // Self-referencing relationship for subprojects
    public function children()
    {
        return $this->hasMany(Project::class, 'parent_id')->orderBy('sort_order');
    }

    // Get all descendants (children, grandchildren, etc.)
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    // One-to-one relation with project detail
    public function detail()
    {
        return $this->hasOne(ProjectDetail::class);
    }

    // Scope for top-level projects
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope for active projects
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for inactive projects
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Scope for archived projects
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    // Check if project has children
    public function hasChildren()
    {
        return $this->children()->exists();
    }

    // Check if project is a child (has parent)
    public function isChild()
    {
        return !is_null($this->parent_id);
    }

    // Check if project is top level (no parent)
    public function isTopLevel()
    {
        return is_null($this->parent_id);
    }

    // Get full project path for breadcrumbs
    public function getProjectPath()
    {
        $path = collect([$this]);
        $current = $this;

        while ($current->parent) {
            $current = $current->parent;
            $path->prepend($current);
        }

        return $path;
    }

    // Get project depth level (0 for top-level, 1 for first child, etc.)
    public function getDepthLevel()
    {
        $level = 0;
        $current = $this;

        while ($current->parent) {
            $level++;
            $current = $current->parent;
        }

        return $level;
    }

    // Get the root parent project
    public function getRootParent()
    {
        $current = $this;

        while ($current->parent) {
            $current = $current->parent;
        }

        return $current;
    }

    // Get siblings (projects with same parent)
    public function siblings()
    {
        return static::where('parent_id', $this->parent_id)
            ->where('id', '!=', $this->id)
            ->orderBy('sort_order');
    }

    // Get icon URL or default
    public function getIconUrlAttribute()
    {
        if ($this->icon) {
            return Storage::disk('public')->url($this->icon);
        }
        return null;
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'active':
                return 'bg-green-100 text-green-800';
            case 'inactive':
                return 'bg-yellow-100 text-yellow-800';
            case 'archived':
                return 'bg-gray-100 text-gray-800';
            default:
                return 'bg-green-100 text-green-800';
        }
    }

    // Get type label (Parent/Subproject)
    public function getTypeAttribute()
    {
        return $this->isChild() ? 'Subproject' : 'Parent Project';
    }

    // Check if project can be deleted (no children)
    public function canBeDeleted()
    {
        return !$this->hasChildren();
    }

    // Validate parent assignment (prevent circular references)
    public function canHaveParent($parentId)
    {
        if (!$parentId || $parentId == $this->id) {
            return false;
        }

        // Check if the proposed parent is already a descendant
        $descendants = $this->getAllDescendantIds();
        return !in_array($parentId, $descendants);
    }

    // Get all descendant IDs (for circular reference checking)
    private function getAllDescendantIds()
    {
        $descendants = [];
        $this->collectDescendantIds($this, $descendants);
        return $descendants;
    }

    // Recursively collect descendant IDs
    private function collectDescendantIds($project, &$descendants)
    {
        foreach ($project->children as $child) {
            $descendants[] = $child->id;
            $this->collectDescendantIds($child, $descendants);
        }
    }

    // Search scope
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('project_name', 'LIKE', "%{$term}%")
                ->orWhere('short_description', 'LIKE', "%{$term}%");
        });
    }

    // Get next sort order for siblings
    public function getNextSortOrder()
    {
        return static::where('parent_id', $this->parent_id)->max('sort_order') + 1;
    }
}
