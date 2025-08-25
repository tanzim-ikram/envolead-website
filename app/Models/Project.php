<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

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

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->project_name);
            }
        });
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

    // Check if project has children
    public function hasChildren()
    {
        return $this->children()->exists();
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
}
