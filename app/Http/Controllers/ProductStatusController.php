<?php

namespace App\Http\Controllers;

use App\Models\ProductStatus;
use App\Models\Setup;
use Illuminate\Http\Request;

class ProductStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $product_statuses = ProductStatus::all();
        return view('product_status.index', compact('setup', 'product_statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('product_status.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:product_statuses",
        ]);
        ProductStatus::create($validated);
        return redirect()->back()->with("success", "Product Status has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductStatus $product_status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $product_status = ProductStatus::findOrFail($id);
        return view('product_status.edit', compact('setup', 'product_status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product_status = ProductStatus::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:product_statuses,name,' . $product_status->id,
        ]);
        $product_status->update($validated);
        return redirect()->back()->with("success", "Product Status has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product_status = ProductStatus::findOrFail($id);
        $product_status->delete();
        return redirect()->back()->with("success", "Product Status has been deleted");
    }
}
