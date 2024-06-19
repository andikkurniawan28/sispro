<?php

namespace App\Http\Controllers;

use App\Models\RawMaterialCategory;
use App\Models\Setup;
use Illuminate\Http\Request;

class RawMaterialCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $raw_material_categories = RawMaterialCategory::all();
        return view('raw_material_category.index', compact('setup', 'raw_material_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('raw_material_category.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:raw_material_categories",
        ]);
        RawMaterialCategory::create($validated);
        return redirect()->back()->with("success", "Raw Material Category has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(RawMaterialCategory $raw_material_category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $raw_material_category = RawMaterialCategory::findOrFail($id);
        return view('raw_material_category.edit', compact('setup', 'raw_material_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $raw_material_category = RawMaterialCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:raw_material_categories,name,' . $raw_material_category->id,
        ]);
        $raw_material_category->update($validated);
        return redirect()->back()->with("success", "Raw Material Category has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $raw_material_category = RawMaterialCategory::findOrFail($id);
        $raw_material_category->delete();
        return redirect()->back()->with("success", "Raw Material Category has been deleted");
    }
}
