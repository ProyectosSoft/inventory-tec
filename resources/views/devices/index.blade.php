{{-- resources/views/devices/index.blade.php --}}
<x-layouts.app>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Inventario de dispositivos</h1>
        <a href="{{ route('devices.create') }}" class="px-4 py-2 rounded bg-blue-600 text-white">
            Nuevo
        </a>
    </div>

    @if (session('ok'))
        <div class="mb-4 rounded border border-green-400/40 bg-green-400/10 p-3 text-sm text-green-800">
            {{ session('ok') }}
        </div>
    @endif

    {{-- 🔍 Filtros --}}
    <form method="GET" class="mb-4 grid gap-2 md:grid-cols-5">
        <select name="company_id" class="w-full rounded border px-3 py-2">
            <option value="">Todas las empresas</option>
            @foreach ($companies as $c)
                <option value="{{ $c->id }}" @selected(($filters['company_id'] ?? '') == $c->id)>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>

        <select name="type" class="w-full rounded border px-3 py-2">
            <option value="">Todos los tipos</option>
            @foreach ($types as $t)
                <option value="{{ $t->key }}" @selected(($filters['type'] ?? '') == $t->key)>
                    {{ $t->name }}
                </option>
            @endforeach
        </select>

        <select name="status" class="w-full rounded border px-3 py-2">
            <option value="">Todos los estados</option>
            @foreach (['active' => 'Activo', 'in_repair' => 'En reparación', 'lost' => 'Perdido', 'retired' => 'Retirado'] as $k => $v)
                <option value="{{ $k }}" @selected(($filters['status'] ?? '') == $k)>
                    {{ $v }}
                </option>
            @endforeach
        </select>

        <div class="flex gap-2 col-span-2">
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Marca, Modelo, Serial"
                class="w-full rounded border px-3 py-2">
            <button class="px-3 py-2 rounded border">Buscar</button>
            <a href="{{ route('devices.index') }}" class="px-3 py-2 rounded border">Limpiar</a>
        </div>
    </form>

    {{-- 📋 Tabla --}}
    <div class="overflow-x-auto rounded border">
        <table class="min-w-full text-sm">
            <thead class="bg-black/10">
                <tr>
                    <th class="px-3 py-2 text-left">Asset</th>
                    <th class="px-3 py-2 text-left">Marca</th>
                    <th class="px-3 py-2 text-left">Modelo</th>
                    <th class="px-3 py-2 text-left">Serial</th>
                    <th class="px-3 py-2 text-left">Tipo</th>
                    <th class="px-3 py-2 text-left">Empresa</th>
                    <th class="px-3 py-2 text-left">Estado</th>
                    <th class="px-3 py-2 text-left">Compra</th>
                    <th class="px-3 py-2 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($devices as $d)
                    <tr class="border-t hoverable cursor-pointer" onclick="toggleRowSelection(this, event)">
                        {{-- Asset --}}
                        <td class="px-3 py-2 font-medium">{{ $d->asset_tag }}</td>

                        {{-- Marca (clic para abrir historial) --}}
                        <td class="px-3 py-2 text-blue-600 underline cursor-pointer hover:text-blue-800"
                            onclick="openHistoryModal({{ $d->id }}, '{{ $d->specs_map['brandcarac'] ?? '—' }} / {{ $d->specs_map['model'] ?? '' }}')">
                            {{ $d->specs_map['brand'] ?? ($d->specs_map['brandcarac'] ?? '—') }}

                        </td>

                        {{-- Modelo --}}
                        <td class="px-3 py-2">{{ $d->specs_map['modelcarac'] ?? ($d->specs_map['model'] ??'—' )}}</td>

                        {{-- Serial --}}
                        <td class="px-3 py-2">{{ $d->specs_map['seriecarac'] ?? ($d->specs_map['serial'] ?? '—') }}</td>

                        {{-- Tipo --}}
                        <td class="px-3 py-2">{{ $d->type->name ?? '—' }}</td>

                        {{-- Empresa --}}
                        <td class="px-3 py-2">{{ $d->company?->name ?? '—' }}</td>

                        {{-- Empleado asignado --}}
                        {{-- <td class="px-3 py-2">
                            {{ optional($d->assignments->last()?->employee)->first_name ?? '—' }}
                            {{ optional($d->assignments->last()?->employee)->last_name ?? '' }}
                        </td> --}}

                        {{-- Estado --}}
                        <td class="px-3 py-2 capitalize">{{ $d->status }}</td>

                        {{-- Fecha de compra --}}
                        <td class="px-3 py-2">{{ $d->purchase_date?->format('Y-m-d') ?? '—' }}</td>

                        {{-- Acciones --}}
                        <td class="px-3 py-2 text-right">
                            <a href="{{ route('devices.show', $d) }}"
                                class="px-2 py-1 rounded border inline-block hover:bg-blue-100 dark:hover:bg-blue-900">Ver</a>
                            <a href="{{ route('devices.edit', $d) }}"
                                class="px-2 py-1 rounded border inline-block hover:bg-blue-100 dark:hover:bg-blue-900">Editar</a>

                            @php
                                $activeAssignment = $d->assignments->whereNull('returned_at')->last();
                            @endphp

                            {{-- Asignar / Devolver --}}
                            {{-- @if (!$activeAssignment)
                                <button class="px-2 py-1 rounded bg-blue-600 text-white hover:bg-blue-700"
                                    onclick="openAssignModal({{ $d->id }})">
                                    Asignar
                                </button>
                            @else
                                <form
                                    action="{{ route('devices.assignments.update', [$d->id, $activeAssignment->id]) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-2 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 ml-2">
                                        Devolver
                                    </button>
                                </form>
                            @endif --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-3 py-6 text-center text-sm opacity-70">
                            No hay dispositivos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $devices->links() }}</div>

    {{-- Modal Asignar Empleado --}}
    {{-- <div id="assignModal" class="fixed inset-0 hidden bg-black/40 items-center justify-center z-50">
        <div class="modal-content rounded-lg shadow-xl w-full max-w-lg p-6 relative">
            <h2 class="text-lg font-semibold mb-4">Asignar Empleado</h2>

            <input id="employeeSearch" type="text" placeholder="Buscar por nombre, documento o código..."
                class="w-full border rounded px-3 py-2 mb-3 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

            <ul id="employeeResults" class="border rounded max-h-56 overflow-y-auto mb-4 divide-y divide-gray-100"></ul>

            <div class="flex justify-end space-x-2">
                <button onclick="closeAssignModal()" class="px-4 py-2 border rounded">Cancelar</button>
                <button id="saveAssignBtn"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50"
                    disabled>Guardar</button>
            </div>

            <div id="loadingSpinner" class="hidden absolute inset-0 bg-white/80 flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-600"></div>
            </div>
        </div>
    </div> --}}

    {{-- 📜 Modal de Historial Mejorado --}}
    <div id="historyModal" class="fixed inset-0 hidden items-center justify-center bg-black/60 backdrop-blur-md z-50">
        <div class="modal-content relative">
            <h2 id="historyTitle">Historial del dispositivo</h2>
            <div class="p-6" id="historyBody">
                <p class="text-center py-6 opacity-60">Cargando historial...</p>
            </div>
            <div class="flex justify-end p-4 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeHistoryModal()">Cerrar</button>
            </div>
        </div>
    </div>


