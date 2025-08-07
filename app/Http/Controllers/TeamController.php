<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\TeamMember;

class TeamController extends Controller
{
    public function show($slug)
    {
        $member = TeamMember::where('slug', $slug)->firstOrFail();

        return Inertia::render('Team/TeamProfile', [
            'member' => $member
        ]);
    }
}
