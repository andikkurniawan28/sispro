<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RawMaterial extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function raw_material_category()
    {
        return $this->belongsTo(RawMaterialCategory::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    protected static function booted()
    {
        static::created(function ($raw_material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Raw Material '{$raw_material->name}' was created.",
            ]);
        });

        static::updated(function ($raw_material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Raw Material '{$raw_material->name}' was updated.",
            ]);
        });

        static::deleted(function ($raw_material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Raw Material '{$raw_material->name}' was deleted.",
            ]);
        });
    }
}
