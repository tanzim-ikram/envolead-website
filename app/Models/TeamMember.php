<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = ['name', 'slug', 'designation', 'photo', 'email', 'linkedin', 'bio'];
    // protected $fillable = ['name', 'designation', 'email', 'linkedin', 'bio', 'photo'];

}
