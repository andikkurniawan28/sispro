<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Quality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QualityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $qualities = Quality::all();
        return view('quality.index', compact('setup', 'qualities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('quality.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:qualities",
            "type" => "required",
        ]);
        Quality::create($validated);
        return redirect()->back()->with("success", "Quality has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Quality $quality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $quality = Quality::findOrFail($id);
        return view('quality.edit', compact('setup', 'quality'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $quality = Quality::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:qualities,name,' . $quality->id,
            "type" => "required",
        ]);
        self::updateColumn($quality, $request);
        $quality->update($validated);
        return redirect()->back()->with("success", "Quality has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $quality = Quality::findOrFail($id);
        $quality->delete();
        return redirect()->back()->with("success", "Quality has been deleted");
    }

    public static function updateColumn($quality, $request)
    {
        if($request->type == "Quantitative") {
            $type_data = "FLOAT";
        }
        else {
            $type_data = "VARCHAR(255)";
        }
        $old_column_name = str_replace(' ', '_', $quality->name);
        $new_column_name = str_replace(' ', '_', $request->name);
        $rename_query = "ALTER TABLE production_qualities CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` {$type_data} NULL";
        DB::statement($rename_query);
    }
}
