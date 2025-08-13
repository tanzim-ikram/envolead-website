<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamMember;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string',
            'email' => 'nullable|email',
            'linkedin' => 'nullable|url',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Delete old photo if new one is uploaded
        if ($request->hasFile('photo')) {
            // Remove old photo if exists
            if ($member->photo && file_exists(public_path($member->photo))) {
                unlink(public_path($member->photo));
            }

            // Store new photo
            $photoPath = $request->file('photo')->store('team', 'public');
            $validated['photo'] = '/storage/' . $photoPath;
        } else {
            $validated['photo'] = $member->photo; // Keep existing photo
        }

        $member->update($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy($id)
    {
        TeamMember::findOrFail($id)->delete();
        return redirect()->route('admin.team.manage')->with('success', 'Member deleted.');
    }
}
