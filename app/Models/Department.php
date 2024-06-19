<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function role()
    {
        return $this->hasMany(Role::class);
    }

    protected static function booted()
    {
        static::created(function ($department) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Department '{$department->name}' was created.",
            ]);
        });

        static::updated(function ($department) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Department '{$department->name}' was updated.",
            ]);
        });

        static::deleted(function ($department) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Department '{$department->name}' was deleted.",
            ]);
        });
    }
}
