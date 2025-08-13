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

    public function manage()
    {
        $members = TeamMember::all();
        return Inertia::render('Admin/Team/Manage', ['members' => $members]);
    }

    public function edit($id)
    {
        $member = TeamMember::findOrFail($id);
        return Inertia::render('Admin/Team/Edit', ['member' => $member]);
    }

    public function update(Request $request, $id)
    {
        $member = TeamMember::findOrFail($id);
        $member->update($request->only('name', 'designation', 'email', 'linkedin', 'photo', 'bio'));
        return redirect()->route('admin.team.manage')->with('success', 'Member updated successfully.');
    }

    public function destroy($id)
    {
        TeamMember::findOrFail($id)->delete();
        return redirect()->route('admin.team.manage')->with('success', 'Member deleted.');
    }
}
