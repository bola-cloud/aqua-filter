<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Village;

class VillageController extends Controller
{
    public function index()
    {
        $villages = Village::paginate(10);
        return view('admin.villages.index', compact('villages'));
    }

    // Store a new village
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Village::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'تم إضافة القرية بنجاح');
    }

    // Show form for editing a village
    public function edit(Village $village)
    {
        return view('admin.villages.edit', compact('village'));
    }

    // Update an existing village
    public function update(Request $request, Village $village)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $village->update([
            'name' => $request->name,
        ]);

        return redirect()->route('villages.index')->with('success', 'تم تحديث القرية بنجاح');
    }

    // Delete a village
    public function destroy(Village $village)
    {
        $village->delete();
        return redirect()->back()->with('success', 'تم حذف القرية بنجاح');
    }
}
