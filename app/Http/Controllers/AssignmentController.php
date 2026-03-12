<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Device,
    DeviceType,
    DeviceAssignment,
    DeviceAssignmentItem,
    Employee,
    Company
};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    // =================================================================
    // 📋 VISTAS DE LECTURA Y DETALLE
    // =================================================================

    /**
     * 📋 Listado general de asignaciones.
     */
    public function index(Request $request)
    {
        $companies = Company::orderBy('name')->get(['id', 'name']);
        $employees = Employee::orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'document_id']);

        $query = DeviceAssignment::with([
            'company',
            'employee',
            'items.device.type',
        ])
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        // 🔹 Filtro por empresa
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // 🔹 Filtro por empleado
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // 🔹 Filtro por estado
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('returned_at');
            } elseif ($request->status === 'returned') {
                $query->whereNotNull('returned_at');
            }
        }

        // 🔹 Búsqueda por empleado o TAG
        if ($request->filled('q')) {
            $term = $request->q;

            $query->where(function ($q) use ($term) {

                // Buscar por empleado
                $q->whereHas('employee', function ($qq) use ($term) {
                    $qq->where('first_name', 'ilike', "%$term%")
                        ->orWhere('last_name', 'ilike', "%$term%")
                        ->orWhere('document_id', 'ilike', "%$term%");
                });

                // Buscar por dispositivos asignados
                $q->orWhereHas('items.device', function ($qq) use ($term) {
                    $qq->where('asset_tag', 'ilike', "%$term%");
                });
            });
        }

        $assignments = $query->paginate(15)->withQueryString();
        return view('assignments.index', compact('assignments', 'companies', 'employees'));
    }

    /**
     * 👀 Mostrar detalle de una asignación.
     */
    public function show(DeviceAssignment $assignment)
    {
        $assignment->load([
            'company',
            'employee',
            'items.device.type',
        ]);

        return view('assignments.show', compact('assignment'));
    }

    /**
     * 🧾 Formulario de creación de nueva asignación.
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();
        $types     = DeviceType::orderBy('name')->get();

        return view('assignments.create', compact('companies', 'types'));
    }

    // =================================================================
    // 💾 ACCIONES DE CREACIÓN/ACTUALIZACIÓN
    // =================================================================

    /**
     * 💾 Guardar una nueva asignación (cabecera + items).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id'   => 'required|exists:companies,id',
            'employee_id'  => 'required|exists:employees,id',
            'assigned_at'  => 'required|date',
            'notes'        => 'nullable|string|max:500',
            'consecutive'  => 'required|string|max:50',
            'device_ids'   => 'required|string', // JSON
        ]);

        $deviceIds = json_decode($validated['device_ids'], true);

        if (!is_array($deviceIds) || count($deviceIds) === 0) {
            return back()->with('error', '⚠️ Debes agregar al menos un dispositivo.');
        }

        DB::beginTransaction();

        try {
            // 1️⃣ Crear CABECERA
            $assignment = DeviceAssignment::create([
                'company_id'  => $validated['company_id'],
                'employee_id' => $validated['employee_id'],
                'assigned_at' => $validated['assigned_at'],
                'consecutive' => $validated['consecutive'],
                'notes'       => $validated['notes'] ?? null,
            ]);

            // 2️⃣ Crear DETALLE de items
            foreach ($deviceIds as $deviceId) {
                DeviceAssignmentItem::create([
                    'assignment_id' => $assignment->id,
                    'device_id'     => $deviceId,
                ]);
            }

            DB::commit();
            return redirect()->route('assignments.index')
                ->with('ok', "✅ Asignación creada con " . count($deviceIds) . " dispositivos.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    }

    /**
     * ♻️ Marcar una asignación como devuelta.
     */
    public function return(DeviceAssignment $assignment)
    {
        $assignment->update(['returned_at' => now()]);

        return redirect()->route('assignments.index')
            ->with('ok', '📦 Asignación devuelta correctamente.');
    }

    // =================================================================
    // 📡 RUTAS AJAX
    // =================================================================

    /**
     * 👥 AJAX - Obtener empleados filtrados por empresa.
     */
    public function getEmployeesByCompany(Request $request)
    {
        $request->validate(['company_id' => 'required|exists:companies,id']);

        $employees = Employee::where('company_id', $request->company_id)
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);

        return response()->json($employees);
    }

    /**
     * 🔢 AJAX - Generar consecutivo por empresa (sin usar device).
     */
    public function getConsecutive($companyId)
    {
        $count = DeviceAssignment::where('company_id', $companyId)->count();

        $next = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        return response()->json(['consecutive' => "ASG-{$companyId}-{$next}"]);
    }

    /**
     * 💻 AJAX - Filtrar dispositivos disponibles.
     */
    public function filterDevices(Request $request)
    {
        $request->validate([
            'company_id' => 'required|integer',
            'type_id'    => 'nullable|integer',
        ]);

        $query = Device::query()
            ->where('company_id', $request->company_id)
            ->whereDoesntHave('assignments', fn($q) => $q->whereNull('returned_at'));

        if ($request->filled('type_id')) {
            $query->where('device_type_id', $request->type_id);
        }

        $devices = $query->with('type')->get();

        $formatted = $devices->map(fn($d) => [
            'id'        => $d->id,
            'asset_tag' => $d->asset_tag,
            'brand'     => $d->specs_map['brandcarac'] ?? 'Equipo',
            'model'     => $d->specs_map['modelcarac'] ?? '',
            'type'      => $d->type->name ?? 'Tipo desconocido',
        ]);

        return response()->json($formatted);
    }

    public function pdf(DeviceAssignment $assignment)
    {
        // Cargar relaciones necesarias
        $assignment->load([
            'employee',
            'items.device.type',
            'items.device.company'
        ]);

        // Renderizar vista PDF
        $pdf = Pdf::loadView('assignments.pdf', compact('assignment'))
            ->setPaper('letter')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true);

        $filename = 'Acta-Entrega-' . $assignment->consecutive . '.pdf';

        return $pdf->download($filename);
    }
}
