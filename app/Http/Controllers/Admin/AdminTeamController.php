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
    public function create()
    {
        return Inertia::render('Admin/Team/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'linkedin' => 'nullable|url|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('team', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(6);

        TeamMember::create($validated);

        return redirect()->route('admin.team.manage')->with('success', 'Team member added successfully.');
    }

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
        if ($request->has('other_designations')) {
            $validationRules['other_designations'] = 'nullable|string|max:255';
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
