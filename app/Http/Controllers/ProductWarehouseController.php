<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductWarehouse;

class ProductWarehouseController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $product_warehouses = ProductWarehouse::all();
        return view('product_warehouse.index', compact('setup', 'product_warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('product_warehouse.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:product_warehouses",
        ]);
        ProductWarehouse::create($validated);
        return redirect()->back()->with("success", "Product Warehouse has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductWarehouse $product_warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $product_warehouse = ProductWarehouse::findOrFail($id);
        return view('product_warehouse.edit', compact('setup', 'product_warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product_warehouse = ProductWarehouse::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:product_warehouses,name,' . $product_warehouse->id,
        ]);
        self::updateColumn($product_warehouse, $request);
        $product_warehouse->update($validated);
        return redirect()->back()->with("success", "Product Warehouse has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product_warehouse = ProductWarehouse::findOrFail($id);
        $product_warehouse->delete();
        return redirect()->back()->with("success", "Product Warehouse has been deleted");
    }

    public static function updateColumn($product_warehouse, $request)
    {
        $old_column_name = str_replace(' ', '_', $product_warehouse->name);
        $new_column_name = str_replace(' ', '_', $request->name);
        $rename_query = "ALTER TABLE products CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` FLOAT NULL";
        DB::statement($rename_query);
    }
}
