<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Setup;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductLog;
use Yajra\DataTables\DataTables;
use App\Models\ProductLogItem;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Validator;

class ProductLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = ProductLog::with('product_log_items', 'product_warehouse')
                ->latest()->get();
                return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('product_log_item_list', function ($row) {
                    $product_log_items = $row->product_log_items;
                    $product_log_itemList = '<ul>';

                    foreach ($product_log_items as $product_log_item) {
                        $product = $product_log_item->product;
                        if ($product) {
                            $product_log_itemList .= '<li>' . $product->code . ' : ' . $product_log_item->qty . ' ' . $product->unit->symbol . '</li>';
                        }
                    }

                    $product_log_itemList .= '</ul>';
                    return $product_log_itemList;
                })
                ->editColumn('product_warehouse_id', function ($row) {
                    return $row->product_warehouse_id ? $row->product_warehouse->name : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('product_log.edit', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>
                    </div>
                ';
                })
                // ->rawColumns(['action', 'product_log_item_list', 'production_list'])
                ->rawColumns(['action', 'product_log_item_list'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
        }

        return view('product_log.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $products = Product::all();
        $code = ProductLog::generateCode();
        $product_warehouses = ProductWarehouse::all();
        return view('product_log.create', compact('setup', 'products', 'code', 'product_warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Custom validation rule to check if all products are unique
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:product_logs',
            'product_warehouse_id' => 'required',
            'status' => 'required',
            'products' => 'required|array',
            'quantities' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities.*' => 'numeric|min:1',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (count($request->products) !== count(array_unique($request->products))) {
                $validator->errors()->add('products', 'Each product must be unique.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create product_log
        $product_log = ProductLog::create([
            'code' => $request->code,
            'product_warehouse_id' => $request->product_warehouse_id,
            'status' => $request->status,
        ]);

        // Create product_log items
        $products = $request->input('products', []);
        $quantities = $request->input('quantities', []);

        foreach ($products as $index => $productId) {
            ProductLogItem::create([
                "product_log_id" => $product_log->id,
                "product_id" => $productId,
                "qty" => $quantities[$index],
            ]);
        }

        return redirect()->route('product_log.index')->with('success', 'Product Log created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductLog $product_log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $products = Product::all();
        $product_log = ProductLog::findOrFail($id);
        $product_warehouses = ProductWarehouse::all();
        return view('product_log.edit', compact('setup', 'products', 'product_log', 'product_warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_warehouse_id' => 'required',
            'status' => 'required',
            'products' => 'required|array',
            'quantities' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities.*' => 'numeric|min:1',
        ]);

        $product_log = ProductLog::findOrFail($id);

        // Update or create product_log items
        foreach ($request->products as $index => $productId) {
            $product_logItem = $product_log->product_log_items()->firstOrNew([
                'product_id' => $productId,
            ]);
            $product_logItem->qty = $request->quantities[$index];
            $product_logItem->save();
        }

        // Update product_log details
        $product_log->update([
            'product_warehouse_id' => $request->product_warehouse_id,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Product Log updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ProductLog::findOrFail($id)->delete();
        return redirect()->back()->with("success", "ProductLog has been deleted");
    }
}
