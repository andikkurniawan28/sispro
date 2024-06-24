<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RawMaterialWarehouse;

class RawMaterialWarehouseController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $raw_material_warehouses = RawMaterialWarehouse::all();
        return view('raw_material_warehouse.index', compact('setup', 'raw_material_warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('raw_material_warehouse.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:raw_material_warehouses",
        ]);
        RawMaterialWarehouse::create($validated);
        return redirect()->back()->with("success", "Raw Material Warehouse has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(RawMaterialWarehouse $raw_material_warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $raw_material_warehouse = RawMaterialWarehouse::findOrFail($id);
        return view('raw_material_warehouse.edit', compact('setup', 'raw_material_warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $raw_material_warehouse = RawMaterialWarehouse::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:raw_material_warehouses,name,' . $raw_material_warehouse->id,
        ]);
        self::updateColumn($raw_material_warehouse, $request);
        $raw_material_warehouse->update($validated);
        return redirect()->back()->with("success", "Raw Material Warehouse has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $raw_material_warehouse = RawMaterialWarehouse::findOrFail($id);
        $raw_material_warehouse->delete();
        return redirect()->back()->with("success", "Raw Material Warehouse has been deleted");
    }

    public static function updateColumn($raw_material_warehouse, $request)
    {
        $old_column_name = str_replace(' ', '_', $raw_material_warehouse->name);
        $new_column_name = str_replace(' ', '_', $request->name);
        $rename_query = "ALTER TABLE raw_materials CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` FLOAT NULL";
        DB::statement($rename_query);
    }
}
