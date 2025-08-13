<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamMember;
use Inertia\Inertia;

class AdminTeamController extends Controller
{
    public function index()
    {
        $members = TeamMember::all();

        return Inertia::render('Admin/Team/Index', [
            'members' => $members
        ]);
    }
}
