<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Setup;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\RawMaterialLog;
use Yajra\DataTables\DataTables;
use App\Models\RawMaterialLogItem;
use App\Models\RawMaterialWarehouse;
use Illuminate\Support\Facades\Validator;

class RawMaterialLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = RawMaterialLog::with('raw_material_log_items', 'raw_material_warehouse')
                ->latest()->get();
                return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('raw_material_log_item_list', function ($row) {
                    $raw_material_log_items = $row->raw_material_log_items;
                    $raw_material_log_itemList = '<ul>';

                    foreach ($raw_material_log_items as $raw_material_log_item) {
                        $raw_material = $raw_material_log_item->raw_material;
                        if ($raw_material) {
                            $raw_material_log_itemList .= '<li>' . $raw_material->code . ' : ' . $raw_material_log_item->qty . ' ' . $raw_material->unit->symbol . '</li>';
                        }
                    }

                    $raw_material_log_itemList .= '</ul>';
                    return $raw_material_log_itemList;
                })
                ->editColumn('raw_material_warehouse_id', function ($row) {
                    return $row->raw_material_warehouse_id ? $row->raw_material_warehouse->name : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('raw_material_log.edit', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>
                    </div>
                ';
                })
                // ->rawColumns(['action', 'raw_material_log_item_list', 'raw_materialion_list'])
                ->rawColumns(['action', 'raw_material_log_item_list'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
        }

        return view('raw_material_log.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $raw_materials = RawMaterial::all();
        $code = RawMaterialLog::generateCode();
        $raw_material_warehouses = RawMaterialWarehouse::all();
        return view('raw_material_log.create', compact('setup', 'raw_materials', 'code', 'raw_material_warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Custom validation rule to check if all raw_materials are unique
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:raw_material_logs',
            'raw_material_warehouse_id' => 'required',
            'status' => 'required',
            'raw_materials' => 'required|array',
            'quantities' => 'required|array',
            'raw_materials.*' => 'exists:raw_materials,id',
            'quantities.*' => 'numeric|min:1',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (count($request->raw_materials) !== count(array_unique($request->raw_materials))) {
                $validator->errors()->add('raw_materials', 'Each raw_material must be unique.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create raw_material_log
        $raw_material_log = RawMaterialLog::create([
            'code' => $request->code,
            'raw_material_warehouse_id' => $request->raw_material_warehouse_id,
            'status' => $request->status,
        ]);

        // Create raw_material_log items
        $raw_materials = $request->input('raw_materials', []);
        $quantities = $request->input('quantities', []);

        foreach ($raw_materials as $index => $raw_materialId) {
            RawMaterialLogItem::create([
                "raw_material_log_id" => $raw_material_log->id,
                "raw_material_id" => $raw_materialId,
                "qty" => $quantities[$index],
            ]);
        }

        return redirect()->route('raw_material_log.index')->with('success', 'Raw Material Log created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RawMaterialLog $raw_material_log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $raw_materials = RawMaterial::all();
        $raw_material_log = RawMaterialLog::findOrFail($id);
        $raw_material_warehouses = RawMaterialWarehouse::all();
        return view('raw_material_log.edit', compact('setup', 'raw_materials', 'raw_material_log', 'raw_material_warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'raw_material_warehouse_id' => 'required',
            'status' => 'required',
            'raw_materials' => 'required|array',
            'quantities' => 'required|array',
            'raw_materials.*' => 'exists:raw_materials,id',
            'quantities.*' => 'numeric|min:1',
        ]);

        $raw_material_log = RawMaterialLog::findOrFail($id);

        // Update or create raw_material_log items
        foreach ($request->raw_materials as $index => $raw_materialId) {
            $raw_material_logItem = $raw_material_log->raw_material_log_items()->firstOrNew([
                'raw_material_id' => $raw_materialId,
            ]);
            $raw_material_logItem->qty = $request->quantities[$index];
            $raw_material_logItem->save();
        }

        // Update raw_material_log details
        $raw_material_log->update([
            'raw_material_warehouse_id' => $request->raw_material_warehouse_id,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Raw Material Log updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        RawMaterialLog::findOrFail($id)->delete();
        return redirect()->back()->with("success", "RawMaterialLog has been deleted");
    }
}
