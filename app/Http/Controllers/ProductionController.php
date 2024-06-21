<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\Setup;
use App\Models\Product;
use App\Models\Production;
use Illuminate\Http\Request;
use App\Models\ProductStatus;
use Yajra\DataTables\DataTables;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Production::with('product', 'demand')
                ->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('product_id', function ($row) {
                    return $row->product_id ? $row->product->code : 'N/A';
                })
                ->editColumn('demand_id', function ($row) {
                    return $row->demand_id ? $row->demand->code : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('production.edit', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>
                    </div>
                ';
                })
                ->rawColumns(['action', 'product_id', 'demand_id'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
        }
        return view('production.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $saleStatusId = ProductStatus::where('name', 'Sale')->value('id');
        $products = Product::where('product_status_id', $saleStatusId)->get();
        $demands = Demand::all();
        $code = Production::generateCode();
        return view('production.create', compact('setup', 'products', 'demands', 'code'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "product_id" => "required",
            "demand_id" => "required",
            "batch" => "required",
            "qty" => "required|numeric|min:0",
            "accepted" => "required|numeric|min:0",
            "rejected" => "required|numeric|min:0",
            "code" => "required|unique:productions",
        ]);
        Production::create($validated);
        return redirect()->back()->with("success", "Production has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Production $production)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $production = Production::findOrFail($id);
        $saleStatusId = ProductStatus::where('name', 'Sale')->value('id');
        $products = Product::where('product_status_id', $saleStatusId)->get();
        $demands = Demand::all();
        $code = Production::generateCode();
        return view('production.edit', compact('setup', 'production', 'products', 'demands', 'code'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $production = Production::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            "product_id" => "required",
            "demand_id" => "required",
            "qty" => "required|numeric|min:0",
            "accepted" => "required|numeric|min:0",
            "rejected" => "required|numeric|min:0",
            'code' => 'required|unique:productions,code,' . $production->id,
            "batch" => "required",
        ]);

        // Update data pengguna
        $production->update($validated);

        return redirect()->back()->with("success", "Production has been updated");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Production::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Production has been deleted");
    }
}
