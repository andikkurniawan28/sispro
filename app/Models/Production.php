<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Production extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function generateCode()
    {
        $date = now()->format('Y-m-d');
        $datePrefix = now()->format('Ymd');
        $prefix = "PRO-{$datePrefix}-";

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

    protected static function booted()
    {
        static::created(function ($production) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Production '{$production->code}' was created.",
            ]);
        });

        static::updated(function ($production) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Production '{$production->code}' was updated.",
            ]);
        });

        static::deleted(function ($production) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Production '{$production->code}' was deleted.",
            ]);
        });
    }
}
