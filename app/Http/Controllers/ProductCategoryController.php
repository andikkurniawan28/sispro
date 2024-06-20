<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Setup;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $product_categories = ProductCategory::all();
        return view('product_category.index', compact('setup', 'product_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('product_category.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:product_categories",
        ]);
        ProductCategory::create($validated);
        return redirect()->back()->with("success", "Product Category has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $product_category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $product_category = ProductCategory::findOrFail($id);
        return view('product_category.edit', compact('setup', 'product_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product_category = ProductCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:product_categories,name,' . $product_category->id,
        ]);
        $product_category->update($validated);
        return redirect()->back()->with("success", "Product Category has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product_category = ProductCategory::findOrFail($id);
        $product_category->delete();
        return redirect()->back()->with("success", "Product Category has been deleted");
    }
}
