<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Setup;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\RawMaterialCategory;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $raw_materials = RawMaterial::all();
        return view('raw_material.index', compact('setup', 'raw_materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $raw_material_categories = RawMaterialCategory::all();
        $units = Unit::all();
        return view('raw_material.create', compact('setup', 'raw_material_categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "raw_material_category_id" => "required",
            "unit_id" => "required",
            "name" => "required|unique:raw_materials",
            "code" => "required|unique:raw_materials",
        ]);
        RawMaterial::create($validated);
        return redirect()->back()->with("success", "Raw Material has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(RawMaterial $raw_material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $raw_material = RawMaterial::findOrFail($id);
        $raw_material_categories = RawMaterialCategory::all();
        $units = Unit::all();
        return view('raw_material.edit', compact('setup', 'raw_material', 'raw_material_categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $raw_material = RawMaterial::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            "raw_material_category_id" => "required",
            "unit_id" => "required",
            'name' => 'required|unique:raw_materials,name,' . $raw_material->id,
            'code' => 'required|unique:raw_materials,code,' . $raw_material->id,
        ]);

        // Update data pengguna
        $raw_material->update($validated);

        return redirect()->back()->with("success", "Raw Material has been updated");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        RawMaterial::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Raw Material has been deleted");
    }
}
