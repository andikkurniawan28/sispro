<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($product_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Category '{$product_category->name}' was created.",
            ]);
        });

        static::updated(function ($product_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Category '{$product_category->name}' was updated.",
            ]);
        });

        static::deleted(function ($product_category) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Product Category '{$product_category->name}' was deleted.",
            ]);
        });
    }
}
