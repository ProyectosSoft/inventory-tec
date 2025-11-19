<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceAssignmentItem extends Model
{
    use HasFactory;

    protected $table = 'device_assignment_items';

    protected $fillable = [
        'assignment_id',
        'device_id',
        'specs',
        'notes',
    ];

    protected $casts = [
        'specs' => 'array',
    ];

    /**
     * 🔗 Cada ítem pertenece a una asignación (cabecera)
     */
    public function assignment()
    {
        return $this->belongsTo(DeviceAssignment::class, 'assignment_id');
    }

    /**
     * 🔗 Cada ítem pertenece a un dispositivo real
     */
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    /**
     * 🔍 Scope para obtener ítems activos (si en futuro se maneja devolución por ítem)
     */
    public function scopeActive($query)
    {
        return $query->whereHas('assignment', function ($q) {
            $q->whereNull('returned_at');
        });
    }
}
