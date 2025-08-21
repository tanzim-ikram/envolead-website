<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('id', 'desc')->get()->map(function ($partner) {
            // Handle both old (public/partners) and new (storage/partners) paths
            $partner->logo_url = $this->getLogoUrl($partner->logo);
            return $partner;
        });

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
        $data = $request->validate([
            'partner_name' => 'required|string|max:255',
            'type' => 'required|in:clubs,company',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        }

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner created successfully.');
    }

    public function edit(Partner $partner)
    {
        // Add logo URL for preview
        $partner->logo_url = $this->getLogoUrl($partner->logo);
        
        return Inertia::render('Admin/Partners/Edit', [
            'partner' => $partner,
        ]);
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'partner_name' => 'required|string|max:255',
            'type' => 'required|in:clubs,company',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            $this->deleteOldLogo($partner->logo);
            
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        } else {
            // Prevent overwriting existing logo with null when no new file is provided
            unset($data['logo']);
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner)
    {
        // Delete logo file if it exists
        $this->deleteOldLogo($partner->logo);
        
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner deleted successfully.');
    }

    private function getLogoUrl($logoPath)
    {
        if (!$logoPath) {
            return null;
        }

        // Check if it's an old path (public/partners)
        if (File::exists(public_path('partners/' . basename($logoPath)))) {
            return '/partners/' . basename($logoPath);
        }

        // Check if it's a new path (storage/partners)
        if (File::exists(storage_path('app/public/' . $logoPath))) {
            return '/storage/' . $logoPath;
        }

        // Fallback - assume it's in storage
        return '/storage/' . $logoPath;
    }

    private function deleteOldLogo($logoPath)
    {
        if (!$logoPath) {
            return;
        }

        // Try to delete from old location (public/partners)
        $oldPath = public_path('partners/' . basename($logoPath));
        if (File::exists($oldPath)) {
            File::delete($oldPath);
        }

        // Try to delete from new location (storage/partners)
        $newPath = storage_path('app/public/' . $logoPath);
        if (File::exists($newPath)) {
            File::delete($newPath);
        }
    }
}