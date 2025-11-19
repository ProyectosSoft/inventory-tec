<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeviceTypeController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q',''));
        $types = DeviceType::when($q !== '', fn($s) =>
                $s->where('name','like',"%$q%")
                  ->orWhere('key','like',"%$q%")
            )
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('device_types.index', compact('types','q'));
    }

    public function create()
    {
        $type = new DeviceType([
            'is_active' => true,
            'schema' => ['groups' => []],
        ]);

        return view('device_types.create', compact('type'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key'       => ['required','string','max:50','alpha_dash', 'unique:device_types,key'],
            'name'      => ['required','string','max:100'],
            'is_active' => ['sometimes','boolean'],
            'schema'    => ['required','json'],
        ]);

        // Asegura estructura mínima
        $schema = json_decode($data['schema'], true) ?: ['groups'=>[]];
        if (!isset($schema['groups']) || !is_array($schema['groups'])) {
            return back()->withErrors(['schema' => 'El schema enviado no es válido.'])->withInput();
        }

        DeviceType::create([
            'key'       => $data['key'],
            'name'      => $data['name'],
            'is_active' => (bool)($data['is_active'] ?? false),
            'schema'    => $schema,
        ]);

        return redirect()->route('device-types.index')->with('ok', 'Tipo creado.');
    }

    public function edit(DeviceType $deviceType)
    {
        $type = $deviceType;
        return view('device_types.edit', compact('type'));
    }

    public function update(Request $request, DeviceType $deviceType)
    {
        $data = $request->validate([
            'key'       => ['required','string','max:50','alpha_dash', Rule::unique('device_types','key')->ignore($deviceType->id)],
            'name'      => ['required','string','max:100'],
            'is_active' => ['sometimes','boolean'],
            'schema'    => ['required','json'],
        ]);

        $schema = json_decode($data['schema'], true) ?: ['groups'=>[]];
        if (!isset($schema['groups']) || !is_array($schema['groups'])) {
            return back()->withErrors(['schema' => 'El schema enviado no es válido.'])->withInput();
        }

        $deviceType->update([
            'key'       => $data['key'],
            'name'      => $data['name'],
            'is_active' => (bool)($data['is_active'] ?? false),
            'schema'    => $schema,
        ]);

        return redirect()->route('device-types.index')->with('ok', 'Tipo actualizado.');
    }

    public function destroy(DeviceType $deviceType)
    {
        $deviceType->delete();
        return back()->with('ok', 'Tipo eliminado.');
    }
}
