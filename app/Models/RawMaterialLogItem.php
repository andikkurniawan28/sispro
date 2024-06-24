<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialLogItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function raw_material_log()
    {
        return $this->belongsTo(RawMaterialLog::class);
    }

    public function raw_material()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
