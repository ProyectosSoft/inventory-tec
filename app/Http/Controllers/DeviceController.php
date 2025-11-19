<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Company;
use App\Models\Device;
use App\Models\DeviceSpec;
use App\Models\DeviceType;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        // Filtros permitidos
        $filters = $request->only(['device_type_id', 'type', 'status', 'company_id', 'employee_id', 'q']);

        $devices = Device::query()
            ->with(['company', 'type', 'assignments.employee']) // 👈 antes era user
            ->when($filters['device_type_id'] ?? null, fn($q, $v) => $q->where('device_type_id', $v))
            ->when(
                $filters['type'] ?? null,
                fn($q, $v) =>
                $q->whereHas('type', fn($tq) => $tq->where('key', $v))
            )
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['company_id'] ?? null, fn($q, $v) => $q->where('company_id', $v))

            // 👇 filtramos por empleado actual (reemplaza user_id → employee_id)
            ->when($filters['employee_id'] ?? null, function ($q, $v) {
                $q->whereHas('assignments', fn($qa) => $qa->where('employee_id', $v)->whereNull('returned_at'));
            })

            ->when($filters['q'] ?? null, function ($q, $v) {
                $vv = "%{$v}%";
                $q->where(function ($qq) use ($vv) {
                    $qq->where('asset_tag', 'like', $vv)
                        ->orWhere('notes', 'like', $vv)
                        ->orWhereHas(
                            'type',
                            fn($t) =>
                            $t->where('name', 'like', $vv)->orWhere('key', 'like', $vv)
                        );
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $companies = Company::orderBy('name')->get(['id', 'name']);
        $types     = DeviceType::orderBy('name')->get(['id', 'key', 'name']);
        $employees = Employee::orderBy('first_name')->get(['id', 'first_name', 'last_name']);

        return view('devices.index', [
            'devices'   => $devices,
            'companies' => $companies,
            'types'     => $types,
            'employees' => $employees,
            'filters'   => $filters,
        ]);
    }

    public function create()
    {
        $companies  = Company::orderBy('name')->get(['id', 'name']);
        $types      = DeviceType::orderBy('name')->get(['id', 'key', 'name', 'schema']);

        // Si no viene nada en la request, toma el primer tipo por defecto
        $defaultType = $this->resolveTypeFromRequest(request(), $types) ?? $types->first();

        // ✅ Decodificación robusta (soporta cast doble o string puro)
        $schema = ['groups' => []];

        if ($defaultType && $defaultType->schema) {
            $raw = $defaultType->schema;

            // Si ya es array (por el cast), intenta decodificar el contenido de groups
            if (is_array($raw)) {
                if (isset($raw['groups']) && is_string($raw['groups'])) {
                    $decoded = json_decode($raw['groups'], true);
                    $schema = ['groups' => $decoded ?? []];
                } else {
                    $schema = $raw;
                }
            }
            // Si sigue siendo string JSON plano
            elseif (is_string($raw)) {
                $decoded = json_decode($raw, true);
                $schema = is_array($decoded) ? $decoded : ['groups' => []];
            }
        }
        // dd($schema);
        return view('devices.create', [
            'companies'    => $companies,
            'types'        => $types,
            'defaultType'  => $defaultType,
            'schema'       => $schema,
        ]);
    }


    public function store(StoreDeviceRequest $request)
    {
        $type = $this->resolveTypeFromRequest($request);
        if (!$type) {
            return back()->withErrors(['type' => 'Tipo de dispositivo inválido'])->withInput();
        }

        // Validación dinámica de specs según schema del tipo
        $request->validate($this->buildSpecRules($type));

        // Crear dispositivo (solo campos vigentes)
        $device = Device::create([
            'company_id'      => $request->integer('company_id'),
            'device_type_id'  => $type->id,
            'status'          => $request->input('status', 'active'),
            'asset_tag'       => $request->input('asset_tag'),
            'purchase_date'   => $request->date('purchase_date'),
            'warranty_months' => $request->integer('warranty_months'),
            'notes'           => $request->input('notes'),
        ]);

        // Guardar specs dinámicas
        $this->saveSpecs($device, $request->input('specs', []));

        return redirect()->route('devices.show', $device)->with('ok', 'Dispositivo creado.');
    }

    public function edit(Device $device)
    {
        // 🔹 Cargar relaciones necesarias
        $device->load(['specs', 'company', 'type']);

        // 🔹 Construir arreglo plano (clave → valor)
        $device->specs_flat = $device->specs->pluck('value', 'key')->toArray();
        // dd($device->specs_flat);

        // (no agregues closure aquí)

        if (config('app.debug')) {
            // \Log::info('Specs flat:', $device->specs_flat);
        }
        // 🔹 Retornar la vista con toda la info necesaria
        return view('devices.edit', [
            'device'      => $device,
            'companies'   => Company::orderBy('name')->get(['id', 'name']),
            'users'       => User::orderBy('name')->get(['id', 'name']),
            'types'       => DeviceType::orderBy('name')->get(['id', 'key', 'name', 'schema']),
            'defaultType' => $device->type,
            'schema'      => $device->type->schema ?? ['groups' => []],
        ]);
    }





    public function update(UpdateDeviceRequest $request, Device $device)
    {

        $type = $this->resolveTypeFromRequest($request) ?? $device->type;
        if (!$type) {
            return back()->withErrors(['type' => 'Tipo de dispositivo inválido'])->withInput();
        }

        $device->update([
            'company_id'      => $request->integer('company_id', $device->company_id),
            'device_type_id'  => $type->id,
            'status'          => $request->input('status', $device->status),
            'asset_tag'       => $request->input('asset_tag', $device->asset_tag),
            'purchase_date' => $request->filled('purchase_date')
                ? \Carbon\Carbon::parse($request->input('purchase_date'))
                : $device->purchase_date,

            'warranty_months' => $request->integer('warranty_months', $device->warranty_months),
            'notes'           => $request->input('notes', $device->notes),
        ]);

        $device->specs()->delete();

        $this->saveSpecs($device, $request->input('specs', []));

        return redirect()->route('devices.show', $device)->with('ok', 'Dispositivo actualizado.');
    }

    public function show(Device $device)
    {
        $device->load(['company', 'type', 'specs']);

        $schema  = $device->type?->schema ?? ['groups' => []];
        $specMap = $device->specs->pluck('value', 'key')->toArray();
        

        return view('devices.show', [
            'device'  => $device,
            'schema'  => $schema,
            'specMap' => $specMap,
            'type'    => $device->type,
        ]);
    }

    /* ===========================
     * Helpers privados
     * ===========================
     */

    /**
     * Resolver DeviceType desde el request (acepta device_type_id o type (key)).
     */
    private function resolveTypeFromRequest(Request $request, $prefetched = null): ?DeviceType
    {
        if ($id = $request->input('device_type_id')) {
            return ($prefetched ? $prefetched->firstWhere('id', (int)$id) : DeviceType::find((int)$id));
        }
        if ($key = $request->input('type')) {
            return ($prefetched ? $prefetched->firstWhere('key', $key) : DeviceType::where('key', $key)->first());
        }
        return null;
    }

    /**
     * Guardar specs desde array anidado.
     */
    private function saveSpecs(Device $device, array $specsInput): void
    {
        $flat       = $this->flattenSpecs($specsInput);
        $normalized = $this->normalizeSlots($flat);

        $rows = [];
        $now  = now();
        foreach ($normalized as $k => $v) {
            if ($v === null || $v === '') continue;
            $rows[] = [
                'device_id'  => $device->id,
                'key'        => $k,
                'value'      => is_array($v) ? json_encode($v) : (string)$v,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        if ($rows) {
            DeviceSpec::insert($rows);
        }
    }

    /**
     * Aplana un array anidado en claves dot-notation.
     */
    private function flattenSpecs(array $arr, string $prefix = ''): array
    {
        $out = [];
        foreach ($arr as $k => $v) {
            $key = $prefix ? "{$prefix}.{$k}" : $k;
            if (is_array($v)) {
                $out += $this->flattenSpecs($v, $key);
            } else {
                $out[$key] = $v;
            }
        }
        return $out;
    }

    /**
     * Normaliza patrones de slots repetibles: ram.slots.0.type → ram.1.type
     */
    private function normalizeSlots(array $flat): array
    {
        $normalized = [];
        foreach ($flat as $k => $v) {
            if (preg_match('/^ram\.slots\.(\d+)\.(.+)$/', $k, $m)) {
                $idx = (int)$m[1] + 1;
                $normalized["ram.$idx." . $m[2]] = $v;
            } else {
                $normalized[$k] = $v;
            }
        }
        return $normalized;
    }

    /**
     * Construye reglas de validación para specs a partir del schema JSON del DeviceType.
     */
    private function buildSpecRules(DeviceType $type): array
    {
        $rules  = [];
        $schema = $type->schema ?? [];

        foreach (($schema['groups'] ?? []) as $group) {
            foreach (($group['fields'] ?? []) as $field) {

                // Caso "grupo repetible" con metadatos en $group['repeatable']
                if (!empty($group['repeatable']) && !empty($field['key'])) {
                    // Si el diseño que usas marca repeatable a nivel de grupo:
                    // base: specs.{repeatable.key or derived}.*
                    $repKey = $group['repeatable']['key'] ?? ($field['key'] ?? null);
                    if ($repKey) {
                        $path = 'specs.' . $repKey . '.*.' . ($field['key'] ?? 'value');
                        $rules[$path] = $field['rules'] ?? 'nullable';
                    }
                    continue;
                }

                // Caso "campo repetible" definido en el propio field (tu versión anterior)
                if (!empty($field['repeatable']) && !empty($field['fields']) && isset($field['key'])) {
                    $base = 'specs.' . $field['key'] . '.*';
                    foreach ($field['fields'] as $child) {
                        $rules[$base . '.' . $child['key']] = $child['rules'] ?? 'nullable';
                    }
                    continue;
                }

                // Campo simple
                if (isset($field['key'])) {
                    $rules['specs.' . $field['key']] = $field['rules'] ?? 'nullable';
                }
            }
        }
        return $rules;
    }

    public function history(Device $device)
    {
        $history = $device->assignments()
            ->with('employee')
            ->orderByDesc('assigned_at')
            ->get()
            ->map(function ($a) {
                return [
                    'employee' => $a->employee ? $a->employee->full_name : '—',
                    'assigned_at' => optional($a->assigned_at)->format('Y-m-d'),
                    'returned_at' => optional($a->returned_at)->format('Y-m-d'),
                    'status' => $a->returned_at ? 'Devuelto' : 'Activo',
                ];
            });

        return response()->json($history);
    }
}
