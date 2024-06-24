<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Demand;
use App\Models\Product;
use App\Models\Quality;
use App\Models\Production;
use Illuminate\Http\Request;
use App\Models\ProductStatus;
use Yajra\DataTables\DataTables;
use App\Models\ProductionQuality;

class ProductionQualityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        $qualities = Quality::all();
        if ($request->ajax()) {
            $data = ProductionQuality::with('production')
                ->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('production_id', function ($row) {
                    return $row->production_id ? $row->production->code : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('production_quality.edit', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>
                    </div>
                ';
                })
                ->addColumn('qualities', function ($row) use ($qualities) {
                    $quality_values = '';
                    foreach ($qualities as $quality) {
                        $quality_name = str_replace(' ', '_', $quality->name);
                        if($quality->type == "Quantitative")
                        {
                            if (!empty($row->$quality_name)) {
                                $quality_values .= '<li>' . $quality->name . ' = ' . $row->$quality_name . '</li>';
                            }
                        }
                        else if($quality->type == "Qualitative") {
                            $quality_values .= '<li>' . $quality->name . ' = ' . $row->$quality_name . '</li>';
                        }
                    }
                    return $quality_values ? '<ul>' . $quality_values . '</ul>' : '';
                })
                ->rawColumns(['action', 'production_id', 'qualities'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
        }
        return view('production_quality.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $qualities = Quality::all();
        $productions = Production::whereDoesntHave('quality')->get();
        return view('production_quality.create', compact('setup', 'qualities', 'productions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validation_rules = [
            'production_id' => 'required|exists:productions,id',
            'qualities.*' => 'nullable|string',
            'status' => 'required',
        ];

        // Ambil produksi berdasarkan ID
        $production = Production::findOrFail($request->production_id);

        // Ambil semua qualities dari database
        $qualities = Quality::all();

        // Simpan setiap kualitas yang diinputkan
        foreach ($qualities as $quality) {
            $quality_name = str_replace(' ', '_', $quality->name);
            if($quality->type == "Quantitative"){
                $validation_rules[$quality_name] = "nullable|numeric";
            } elseif($quality->type == "Qualitative") {
                $validation_rules[$quality_name] = "nullable";
            }
        }

        $validated = $request->validate($validation_rules);
        ProductionQuality::create($validated);

        // Redirect ke halaman indeks produksi dengan pesan sukses
        return redirect()->back()->with("success", "Production Quality has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionQuality $production_quality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $qualities = Quality::all();
        $productions = Production::whereDoesntHave('quality')->get();
        $production_quality = ProductionQuality::findOrFail($id);
        return view('production_quality.edit', compact('setup', 'qualities', 'productions', 'productions', 'production_quality'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari request
        $request->validate([
            'production_id' => 'required|exists:productions,id',
            'status' => 'required',
            // Lakukan validasi tambahan untuk setiap field kualitas jika diperlukan
        ]);

        // Temukan production_quality berdasarkan ID
        $productionQuality = ProductionQuality::findOrFail($id);

        // Update data production_quality dengan data yang diterima dari request
        $productionQuality->production_id = $request->production_id;

        // Ambil semua kualitas yang tersedia
        $qualities = Quality::all();
        foreach ($qualities as $quality) {
            $fieldName = str_replace(" ", "_", $quality->name);
            if ($request->has($fieldName)) {
                $productionQuality->{$fieldName} = $request->input($fieldName);
            }
        }

        // Simpan perubahan
        $productionQuality->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->back()->with("success", "Production Quality has been updated");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ProductionQuality::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Production Quality has been deleted");
    }
}
