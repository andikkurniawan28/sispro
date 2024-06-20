<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($product_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Status '{$product_status->name}' was created.",
            ]);
        });

        static::updated(function ($product_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Status '{$product_status->name}' was updated.",
            ]);
        });

        static::deleted(function ($product_status) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Status '{$product_status->name}' was deleted.",
            ]);
        });
    }
}
