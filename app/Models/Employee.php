<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'code',
        'first_name',
        'last_name',
        'document_id',
        'email',
        'phone',
        'position',
        'site',
        'status',
    ];

    protected $appends = ['full_name'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    public function assignments()
    {
        return $this->hasMany(\App\Models\DeviceAssignment::class);
    }

    // public function currentDevices()
    // {
    //     return $this->hasManyThrough(
    //         \App\Models\Device::class,
    //         \App\Models\DeviceAssignment::class,
    //         'employee_id', // clave foránea en device_assignments
    //         'id',          // clave local en devices
    //         'id',          // clave local en employees
    //         'device_id'    // clave foránea en assignments
    //     )->whereNull('device_assignments.returned_at'); // solo activos
    // }

    public function currentDevices()
    {
        return $this->hasManyThrough(
            Device::class,                 // Modelo final
            DeviceAssignmentItem::class,   // Modelo intermedio
            'assignment_id',               // FK en DeviceAssignmentItem → assignment_id
            'id',                          // FK en Device → id
            'id',                          // Local key en Employee → id
            'device_id'                    // FK en DeviceAssignmentItem → device_id
        )
            ->whereHas('assignments', function ($q) {
                $q->whereColumn('device_assignment_items.assignment_id', 'device_assignments.id')
                    ->whereNull('device_assignments.returned_at');
            });
    }
}
