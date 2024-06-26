<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Setup;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\RawMaterialCategory;
use Yajra\DataTables\DataTables;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = RawMaterial::with('raw_material_category', 'unit')
                ->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('raw_material_category_id', function ($row) {
                    return $row->raw_material_category_id ? $row->raw_material_category->name : 'N/A';
                })
                ->editColumn('unit_id', function ($row) {
                    return $row->unit_id ? $row->unit->name : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('raw_material.edit', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>
                    </div>
                ';
                })
                ->rawColumns(['action', 'raw_material_category_id', 'unit_id'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
        }
        return view('raw_material.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $raw_material_categories = RawMaterialCategory::all();
        $units = Unit::all();
        return view('raw_material.create', compact('setup', 'raw_material_categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "raw_material_category_id" => "required",
            "unit_id" => "required",
            "name" => "required|unique:raw_materials",
            "code" => "required|unique:raw_materials",
        ]);
        RawMaterial::create($validated);
        return redirect()->back()->with("success", "Raw Material has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(RawMaterial $raw_material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $raw_material = RawMaterial::findOrFail($id);
        $raw_material_categories = RawMaterialCategory::all();
        $units = Unit::all();
        return view('raw_material.edit', compact('setup', 'raw_material', 'raw_material_categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $raw_material = RawMaterial::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            "raw_material_category_id" => "required",
            "unit_id" => "required",
            'name' => 'required|unique:raw_materials,name,' . $raw_material->id,
            'code' => 'required|unique:raw_materials,code,' . $raw_material->id,
        ]);

        // Update data pengguna
        $raw_material->update($validated);

        return redirect()->back()->with("success", "Raw Material has been updated");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        RawMaterial::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Raw Material has been deleted");
    }
}
