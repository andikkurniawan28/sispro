<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLogItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product_log()
    {
        return $this->belongsTo(ProductLog::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
