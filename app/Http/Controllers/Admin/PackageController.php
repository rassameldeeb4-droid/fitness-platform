<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('admin.packages', compact('packages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'type' => 'required|string',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
            'max_bookings' => 'integer',
            'has_trainer' => 'boolean',
            'has_nutrition' => 'boolean',
            'has_ai' => 'boolean',
            'badge' => 'nullable|string',
        ]);
        if (isset($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        }
        Package::create($data);
        return redirect()->route('admin.packages')->with('success', 'تم إضافة الباقة بنجاح');
    }

    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('admin.package-edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'type' => 'required|string',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
            'max_bookings' => 'integer',
            'has_trainer' => 'boolean',
            'has_nutrition' => 'boolean',
            'has_ai' => 'boolean',
            'badge' => 'nullable|string',
        ]);
        if (isset($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        }
        $package->update($data);
        return redirect()->route('admin.packages')->with('success', 'تم تحديث الباقة بنجاح');
    }

    public function destroy($id)
    {
        Package::findOrFail($id)->delete();
        return redirect()->route('admin.packages')->with('success', 'تم حذف الباقة');
    }
}
