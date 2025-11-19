<x-layouts.app>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Asignaciones de Dispositivos</h1>
        <a href="{{ route('assignments.create') }}" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
            Nueva Asignación
        </a>
    </div>

    {{-- Mensajes de confirmación --}}
    @if (session('ok'))
        <div class="mb-4 rounded border border-green-400/40 bg-green-400/10 p-3 text-sm text-green-800">
            {{ session('ok') }}
        </div>
    @elseif (session('error'))
        <div class="mb-4 rounded border border-red-400/40 bg-red-400/10 p-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- 🔍 Filtros --}}
    <form method="GET" class="mb-4 grid gap-2 md:grid-cols-4">
        {{-- Empresa --}}
        <select name="company_id" class="w-full rounded border px-3 py-2">
            <option value="">Todas las empresas</option>
            @foreach (\App\Models\Company::orderBy('name')->get() as $company)
                <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>

        {{-- Estado --}}
        <select name="status" class="w-full rounded border px-3 py-2">
            <option value="">Todos los estados</option>
            <option value="active" @selected(request('status') === 'active')>Activos</option>
            <option value="returned" @selected(request('status') === 'returned')>Devueltos</option>
        </select>

        {{-- Búsqueda --}}
        <div class="flex gap-2 col-span-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar empleado, documento o tag..."
                class="w-full rounded border px-3 py-2">
            <button class="px-3 py-2 rounded border bg-zinc-100 hover:bg-zinc-200">Buscar</button>
            <a href="{{ route('assignments.index') }}" class="px-3 py-2 rounded border">Limpiar</a>
        </div>
    </form>

    {{-- 📋 Tabla --}}
    <div class="overflow-x-auto rounded border">
        <table class="min-w-full text-sm">
            <thead class="bg-black/10 dark:bg-zinc-800/60">
                <tr>
                    <th class="px-3 py-2 text-left">#</th>
                    <th class="px-3 py-2 text-left">Consecutivo</th>
                    <th class="px-3 py-2 text-left">Empresa</th>
                    <th class="px-3 py-2 text-left">Empleado</th>
                    <th class="px-3 py-2 text-left">Dispositivo(s)</th>
                    <th class="px-3 py-2 text-left">Tipo(s)</th>
                    <th class="px-3 py-2 text-left">Asignado</th>
                    <th class="px-3 py-2 text-left">Devuelto</th>
                    <th class="px-3 py-2 text-left">Estado</th>
                    <th class="px-3 py-2 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($assignments as $a)
                    <tr class="border-t hover:bg-zinc-50 dark:hover:bg-zinc-800/40">

                        {{-- # --}}
                        <td class="px-3 py-2">{{ $loop->iteration }}</td>

                        {{-- Consecutivo --}}
                        <td class="px-3 py-2 font-medium">{{ $a->consecutive ?? '—' }}</td>

                        {{-- Empresa --}}
                        <td class="px-3 py-2">{{ $a->company->name ?? '—' }}</td>

                        {{-- Empleado --}}
                        <td class="px-3 py-2">
                            {{ $a->employee->first_name }} {{ $a->employee->last_name }}
                            <div class="text-xs text-zinc-500">{{ $a->employee->document_id }}</div>
                        </td>

                        {{-- 🔹 Dispositivos múltiples --}}
                        <td class="px-3 py-2">
                            @foreach ($a->items as $item)
                                <div class="mb-1">
                                    <span class="font-medium">{{ $item->device->asset_tag }}</span>
                                    <div class="text-xs text-gray-500">
                                        {{ $item->device->brand }} {{ $item->device->model }}
                                    </div>
                                </div>
                            @endforeach
                        </td>

                        {{-- 🔹 Tipos múltiples --}}
                        <td class="px-3 py-2">
                            @foreach ($a->items as $item)
                                <div class="text-xs mb-1">
                                    {{ $item->device->type->name }}
                                </div>
                            @endforeach
                        </td>

                        {{-- Fecha asignado --}}
                        <td class="px-3 py-2">
                            {{ $a->assigned_at?->format('Y-m-d') ?? '—' }}
                        </td>

                        {{-- Fecha devuelto --}}
                        <td class="px-3 py-2">
                            {{ $a->returned_at?->format('Y-m-d') ?? '—' }}
                        </td>

                        {{-- Estado --}}
                        <td class="px-3 py-2">
                            @if ($a->returned_at)
                                <span
                                    class="text-xs text-gray-600 dark:text-gray-400 bg-gray-200/40 dark:bg-gray-700 px-2 py-0.5 rounded-full">
                                    Devuelto
                                </span>
                            @else
                                <span
                                    class="text-xs text-green-700 bg-green-200/50 px-2 py-0.5 rounded-full">
                                    Activo
                                </span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td class="px-3 py-2 text-right">
                            <a href="{{ route('assignments.show', $a) }}"
                                class="px-2 py-1 rounded border hover:bg-blue-100 dark:hover:bg-blue-900">Ver</a>

                            @if (!$a->returned_at)
                                <form action="{{ route('assignments.return', $a) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="ml-2 px-2 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                        Devolver
                                    </button>
                                </form>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-3 py-6 text-center text-sm opacity-70">
                            No hay asignaciones registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $assignments->links() }}</div>
</x-layouts.app>
