<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Demand extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function demand_items()
    {
        return $this->hasMany(DemandItem::class);
    }

    protected static function booted()
    {
        static::created(function ($demand) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Demand '{$demand->code}' was created.",
            ]);
        });

        static::updated(function ($demand) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Demand '{$demand->code}' was updated.",
            ]);
        });

        static::deleted(function ($demand) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Demand '{$demand->code}' was deleted.",
            ]);
        });

        static::saving(function ($demand) {
            // Check if the model is being updated
            if ($demand->exists) {
                // Handle activity log creation for update
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'description' => "Demand '{$demand->code}' was updated.",
                ]);
            } else {
                // Handle activity log creation for creation
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'description' => "Demand '{$demand->code}' was created.",
                ]);
            }
        });
    }

    public static function generateCode()
    {
        $date = now()->format('Y-m-d');
        $datePrefix = now()->format('Ymd');
        $prefix = "DMD-{$datePrefix}-";

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