</x-layouts.app>

{{-- 🎨 Estilos modo claro/oscuro para hover y selección --}}
<style>
    /* Hover adaptable */
    tr.hoverable:hover {
        transition: background-color 0.15s ease;
    }

    @media (prefers-color-scheme: light) {
        tr.hoverable:hover {
            background-color: rgba(37, 99, 235, 0.08);
        }
    }

    @media (prefers-color-scheme: dark) {
        tr.hoverable:hover {
            background-color: rgba(255, 255, 255, 0.08);
        }
    }

    /* Fila seleccionada */
    tr.selected {
        background-color: #e5e7eb !important;
        /* gris claro */
        color: #111827 !important;
        box-shadow: inset 0 0 0 2px #2563eb;
    }

    @media (prefers-color-scheme: dark) {
        tr.selected {
            background-color: rgba(59, 130, 246, 0.25) !important;
            /* azul translúcido */
            color: #f9fafb !important;
            /* texto claro */
            box-shadow: inset 0 0 0 1px #3b82f6;
        }
    }
</style>
<style>
    /* ===========================
     🎨 Modal adaptable a modo oscuro/claro
     =========================== */
    #assignModal .modal-content {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }

    @media (prefers-color-scheme: light) {
        #assignModal .modal-content {
            background-color: #ffffff;
            color: #111827;
            border: 1px solid #e5e7eb;
        }

        #assignModal input {
            background-color: #ffffff;
            color: #111827;
            border-color: #d1d5db;
        }

        #assignModal ul li:hover {
            background-color: rgba(37, 99, 235, 0.1);
        }
    }

    @media (prefers-color-scheme: dark) {
        #assignModal .modal-content {
            background-color: #1f2937;
            /* gris oscuro elegante */
            color: #f3f4f6;
            border: 1px solid #374151;
        }

        #assignModal input {
            background-color: #111827;
            color: #f3f4f6;
            border-color: #374151;
        }

        #assignModal ul li {
            border-color: #374151;
        }

        #assignModal ul li:hover {
            background-color: rgba(59, 130, 246, 0.2);
        }

        #assignModal button {
            color: inherit;
        }

        #assignModal #loadingSpinner {
            background-color: rgba(31, 41, 55, 0.85);
        }

        #historyModal .modal-content {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

    }

    /* Ajustes visuales generales */
    #assignModal .modal-content {
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }
</style>
<style>
    /* ===========================
   💠 Modal de Historial — Optimizado para fondo negro
   =========================== */
    #historyModal {
        display: none;
        align-items: center;
        justify-content: center;
        position: fixed;
        inset: 0;
        z-index: 50;
        background-color: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(6px);
        animation: fadeIn 0.25s ease;
    }

    #historyModal.flex {
        display: flex;
    }

    #historyModal .modal-content {
        width: 100%;
        max-width: 760px;
        border-radius: 0.9rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.55);
        overflow: hidden;
        transform: translateY(20px);
        opacity: 0;
        animation: slideUp 0.25s ease forwards;
    }

    /* 🕊️ Tema claro */
    @media (prefers-color-scheme: light) {
        #historyModal .modal-content {
            background: linear-gradient(160deg, #0f172a, #1e293b);
            color: #f3f4f6;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        #historyModal h2 {
            background-color: #f3f4f6;
            color: #111827;
            padding: 1rem 1.25rem;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
        }

        #historyModal table {
            width: 100%;
            border-collapse: collapse;
        }

        #historyModal thead {
            background-color: #f9fafb;
        }

        #historyModal th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            color: #374151;
        }

        #historyModal td {
            padding: 0.75rem 1rem;
            border-top: 1px solid #f3f4f6;
        }

        #historyModal tr:hover td {
            background-color: #f3f4f6;
        }

        #historyModal button {
            background-color: #2563eb;
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
        }

        #historyModal button:hover {
            background-color: #1d4ed8;
        }
    }

    /* 🌙 Tema oscuro (fondo negro real) */
    @media (prefers-color-scheme: dark) {
        #historyModal .modal-content {
            background: linear-gradient(160deg, #0f172a, #1e293b);
            color: #f3f4f6;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        #historyModal h2 {
            background: rgba(255, 255, 255, 0.04);
            color: #f9fafb;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 600;
            box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.05);
        }

        #historyModal table {
            width: 100%;
            border-collapse: collapse;
        }

        #historyModal thead {
            background-color: rgba(255, 255, 255, 0.06);
        }

        #historyModal th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            color: #e5e7eb;
        }

        #historyModal td {
            padding: 0.75rem 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        #historyModal tr:hover td {
            background-color: rgba(255, 255, 255, 0.07);
        }

        #historyModal button {
            background-color: #2563eb;
            color: #f9fafb;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
        }

        #historyModal button:hover {
            background-color: #1d4ed8;
        }
    }

    /* 🎬 Animaciones suaves */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>




