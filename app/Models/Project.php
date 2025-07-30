<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'slug',
        'icon',
        'short_description',
        'parent_id',
    ];

    // Self-referencing relationship for parent (mother project)
    public function parent()
    {
        return $this->belongsTo(Project::class, 'parent_id');
    }

    // Self-referencing relationship for subprojects
    public function children()
    {
        return $this->hasMany(Project::class, 'parent_id');
    }

    // One-to-one relation with project detail (like blog)
    public function detail()
    {
        return $this->hasOne(ProjectDetail::class);
    }
}

