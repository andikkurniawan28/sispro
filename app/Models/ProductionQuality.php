<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionQuality extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    protected static function booted()
    {
        static::created(function ($production_quality) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Production Quality for '{$production_quality->production->code}' was created.",
            ]);
        });

        static::updated(function ($production_quality) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Production Quality for '{$production_quality->production->code}' was updated.",
            ]);
        });

        static::deleted(function ($production_quality) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Production Quality for '{$production_quality->production->code}' was deleted.",
            ]);
        });
    }
}
