<x-layouts.app>
    {{-- Encabezado --}}
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">{{ $employee->full_name ?: 'Empleado' }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="rounded border px-3 py-2">Editar</a>
            <a href="{{ route('employees.index') }}" class="rounded border px-3 py-2">Volver</a>
        </div>
    </div>

    {{-- Tarjetas de información y dispositivos --}}
    <div class="grid gap-4 md:grid-cols-2">
        {{-- Información del empleado --}}
        <div class="rounded border p-4">
            <h2 class="mb-3 font-semibold text-gray-800">Información</h2>
            <dl class="text-sm space-y-1">
                <div>
                    <dt class="inline font-medium">Empresa:</dt>
                    <dd class="inline">{{ $employee->company?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="inline font-medium">Código:</dt>
                    <dd class="inline">{{ $employee->code ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="inline font-medium">Documento:</dt>
                    <dd class="inline">{{ $employee->document_id ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="inline font-medium">Email:</dt>
                    <dd class="inline">{{ $employee->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="inline font-medium">Teléfono:</dt>
                    <dd class="inline">{{ $employee->phone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="inline font-medium">Cargo:</dt>
                    <dd class="inline">{{ $employee->position ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="inline font-medium">Sede:</dt>
                    <dd class="inline">{{ $employee->site ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="inline font-medium">Estado:</dt>
                    <dd class="inline">
                        @switch($employee->status)
                            @case('active')
                                <span class="text-green-700 font-semibold">Activo</span>
                                @break
                            @case('inactive')
                                <span class="text-gray-500">Inactivo</span>
                                @break
                            @default
                                <span class="text-yellow-600">Suspendido</span>
                        @endswitch
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Dispositivos asignados actualmente --}}
        <div class="rounded border p-4">
            <h2 class="mb-3 font-semibold text-gray-800">Dispositivos asignados</h2>

            @if ($employee->currentDevices->isEmpty())
                <p class="text-sm text-neutral-500">No tiene dispositivos asignados actualmente.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border">
                        <thead class="bg-black/10">
                            <tr>
                                <th class="px-3 py-2 text-left">Asset</th>
                                <th class="px-3 py-2 text-left">Tipo</th>
                                <th class="px-3 py-2 text-left">Marca / Modelo</th>
                                <th class="px-3 py-2 text-left">Asignado desde</th>
                                <th class="px-3 py-2 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->currentDevices as $device)
                                @php
                                    $assignment = $device->assignments
                                        ->where('employee_id', $employee->id)
                                        ->whereNull('returned_at')
                                        ->last();
                                @endphp
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-3 py-2">{{ $device->asset_tag }}</td>
                                    <td class="px-3 py-2">{{ $device->type->name ?? '—' }}</td>
                                    <td class="px-3 py-2">
                                        {{ $device->specs_map['brand'] ?? '—' }} /
                                        {{ $device->specs_map['model'] ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2">
                                        {{ optional($assignment)->assigned_at?->format('Y-m-d') ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-right">
                                        <form action="{{ route('devices.assignments.update', [$device->id, $assignment?->id]) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-2 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                                Devolver
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Historial de asignaciones --}}
    <div class="rounded border p-4 mt-4">
        <h2 class="mb-3 font-semibold text-gray-800">Historial de asignaciones</h2>

        @php
            // ✔ Cargar devices() en lugar de device()
            $history = $employee->assignments()
                ->with('devices')
                ->latest('assigned_at')
                ->get();
        @endphp

        @if ($history->isEmpty())
            <p class="text-sm text-neutral-500">No hay registros de asignaciones anteriores.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border">
                    <thead class="bg-black/10">
                        <tr>
                            <th class="px-3 py-2 text-left">Assets</th>
                            <th class="px-3 py-2 text-left">Marca / Modelo</th>
                            <th class="px-3 py-2 text-left">Asignado</th>
                            <th class="px-3 py-2 text-left">Devuelto</th>
                            <th class="px-3 py-2 text-left">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $a)
                            <tr class="border-t hoverable cursor-pointer" onclick="toggleRowSelection(this)">
                                {{-- Assets --}}
                                <td class="px-3 py-2">
                                    @foreach ($a->devices as $d)
                                        {{ $d->asset_tag }}<br>
                                    @endforeach
                                </td>

                                {{-- Marca Modelo --}}
                                <td class="px-3 py-2">
                                    @foreach ($a->devices as $d)
                                        {{ $d->specs_map['brand'] ?? '—' }} /
                                        {{ $d->specs_map['model'] ?? '—' }}<br>
                                    @endforeach
                                </td>

                                <td class="px-3 py-2">{{ $a->assigned_at?->format('Y-m-d') ?? '—' }}</td>
                                <td class="px-3 py-2">{{ $a->returned_at?->format('Y-m-d') ?? '—' }}</td>

                                <td class="px-3 py-2">
                                    @if (!$a->returned_at)
                                        <span class="text-green-600 font-medium">Activo</span>
                                    @else
                                        <span class="text-gray-400">Devuelto</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-layouts.app>

<style>
    tr.selected {
        background-color: #f9fafb !important;
        color: #111827 !important;
        box-shadow: inset 0 0 0 2px #2563eb;
    }

    @media (prefers-color-scheme: dark) {
        tr.selected {
            background-color: #ffffff !important;
            color: #000000 !important;
            box-shadow: inset 0 0 0 2px #3b82f6;
        }
    }

    tr.hoverable:hover {
        background-color: rgba(37, 99, 235, 0.08);
        transition: background-color 0.15s ease, box-shadow 0.2s ease;
    }

    tr {
        transition: background-color 0.15s ease, color 0.15s ease, box-shadow 0.2s ease;
    }
</style>

<script>
    function toggleRowSelection(row) {
        document.querySelectorAll('tr.selected').forEach(r => r.classList.remove('selected'));
        row.classList.add('selected');
    }
</script>
