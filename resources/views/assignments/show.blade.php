<x-layouts.app>
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Detalle de Asignación</h1>

        <div class="space-y-3 border rounded p-4">
            <div>
                <strong>Empleado:</strong>
                {{ $assignment->employee->first_name }} {{ $assignment->employee->last_name }}
            </div>

            <div>
                <strong>Fecha asignado:</strong>
                {{ $assignment->assigned_at->format('d/m/Y') }}
            </div>

            <div>
                <strong>Devuelto:</strong>
                {{ $assignment->returned_at?->format('d/m/Y') ?? '—' }}
            </div>

            <div>
                <strong>Estado:</strong>
                @if ($assignment->returned_at)
                    <span class="text-gray-600 font-medium">Devuelto</span>
                @else
                    <span class="text-green-700 font-medium">Activo</span>
                @endif
            </div>
        </div>

        {{-- === DISPOSITIVOS ASIGNADOS === --}}
        <div class="mt-6 border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Dispositivos incluidos en esta asignación</h2>

            @if ($assignment->items->isEmpty())
                <p class="text-neutral-500 text-sm">No se encontraron dispositivos en esta asignación.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border">
                        <thead class="bg-black/10">
                            <tr>
                                <th class="px-3 py-2 text-left">Asset</th>
                                <th class="px-3 py-2 text-left">Marca</th>
                                <th class="px-3 py-2 text-left">Modelo</th>
                                <th class="px-3 py-2 text-left">Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignment->items as $item)
                                @php $device = $item->device; @endphp
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-3 py-2">{{ $device->asset_tag ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ $device->specs_map['brandcarac'] ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ $device->specs_map['modelcarac'] ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ $device->type->name ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="mt-4 flex justify-end">
            <a href="{{ route('assignments.index') }}" class="px-4 py-2 border rounded">Volver</a>
        </div>
        <a href="{{ route('assignments.pdf', $assignment) }}"
            class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">
            Descargar PDF
        </a>
    </div>
</x-layouts.app>
