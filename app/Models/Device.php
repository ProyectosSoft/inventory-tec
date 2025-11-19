<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;         // 👈 DB::transaction
use App\Models\DeviceCounters;             // 👈 Contador
use App\Models\Company;                   // 👈 Para findOrFail
use App\Models\DeviceType;                // 👈 Para findOrFail

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'device_type_id', // ← tipo desde catálogo device_types
        'status',
        'asset_tag',
        'purchase_date',
        'warranty_months',
        'notes',
    ];

    protected $casts = [
        'purchase_date'   => 'date',
        'warranty_months' => 'integer',
    ];

    /* ─────────── Relaciones ─────────── */

    /** Device ∞ ── 1 Company */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /** Device ∞ ── 1 DeviceType */
    public function type()
    {
        return $this->belongsTo(DeviceType::class, 'device_type_id');
    }

    /** Device 1 ── ∞ Specs (si usas especificaciones dinámicas) */
    public function specs()
    {
        return $this->hasMany(DeviceSpec::class);
    }

    /** Device 1 ── ∞ Assignments (historial de asignaciones a empleados) */
    // public function assignments()
    // {
    //     return $this->hasMany(DeviceAssignment::class);
    // }
    public function assignmentItems()
    {
        return $this->hasMany(DeviceAssignmentItem::class, 'device_id');
    }

    public function assignments()
    {
        return $this->hasManyThrough(
            DeviceAssignment::class,
            DeviceAssignmentItem::class,
            'device_id',       // FK en DeviceAssignmentItem ¬ device_id
            'id',              // PK en DeviceAssignment
            'id',              // PK en Device
            'assignment_id'    // FK en DeviceAssignmentItem ¬ assignment_id
        );
    }


    public function currentAssignment()
    {
        return $this->hasOne(DeviceAssignment::class)->latestOfMany()->whereNull('returned_at');
    }


    /* ─────────── Scopes ─────────── */

    public function scopeFilters($query, array $filters)
    {
        return $query
            // antes: where('type', $v) → ahora device_type_id o por nombre/clave del tipo
            ->when($filters['device_type_id'] ?? null, fn($q, $v) => $q->where('device_type_id', $v))
            ->when(
                $filters['type_key'] ?? null,
                fn($q, $v) =>
                $q->whereHas('type', fn($t) => $t->where('key', $v))
            )
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['company_id'] ?? null, fn($q, $v) => $q->where('company_id', $v))
            ->when($filters['q'] ?? null, function ($q, $v) {
                $q->where(function ($qq) use ($v) {
                    $qq->where('asset_tag', 'like', "%{$v}%")
                        ->orWhere('notes', 'like', "%{$v}%")
                        ->orWhereHas(
                            'type',
                            fn($t) =>
                            $t->where('name', 'like', "%{$v}%")
                                ->orWhere('key', 'like', "%{$v}%")
                        );
                });
            });
    }

    /* ─────────── Accessors útiles ─────────── */

    /** Fin de garantía (purchase_date + warranty_months) */
    public function getWarrantyEndsAtAttribute(): ?Carbon
    {
        if (!$this->purchase_date || !$this->warranty_months) return null;
        return (clone $this->purchase_date)->addMonths($this->warranty_months);
    }

    public function getInWarrantyAttribute(): bool
    {
        $end = $this->warranty_ends_at;
        return $end ? now()->lte($end) : false;
    }

    /** Mapa plano de specs: ['ram.slots.0.size_gb' => '16', ...] */
    public function getSpecsMapAttribute(): array
    {
        return $this->specs()->pluck('value', 'key')->toArray();
    }

    /** Árbol anidado de specs */
    public function getSpecsTreeAttribute(): array
    {
        $tree = [];
        foreach ($this->specs_map as $key => $value) {
            Arr::set($tree, $key, $value);
        }
        return $tree;
    }

    // app/Models/Device.php (añade esto dentro de la clase)
    protected static function booted()
    {
        static::creating(function (Device $device) {
            if (empty($device->asset_tag)) {
                $device->asset_tag = static::generateAssetTag($device->company_id, $device->device_type_id);
            }
        });
    }

    /** Genera EC_LP_0001 según company/device_type y contador transaccional */
    public static function generateAssetTag(int $companyId, int $deviceTypeId): string
    {
        return DB::transaction(function () use ($companyId, $deviceTypeId) {
            // 1) levantar/crear contador con lock
            $counter = device_counters::where([
                'company_id' => $companyId,
                'device_type_id' => $deviceTypeId,
            ])->lockForUpdate()->first();

            if (!$counter) {
                $counter = device_counters::create([
                    'company_id' => $companyId,
                    'device_type_id' => $deviceTypeId,
                    'last_seq' => 0,
                ]);
                // Lock de nuevo por seguridad si tu motor lo requiere
                $counter->refresh();
            }

            // 2) siguiente número (1..10000)
            $next = $counter->last_seq + 1;
            if ($next > 10000) {
                throw new \RuntimeException('Rango de AssetTag agotado para esta empresa y tipo.');
            }

            // 3) prefijos
            $company = \App\Models\Company::findOrFail($companyId);
            $type    = \App\Models\DeviceType::findOrFail($deviceTypeId);

            $cCode = static::abbr($company->code ?: $company->name, 2);    // ej. EC
            $tCode = static::abbr($type->code ?? $type->key ?? $type->name, 2); // ej. LP

            $tag = sprintf('%s_%s_%04d', $cCode, $tCode, $next);

            // 4) persistir el contador
            $counter->update(['last_seq' => $next]);

            return $tag;
        });
    }

    /** Saca una abreviatura (2-3 letras) robusta de un texto si 'code' no existe */
    protected static function abbr(?string $text, int $len = 2): string
    {
        $text = strtoupper(preg_replace('/[^A-Za-z0-9]+/', '', (string)$text));
        if (strlen($text) >= $len) return substr($text, 0, $len);
        return str_pad($text, $len, 'X'); // fallback
    }

    /** Asignar a un empleado */
    // public function assignTo(\App\Models\Employee $employee, \Illuminate\Support\Carbon $date, ?string $notes = null)
    // {
    //     // Cierra asignaciones anteriores
    //     $this->assignments()->whereNull('returned_at')->update(['returned_at' => now()]);

    //     // Crea nueva
    //     $this->assignments()->create([
    //         'employee_id' => $employee->id,
    //         'assigned_at' => $date,
    //         'notes' => $notes,
    //     ]);

    //     if ($this->fillable && in_array('current_employee_id', $this->fillable)) {
    //         $this->forceFill(['current_employee_id' => $employee->id])->save();
    //     }

    //     return $this;
    // }
    public function assignTo(Employee $employee, $assignedAt = null, $notes = null)
    {
        $assignedAt = $assignedAt ?? now();

        // Cerrar asignaciones activas previas
        $this->assignments()->whereNull('returned_at')->update([
            'returned_at' => now(),
        ]);

        // Crear nueva asignación
        $assignment = $this->assignments()->create([
            'employee_id' => $employee->id,
            'assigned_at' => $assignedAt,
            'notes'       => $notes,
        ]);

        // Actualizar referencia al empleado actual
        $this->update(['current_employee_id' => $employee->id]);

        return $assignment; // <- ✅ devolvemos la nueva asignación
    }

    // app/Models/Device.php

    // app/Models/Device.php

    public function getSpecsFlatAttribute()
    {
        if (!$this->relationLoaded('specs')) {
            $this->load('specs');
        }

        return $this->specs
            ->mapWithKeys(fn($spec) => [$spec->key => $spec->value])
            ->toArray();
    }

    // public function getSpecValue($key)
    // {
    //     foreach ($this->specs_flat as $flatKey => $value) {
    //         if ($flatKey === $key || str_ends_with($flatKey, ".$key")) {
    //             return $value;
    //         }
    //     }
    //     return null;
    // }
    // public function getSpecValue($key)
    // {
    //     foreach ($this->specs_flat as $flatKey => $value) {
    //         // Aceptar coincidencia exacta o terminada en ".$key"
    //         if ($flatKey === $key || str_ends_with($flatKey, ".$key")) {
    //             return $value;
    //         }

    //         // ✅ NUEVO: soportar claves con prefijo ("caracteristicas.brand")
    //         if (preg_match("/(^|\\.){$key}$/", $flatKey)) {
    //             return $value;
    //         }
    //     }
    //     return null;
    // }
    public function getSpecValue($key)
    {
        // Si no existe el arreglo plano, construirlo en el momento
        if (!isset($this->specs_flat) || !is_array($this->specs_flat)) {
            $this->specs_flat = $this->specs
                ? $this->specs->pluck('value', 'key')->toArray()
                : [];
        }

        foreach ($this->specs_flat as $flatKey => $value) {
            // Coincidencia exacta o terminada en ".$key"
            if ($flatKey === $key || str_ends_with($flatKey, ".$key")) {
                return $value;
            }

            // Compatibilidad con prefijos (e.g. caracteristicas.brand)
            if (preg_match("/(^|\\.){$key}$/", $flatKey)) {
                return $value;
            }
        }

        return null;
    }
}
