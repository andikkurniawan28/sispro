<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quality extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($quality) {
            if($quality->type == "Quantitative") {
                $type_data = "FLOAT";
            }
            else {
                $type_data = "VARCHAR(255)";
            }
            $column_name = str_replace(' ', '_', $quality->name);
            $alter_query = "ALTER TABLE production_qualities ADD COLUMN `{$column_name}` {$type_data} NULL";
            DB::statement($alter_query);
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Quality '{$quality->name}' was created.",
            ]);
        });

        static::updated(function ($quality) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Quality '{$quality->name}' was updated.",
            ]);
        });

        static::deleted(function ($quality) {
            $column_name = str_replace(' ', '_', $quality->name);
            $alter_query = "ALTER TABLE production_qualities DROP COLUMN `{$column_name}`";
            DB::statement($alter_query);
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Quality '{$quality->name}' was deleted.",
            ]);
        });
    }
}
