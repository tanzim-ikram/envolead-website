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

        // Only validate fields that are present in the request
        $validationRules = [];
        
        if ($request->has('name')) {
            $validationRules['name'] = 'required|string|max:255';
        }
        if ($request->has('designation')) {
            $validationRules['designation'] = 'required|string';
        }
        if ($request->has('email')) {
            $validationRules['email'] = 'nullable|email';
        }
        if ($request->has('linkedin')) {
            $validationRules['linkedin'] = 'nullable|url';
        }
        if ($request->has('bio')) {
            $validationRules['bio'] = 'nullable|string';
        }
        if ($request->hasFile('photo')) {
            $validationRules['photo'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        $validated = $request->validate($validationRules);

        // Delete old photo if new one is uploaded
        if ($request->hasFile('photo')) {
            // Remove old photo if exists
            if ($member->photo && file_exists(public_path($member->photo))) {
                unlink(public_path($member->photo));
            }

            // Store new photo
            $photoPath = $request->file('photo')->store('team', 'public');
            $validated['photo'] = '/storage/' . $photoPath;
        }

        $member->update($validated);

        return redirect()->route('admin.team.manage')->with('success', 'Team member updated successfully.');
    }

    public function destroy($id)
    {
        TeamMember::findOrFail($id)->delete();
        return redirect()->route('admin.team.manage')->with('success', 'Member deleted.');
    }
}
