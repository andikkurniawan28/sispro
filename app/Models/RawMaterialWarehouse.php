<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RawMaterialWarehouse extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($raw_material_warehouse) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Raw Material Warehouse '{$raw_material_warehouse->name}' was created.",
            ]);
            $column_name = str_replace(' ', '_', $raw_material_warehouse->name);
            $alter_query = "ALTER TABLE raw_materials ADD COLUMN `{$column_name}` FLOAT NULL";
            DB::statement($alter_query);
        });

        static::updated(function ($raw_material_warehouse) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Raw Material Warehouse '{$raw_material_warehouse->name}' was updated.",
            ]);
        });

        static::deleted(function ($raw_material_warehouse) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Raw Material Warehouse '{$raw_material_warehouse->name}' was deleted.",
            ]);
            $column_name = str_replace(' ', '_', $raw_material_warehouse->name);
            $alter_query = "ALTER TABLE raw_materials DROP COLUMN `{$column_name}`";
            DB::statement($alter_query);
        });
    }
}