<script>
    function toggleRowSelection(row, event) {
        // Evita que se active cuando se hace clic en botones o formularios
        if (event.target.tagName === 'BUTTON' || event.target.tagName === 'A' || event.target.closest('form')) {
            return;
        }

        document.querySelectorAll('tr.selected').forEach(r => r.classList.remove('selected'));
        row.classList.add('selected');
    }
</script>


{{-- ⚙️ Lógica de interacción --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleRowSelection(row) {
        document.querySelectorAll('tr.selected').forEach(r => r.classList.remove('selected'));
        row.classList.add('selected');
    }

    let assignModal = document.getElementById('assignModal');
    let searchInput = document.getElementById('employeeSearch');
    let resultList = document.getElementById('employeeResults');
    let saveBtn = document.getElementById('saveAssignBtn');
    let loading = document.getElementById('loadingSpinner');
    let selectedId = null;
    let deviceId = null;

    async function openAssignModal(id) {
        assignModal.classList.remove('hidden');
        assignModal.classList.add('flex');
        deviceId = id;
        selectedId = null;
        resultList.innerHTML = '';
        searchInput.value = '';
        saveBtn.disabled = true;
    }

    function closeAssignModal() {
        assignModal.classList.add('hidden');
        assignModal.classList.remove('flex');
    }

    searchInput.addEventListener('input', async () => {
        const q = searchInput.value.trim();
        if (q.length < 2) {
            resultList.innerHTML = '';
            return;
        }

        const res = await fetch(`{{ route('employees.search') }}?q=${encodeURIComponent(q)}`);
        const data = await res.json();

        if (data.length === 0) {
            resultList.innerHTML = `
        <li class="px-3 py-2 text-gray-500 text-sm text-center">No se encontraron empleados</li>
      `;
            return;
        }

        resultList.innerHTML = data.map(emp => `
      <li class="px-3 py-2 cursor-pointer hover:bg-blue-100"
          onclick="selectEmployee(${emp.id}, '${emp.first_name}', '${emp.last_name}', '${emp.document_id}', '${emp.code}')">
        <div class="flex justify-between">
          <span>${emp.first_name} ${emp.last_name}</span>
          <span class="text-gray-500 text-xs">${emp.code}</span>
        </div>
        <p class="text-gray-500 text-xs">Doc: ${emp.document_id}</p>
      </li>
    `).join('');
    });

    function selectEmployee(id, firstName, lastName, document, code) {
        selectedId = id;
        saveBtn.disabled = false;
        Array.from(resultList.children).forEach(li => li.classList.remove('bg-blue-50'));
        event.target.closest('li').classList.add('bg-blue-50');
    }

    saveBtn.addEventListener('click', async () => {
        if (!selectedId) return;

        loading.classList.remove('hidden');
        const url = `{{ route('devices.assignments.store', ':id') }}`.replace(':id', deviceId);

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('employee_id', selectedId);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        loading.classList.add('hidden');
        closeAssignModal();

        if (response.ok) {
            const assignment = await response.json();
            const row = document.querySelector(`button[onclick="openAssignModal(${deviceId})"]`)?.closest(
                'tr');

            if (row) {
                const empleadoCell = row.querySelector('td:nth-child(6)');
                const accionesCell = row.querySelector('td:last-child');

                empleadoCell.textContent = `${assignment.employee_name}`;
                accionesCell.innerHTML = `
          <form method="POST" action="/devices/${deviceId}/assignments/${assignment.id}" class="inline devolver-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PATCH">
            <button type="button" onclick="confirmDevolver(this, ${deviceId}, ${assignment.id})"
              class="px-2 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 ml-2">
              Devolver
            </button>
          </form>
        `;
            }

            Swal.fire({
                title: 'Empleado asignado',
                text: 'La asignación se realizó correctamente.',
                icon: 'success',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Aceptar'
            });

        } else {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al asignar el empleado.',
                icon: 'error',
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Entendido'
            });
        }
    });

    // Confirmar devolución
    async function confirmDevolver(button, deviceId, assignmentId) {
        const confirmed = await Swal.fire({
            title: '¿Devolver dispositivo?',
            text: 'Esta acción marcará el dispositivo como devuelto.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, devolver',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280'
        });

        if (!confirmed.isConfirmed) return;

        const form = button.closest('form');
        const url = `/devices/${deviceId}/assignments/${assignmentId}`;
        const formData = new FormData(form);

        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            Swal.fire({
                title: 'Dispositivo devuelto',
                text: 'El dispositivo fue marcado como devuelto.',
                icon: 'success',
                confirmButtonColor: '#2563eb'
            });

            const row = button.closest('tr');
            const empleadoCell = row.querySelector('td:nth-child(6)');
            const accionesCell = row.querySelector('td:last-child');

            empleadoCell.textContent = '—';
            accionesCell.innerHTML = `
        <button class="px-2 py-1 rounded bg-blue-600 text-white hover:bg-blue-700"
            onclick="openAssignModal(${deviceId})">
          Asignar
        </button>
      `;
        } else {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo devolver el dispositivo.',
                icon: 'error',
                confirmButtonColor: '#dc2626'
            });
        }
    }
