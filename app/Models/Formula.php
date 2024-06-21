<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Formula extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function raw_material()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    protected static function booted()
    {
        static::created(function ($formula) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Formula for'{$formula->product->name}' was created.",
            ]);
        });

        static::updated(function ($formula) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Formula for'{$formula->product->name}' was updated.",
            ]);
        });

        static::deleted(function ($formula) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Formula for'{$formula->product->name}' was deleted.",
            ]);
        });
    }
}
