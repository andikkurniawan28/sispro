<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Setup;
use App\Models\Demand;
use App\Models\Product;
use App\Models\DemandItem;
use Illuminate\Http\Request;
use App\Models\ProductStatus;
use App\Models\DemandCategory;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class DemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Demand::with('demand_items')
                ->latest()->get();
                return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('demand_item_list', function ($row) {
                    $demand_items = $row->demand_items;
                    $demand_itemList = '<ul>';

                    foreach ($demand_items as $demand_item) {
                        $product = $demand_item->product;
                        if ($product) {
                            $demand_itemList .= '<li>' . $product->code . ' : ' . $demand_item->qty . ' ' . $product->unit->symbol . '</li>';
                        }
                    }

                    $demand_itemList .= '</ul>';
                    return $demand_itemList;
                })
                ->addColumn('production_list', function ($row) {
                    $productions = $row->productions;
                    $productionList = '<ul>';

                    foreach ($productions as $production) {
                        $product = $production->product;
                        if ($product) {
                            $productionList .= '<li>' . $production->code .'-'. $product->code . ' : ' . $production->qty . ' ' . $product->unit->symbol . '</li>';
                        }
                    }

                    $productionList .= '</ul>';
                    return $productionList;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('demand.edit', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>
                    </div>
                ';
                })
                ->rawColumns(['action', 'demand_item_list', 'production_list'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
        }

        return view('demand.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $saleStatusId = ProductStatus::where('name', 'Sale')->value('id');
        $products = Product::where('product_status_id', $saleStatusId)->get();
        $code = Demand::generateCode();
        return view('demand.create', compact('setup', 'products', 'code'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Custom validation rule to check if all products are unique
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:demands',
            'due_date' => 'required|date',
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

        // Create demand
        $demand = Demand::create([
            'code' => $request->code,
            'due_date' => $request->due_date,
        ]);

        // Create demand items
        $products = $request->input('products', []);
        $quantities = $request->input('quantities', []);

        foreach ($products as $index => $productId) {
            DemandItem::create([
                "demand_id" => $demand->id,
                "product_id" => $productId,
                "qty" => $quantities[$index],
            ]);
        }

        return redirect()->route('demand.index')->with('success', 'Demand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Demand $demand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $saleStatusId = ProductStatus::where('name', 'Sale')->value('id');
        $products = Product::where('product_status_id', $saleStatusId)->get();
        $demand = Demand::findOrFail($id);
        return view('demand.edit', compact('setup', 'products', 'demand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'due_date' => 'required|date',
            'products' => 'required|array',
            'quantities' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities.*' => 'numeric|min:1',
        ]);

        $demand = Demand::findOrFail($id);

        // Update or create demand items
        foreach ($request->products as $index => $productId) {
            $demandItem = $demand->demand_items()->where('product_id', $productId)->firstOrNew([]);
            $demandItem->qty = $request->quantities[$index];
            $demandItem->save();
        }

        // Update demand details
        $demand->update([
            'due_date' => $request->due_date,
        ]);

        return redirect()->back()->with('success', 'Demand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Demand::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Demand has been deleted");
    }
}
