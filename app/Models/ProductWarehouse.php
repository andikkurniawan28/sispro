<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductWarehouse extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($product_warehouse) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Warehouse '{$product_warehouse->name}' was created.",
            ]);
            $column_name = str_replace(' ', '_', $product_warehouse->name);
            $alter_query = "ALTER TABLE products ADD COLUMN `{$column_name}` FLOAT NULL";
            DB::statement($alter_query);
        });

        static::updated(function ($product_warehouse) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Warehouse '{$product_warehouse->name}' was updated.",
            ]);
        });

        static::deleted(function ($product_warehouse) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Warehouse '{$product_warehouse->name}' was deleted.",
            ]);
            $column_name = str_replace(' ', '_', $product_warehouse->name);
            $alter_query = "ALTER TABLE products DROP COLUMN `{$column_name}`";
            DB::statement($alter_query);
        });
    }
}
