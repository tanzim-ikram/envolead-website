<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'other_designations',
        'email',
        'linkedin',
        'bio',
        'photo',
        'slug',
    ];
}
