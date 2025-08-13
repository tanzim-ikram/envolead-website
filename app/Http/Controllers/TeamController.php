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

        return Inertia::render('Team', [
            'member' => $member
        ]);
    }

    public function update(Request $request, $id)
    {
        $member = TeamMember::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'linkedin' => 'nullable|url|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        // Only keep fields that are not null
        $updates = array_filter($validated, fn($value) => !is_null($value));

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($member->photo && file_exists(public_path($member->photo))) {
                unlink(public_path($member->photo));
            }

            $path = $request->file('photo')->store('team', 'public');
            $updates['photo'] = '/storage/' . $path;
        }

        $member->update($updates);

        return redirect()->route('admin.team.manage')->with('success', 'Member updated.');
    }
}
