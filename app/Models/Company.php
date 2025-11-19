<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// app/Models/Company.php
class Company extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'legal_name',
        'tax_id',
        'code',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'country',
        'status',
    ];
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
