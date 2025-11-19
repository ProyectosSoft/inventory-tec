<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    protected $fillable = ['key','name','schema','is_active'];

    protected $casts = [
        'schema' => 'array',
        'is_active' => 'boolean',
    ];
}
