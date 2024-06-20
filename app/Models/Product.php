<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function product_status()
    {
        return $this->belongsTo(ProductStatus::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    protected static function booted()
    {
        static::created(function ($product) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product '{$product->name}' was created.",
            ]);
        });

        static::updated(function ($product) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product '{$product->name}' was updated.",
            ]);
        });

        static::deleted(function ($product) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product '{$product->name}' was deleted.",
            ]);
        });
    }
}
