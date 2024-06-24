<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Models\RawMaterialWarehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RawMaterialLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function raw_material_warehouse()
    {
        return $this->belongsTo(RawMaterialWarehouse::class);
    }

    public function raw_material_log_items()
    {
        return $this->hasMany(RawMaterialLogItem::class);
    }

    protected static function booted()
    {
        static::created(function ($raw_material_log) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Raw Material Log '{$raw_material_log->code}' was created.",
            ]);
        });

        static::updated(function ($raw_material_log) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Raw Material Log '{$raw_material_log->code}' was updated.",
            ]);
        });

        static::deleted(function ($raw_material_log) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Raw Material Log '{$raw_material_log->code}' was deleted.",
            ]);
        });

        static::saving(function ($raw_material_log) {
            if ($raw_material_log->exists) {
                // Handle activity log creation for update
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'description' => "Raw Material Log '{$raw_material_log->code}' was updated.",
                ]);
            }
        });
    }

    public static function generateCode()
    {
        $date = now()->format('Y-m-d');
        $datePrefix = now()->format('Ymd');
        $prefix = "RML-{$datePrefix}-";

        // Get the latest sequence number for the current date
        $maxCode = self::where(DB::raw('DATE(created_at)'), $date)
            ->orderBy('code', 'desc')
            ->pluck('code')
            ->first();

        $newSequenceNumber = 1;

        if ($maxCode) {
            $lastSequenceNumber = (int) substr($maxCode, strrpos($maxCode, '-') + 1);
            $newSequenceNumber = $lastSequenceNumber + 1;
        }

        $newCode = $prefix . str_pad($newSequenceNumber, 6, '0', STR_PAD_LEFT);

        return $newCode;
    }
}
