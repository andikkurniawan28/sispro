<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Setup;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductStatus;
use App\Models\ProductCategory;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Product::with('product_category', 'unit', 'product_status')
                ->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('product_category_id', function ($row) {
                    return $row->product_category_id ? $row->product_category->name : 'N/A';
                })
                ->editColumn('product_status_id', function ($row) {
                    return $row->product_status_id ? $row->product_status->name : 'N/A';
                })
                ->editColumn('unit_id', function ($row) {
                    return $row->unit_id ? $row->unit->name : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('product.edit', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>
                    </div>
                ';
                })
                ->rawColumns(['action', 'product_category_id', 'unit_id', 'product_status_id'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
        }
        return view('product.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $product_categories = ProductCategory::all();
        $product_statuses = ProductStatus::all();
        $units = Unit::all();
        return view('product.create', compact('setup', 'product_categories', 'units', 'product_statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "product_category_id" => "required",
            "unit_id" => "required",
            "product_status_id" => "required",
            "name" => "required|unique:products",
            "code" => "required|unique:products",
            "barcode" => "nullable|unique:products",
            "expiration_time" => "required",
        ]);
        Product::create($validated);
        return redirect()->back()->with("success", "Product has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $product = Product::findOrFail($id);
        $product_categories = ProductCategory::all();
        $product_statuses = ProductStatus::all();
        $units = Unit::all();
        return view('product.edit', compact('setup', 'product', 'product_categories', 'units', 'product_statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            "product_category_id" => "required",
            "unit_id" => "required",
            "product_status_id" => "required",
            'name' => 'required|unique:products,name,' . $product->id,
            'code' => 'required|unique:products,code,' . $product->id,
            "barcode" => 'nullable|unique:products,name,' . $product->id,
            "expiration_time" => "required",
        ]);

        // Update data pengguna
        $product->update($validated);

        return redirect()->back()->with("success", "Product has been updated");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Product has been deleted");
    }
}