</script>

<script>
    const historyModal = document.getElementById('historyModal');
    const historyTitle = document.getElementById('historyTitle');
    const historyBody = document.getElementById('historyBody');

    async function openHistoryModal(deviceId, title) {
        historyModal.classList.remove('hidden');
        historyModal.classList.add('flex');
        historyTitle.textContent = `Historial de ${title}`;
        historyBody.innerHTML = `<p class="text-center py-6 opacity-60">Cargando historial...</p>`;

        try {
            const response = await fetch(`/devices/${deviceId}/history`);
            const data = await response.json();

            if (!data.length) {
                historyBody.innerHTML =
                    `<p class="text-center py-6 opacity-60">No hay registros de asignaciones anteriores.</p>`;
                return;
            }

            let rows = data.map(item => `
        <tr class="border-t hover:bg-gray-50 dark:hover:bg-gray-800">
          <td class="px-3 py-2">${item.employee}</td>
          <td class="px-3 py-2">${item.assigned_at || '—'}</td>
          <td class="px-3 py-2">${item.returned_at || '—'}</td>
          <td class="px-3 py-2">
            ${item.status === 'Activo'
              ? '<span class="text-green-600 font-medium">Activo</span>'
              : '<span class="text-gray-500">Devuelto</span>'}
          </td>
        </tr>
      `).join('');

            historyBody.innerHTML = `
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm border">
            <thead class="bg-black/10">
              <tr>
                <th class="px-3 py-2 text-left">Empleado</th>
                <th class="px-3 py-2 text-left">Asignado</th>
                <th class="px-3 py-2 text-left">Devuelto</th>
                <th class="px-3 py-2 text-left">Estado</th>
              </tr>
            </thead>
            <tbody>${rows}</tbody>
          </table>
        </div>
      `;
        } catch (error) {
            historyBody.innerHTML = `<p class="text-center py-6 text-red-500">Error al cargar el historial.</p>`;
        }
    }

    function closeHistoryModal() {
        historyModal.classList.add('hidden');
        historyModal.classList.remove('flex');
    }
</script>
