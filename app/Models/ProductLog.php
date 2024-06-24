<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product_warehouse()
    {
        return $this->belongsTo(ProductWarehouse::class);
    }

    public function product_log_items()
    {
        return $this->hasMany(ProductLogItem::class);
    }

    protected static function booted()
    {
        static::created(function ($product_log) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Log '{$product_log->code}' was created.",
            ]);
        });

        static::updated(function ($product_log) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Log '{$product_log->code}' was updated.",
            ]);
        });

        static::deleted(function ($product_log) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Log '{$product_log->code}' was deleted.",
            ]);
        });

        static::saving(function ($product_log) {
            if ($product_log->exists) {
                // Handle activity log creation for update
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'description' => "Product Log '{$product_log->code}' was updated.",
                ]);
            }
        });
    }

    public static function generateCode()
    {
        $date = now()->format('Y-m-d');
        $datePrefix = now()->format('Ymd');
        $prefix = "PRL-{$datePrefix}-";

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
