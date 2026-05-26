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
        ]);
        Package::create($data);
        return redirect()->route('admin.packages')->with('success', 'تم إضافة الباقة بنجاح');
    }
}
