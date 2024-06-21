<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Formula;
use App\Models\Product;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FormulaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Product::with('formula')
                ->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('formula_list', function ($row) {
                    $formulas = $row->formula;
                    $formulaList = '';

                    foreach ($formulas as $formula) {
                        $raw_material = $formula->raw_material; // Akses relasi belongsTo
                        if ($raw_material) {
                            $formulaList .= '<li>' . $raw_material->code . ': ' . $formula->qty . ' ' . $raw_material->unit->symbol . '</li>';
                        }
                    }

                    return $formulaList;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('formula.adjust', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Adjust</a>
                    </div>
                ';
                })
                ->rawColumns(['action', 'formula_list'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
        }

        return view('formula.index', compact('setup'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function adjust($product_id)
    {
        $setup = Setup::init();

        // Mengambil data produk berdasarkan ID
        $product = Product::findOrFail($product_id);

        // Mengambil semua data raw material
        $raw_materials = RawMaterial::with('unit')->get();

        // Mengambil formula yang sudah ada untuk produk tersebut
        $formula = Formula::where('product_id', $product_id)->get();

        // Menampilkan view dengan data yang diperlukan
        return view('formula.adjust', compact('setup', 'product', 'raw_materials', 'formula'));
    }

    public function update(Request $request, $product_id)
    {
        // Validate the incoming request data
        $request->validate([
            'raw_materials' => 'required|array',
            'raw_materials.*' => 'distinct', // Ensure all raw_material_ids are distinct
            'quantities' => 'required|array',
            'quantities.*' => 'required|numeric|min:0', // Validate quantities
        ]);

        // Retrieve the product instance
        $product = Product::findOrFail($product_id);
        Formula::where("product_id", $product_id)->delete();

        // Process the submitted raw materials and quantities
        $raw_materials = $request->input('raw_materials');
        $quantities = $request->input('quantities');

        // Example: Ensure no duplicate raw_material_id
        if (count($raw_materials) !== count(array_unique($raw_materials))) {
            // If duplicates found, redirect back with error message
            return redirect()->back()->withErrors(['error' => 'Duplicate raw material IDs found. Please ensure each raw material is selected only once.']);
        }

        // Save the raw materials and quantities to the database or perform other actions
        // Example:
        foreach ($raw_materials as $index => $raw_material_id) {
            Formula::insert([
                "product_id" => $product_id,
                "raw_material_id" => $raw_material_id,
                "qty" => $quantities[$index],
            ]);
        }

        // Redirect to previous route or perform other actions after successful save
        return redirect()->back()->with('success', 'Formula updated successfully.');
    }

}
