<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Device;

class DashboardController extends Controller
{
    public function index()
    {
        // Cargamos empresas con conteo de dispositivos y empleados
        $companies = Company::withCount(['devices', 'employees'])
            ->orderBy('name')
            ->get();

        return view('dashboard', compact('companies'));
    }

    /**
     * 📊 Equipos por tipo (todos los equipos de la empresa)
     */
    public function devicesByType(Company $company)
    {
        $data = Device::selectRaw('device_type_id, COUNT(*) as total')
            ->where('company_id', $company->id)
            ->groupBy('device_type_id')
            ->with('type:id,name')
            ->get()
            ->map(fn ($d) => [
                'label' => $d->type->name ?? 'Sin tipo',
                'value' => $d->total,
            ])
            ->values(); // por si acaso

        return response()->json($data);
    }

    /**
     * 📊 Equipos ASIGNADOS por tipo (empresa) – Opción B
     * Cuenta cuántos EQUIPOS asignados hay por tipo.
     */
    public function employeesByType(Company $company)
    {
        $data = Device::selectRaw('devices.device_type_id, COUNT(*) as total')
            ->join('device_assignment_items', 'device_assignment_items.device_id', '=', 'devices.id')
            ->join('device_assignments', 'device_assignment_items.assignment_id', '=', 'device_assignments.id')
            ->whereNull('device_assignments.returned_at')
            ->where('devices.company_id', $company->id)
            ->groupBy('devices.device_type_id')
            ->with('type:id,name')
            ->get()
            ->map(fn ($d) => [
                'label' => $d->type->name ?? 'Sin tipo',
                'value' => $d->total,
            ])
            ->values();

        return response()->json($data);
    }
}
