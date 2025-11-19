<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceAssignment;
use App\Models\DeviceAssignmentItem;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DeviceAssignmentController extends Controller
{
    
    /**
     * Guarda una nueva asignación con N dispositivos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id'   => ['required', 'exists:companies,id'],
            'employee_id'  => ['required', 'exists:employees,id'],
            'assigned_at'  => ['required', 'date'],
            'notes'        => ['nullable', 'string'],
            'device_ids'   => ['required', 'json'], // viene como "[1,2,3]"
        ]);

        $deviceIds = json_decode($validated['device_ids'], true);

        if (!is_array($deviceIds) || count($deviceIds) == 0) {
            return back()->with('error', 'Debe seleccionar al menos un dispositivo.');
        }

        DB::beginTransaction();

        try {
            // 1️⃣ Crear CABECERA
            $assignment = DeviceAssignment::create([
                'company_id'  => $validated['company_id'],
                'employee_id' => $validated['employee_id'],
                'consecutive' => $request->consecutive,
                'assigned_at' => Carbon::parse($validated['assigned_at']),
                'notes'       => $validated['notes'] ?? null,
            ]);

            // 2️⃣ Crear ITEMS (detalle)
            foreach ($deviceIds as $deviceId) {
                DeviceAssignmentItem::create([
                    'assignment_id' => $assignment->id,
                    'device_id'     => $deviceId,
                    'specs'         => null,  // si en futuro guardas specs de asignación
                    'notes'         => null,
                ]);

                // Marcar dispositivo como asignado (opcional)
                Device::where('id', $deviceId)->update([
                    'current_employee_id' => $validated['employee_id'], // si manejas este campo
                ]);
            }

            DB::commit();

            return redirect()
                ->route('assignments.index')
                ->with('ok', 'Asignación creada correctamente con ' . count($deviceIds) . ' equipos.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar la asignación: ' . $e->getMessage());
        }
    }


    /**
     * Registrar la devolución de TODOS los items de una asignación.
     */
    public function update(Request $request, DeviceAssignment $assignment)
    {
        $validated = $request->validate([
            'returned_at' => ['required', 'date'],
            'notes'       => ['nullable', 'string'],
        ]);

        DB::beginTransaction();

        try {
            // 1️⃣ Actualizar cabecera
            $assignment->update([
                'returned_at' => Carbon::parse($validated['returned_at']),
                'notes'       => $validated['notes'] ?? $assignment->notes,
            ]);

            // 2️⃣ Liberar dispositivos asignados
            foreach ($assignment->items as $item) {
                $item->device->update([
                    'current_employee_id' => null,
                ]);
            }

            DB::commit();

            return back()->with('ok', 'Asignación devuelta correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al devolver asignación: ' . $e->getMessage());
        }
    }
}
