<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'employee_id',
        'consecutive',
        'assigned_at',
        'returned_at',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    /**
     * 🔗 Relación: Asignación pertenece a una empresa
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * 🔗 Relación: Asignación pertenece a un empleado
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * 🔗 Relación: Asignación tiene muchos items (uno por cada dispositivo)
     */
    public function items()
    {
        return $this->hasMany(DeviceAssignmentItem::class, 'assignment_id');
    }

    /**
     * 🔍 Asignaciones activas (no devueltas)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('returned_at');
    }

    public function devices()
    {
        return $this->belongsToMany(
            Device::class,
            'device_assignment_items',
            'assignment_id',
            'device_id'
        );
    }
}
