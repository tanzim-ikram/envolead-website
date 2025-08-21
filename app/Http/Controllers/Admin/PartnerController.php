<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->paginate(20);
        return Inertia::render('Admin/Partners/Index', [
            'partners' => $partners,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Partners/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'partner_name' => 'required|string|max:255',
            'type' => 'required|in:clubs,company',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // ✅ required on create
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('partners', 'public');
        }

        Partner::create($validated);

        return redirect()->route('admin.partners.index')->with('success', 'Partner added successfully');
    }

    public function edit(Partner $partner)
    {
        return Inertia::render('Admin/Partners/Edit', [
            'partner' => $partner,
        ]);
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'partner_name' => 'required|string|max:255',
            'type' => 'required|in:clubs,company',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // ✅ optional on update
        ]);

        if ($request->hasFile('logo')) {
            // delete old logo
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $validated['logo'] = $request->file('logo')->store('partners', 'public');
        } else {
            // keep old logo if no new file uploaded
            $validated['logo'] = $partner->logo;
        }

        $partner->update($validated);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner deleted successfully');
    }
}
