<x-layouts.app>
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-semibold mb-6">Nueva Asignación</h1>

        {{-- MANEJO DE MENSAJES FLASH (SESIÓN) --}}
        @if (session('ok'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                {{ session('ok') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('assignments.store') }}" method="POST" id="assignmentForm" class="space-y-6">
            @csrf

            {{-- =====================
            1️⃣ SELECCIÓN GENERAL
        ===================== --}}
            <div class="grid md:grid-cols-3 gap-4">
                {{-- Empresa --}}
                <div>
                    <label for="companySelect" class="block text-sm font-medium mb-1">Empresa</label>
                    <select id="companySelect" name="company_id" required
                        class="w-full border rounded px-3 py-2 @error('company_id') border-red-500 @enderror">
                        <option value="">Seleccionar...</option>
                        @foreach ($companies as $c)
                            <option value="{{ $c->id }}" {{ old('company_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Número consecutivo --}}
                <div>
                    <label for="consecutive" class="block text-sm font-medium mb-1">Número de asignación</label>
                    <input type="text" id="consecutive" name="consecutive" readonly value="{{ old('consecutive') }}"
                        class="w-full border rounded px-3 py-2 bg-gray-100 @error('consecutive') border-red-500 @enderror">
                    @error('consecutive')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha --}}
                <div>
                    <label for="assigned_at" class="block text-sm font-medium mb-1">Fecha de asignación</label>
                    <input type="date" name="assigned_at" id="assigned_at"
                        value="{{ old('assigned_at', date('Y-m-d')) }}" required
                        class="w-full border rounded px-3 py-2 @error('assigned_at') border-red-500 @enderror">
                    @error('assigned_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- =====================
            2️⃣ EMPLEADO (NUEVA ESTRUCTURA)
        ===================== --}}
            <div>
                <label class="block text-sm font-medium mb-1">Empleado</label>
                <div class="flex gap-2">
                    <input type="text" id="selectedEmployeeName"
                        placeholder="Seleccionar empresa y luego buscar empleado..."
                        class="w-full border rounded px-3 py-2 bg-gray-100" readonly>

                    <button type="button" id="openEmployeeModal"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Buscar Empleado
                    </button>
                </div>
                {{-- Campo oculto requerido para el ID del empleado --}}
                <input type="hidden" name="employee_id" id="selectedEmployeeId" value="{{ old('employee_id') }}"
                    required class="@error('employee_id') border-red-500 @enderror">

                @error('employee_id')
                    <p class="text-red-500 text-xs mt-1">Debe seleccionar un empleado.</p>
                @enderror
            </div>

            {{-- ... (El resto del formulario (Equipos y Notas) sigue igual) ... --}}

            {{-- =====================
            3️⃣ EQUIPOS
        ===================== --}}
            <div class="flex justify-between items-center mt-6">
                <h2 class="text-lg font-semibold">Equipos asignados</h2>
                <button type="button" id="openModal"
                    class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">
                    + Agregar equipos
                </button>
            </div>

            {{-- Tabla --}}
            <table class="min-w-full border rounded text-sm mt-2">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Tipo</th>
                        <th class="p-2 text-left">Equipo</th>
                        <th class="p-2 text-left">Serial / Tag</th>
                        <th class="p-2"></th>
                    </tr>
                </thead>
                <tbody id="selectedDevicesTable">
                    <tr class="text-gray-500 text-center">
                        <td colspan="4" class="p-2">Aún no se han agregado equipos.</td>
                    </tr>
                </tbody>
            </table>

            {{-- Campo oculto para IDs de dispositivos --}}
            <input type="hidden" name="device_ids" id="deviceIds" value="{{ old('device_ids', '[]') }}">
            @error('device_ids')
                <p class="text-red-500 text-xs mt-1">Debes agregar al menos un dispositivo.</p>
            @enderror


            {{-- =====================
            4️⃣ NOTAS Y ACCIONES
        ===================== --}}
            <div class="grid md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="notes" class="block text-sm font-medium mb-1">Notas (opcional)</label>
                    <textarea name="notes" id="notes" rows="2"
                        class="w-full border rounded px-3 py-2 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('assignments.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Guardar asignación
                </button>
            </div>
        </form>
    </div>

    {{-- MODAL DE BÚSQUEDA DE EMPLEADO (Diseño Mejorado) --}}
    <div id="employeeModal" class="hidden flex justify-center items-center z-50 fixed inset-0">
        <div class="bg-white rounded-xl w-full max-w-2xl p-6 relative shadow-2xl transform transition-all duration-300">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">
                Buscar y Seleccionar Empleado
            </h3>

            <div class="flex gap-3 mb-4">
                <input type="text" id="employeeSearchInput" placeholder="Buscar por Nombre, Apellido, Cédula o ID..."
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-blue-500 focus:border-blue-500 flex-1">
            </div>

            <div class="max-h-80 overflow-y-auto border rounded-lg">
                <table class="min-w-full text-sm">
                    <thead class="bg-blue-600 text-white sticky top-0">
                        <tr>
                            <th class="p-3 text-left rounded-tl-lg">Seleccionar</th>
                            <th class="p-3 text-left">ID Empleado</th>
                            <th class="p-3 text-left">Nombre Completo</th>
                            <th class="p-3 text-left rounded-tr-lg">Cédula/Documento</th>
                        </tr>
                    </thead>
                    <tbody id="availableEmployeesTable" class="divide-y divide-gray-200">
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 p-4 bg-white">
                                Ingrese términos de búsqueda.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <button id="closeEmployeeModal"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancelar
                </button>
                <button id="selectEmployeeBtn" disabled
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 disabled:bg-gray-400 transition duration-150">
                    Seleccionar
                </button>
            </div>
        </div>
    </div>

    {{-- ... (Tu código para el MODAL DE EQUIPOS (deviceModal) sigue aquí) ... --}}
    <div id="deviceModal" class="fixed inset-0 hidden flex justify-center items-center z-50">
        <div
            class="bg-white rounded-xl w-full max-w-4xl p-6 relative shadow-2xl transform transition-all duration-300">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">
                Seleccionar equipos disponibles
            </h3>

            <div class="flex gap-3 mb-4">
                <select id="typeFilter" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Todos los tipos</option>
                    @foreach ($types as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
                <input type="text" id="searchInput" placeholder="Buscar equipo..."
                    class="border border-gray-300 rounded-lg px-4 py-2 flex-1 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="max-h-96 overflow-y-auto border rounded-lg">
                <table class="min-w-full text-sm">
                    <thead class="bg-blue-600 text-white sticky top-0">
                        <tr>
                            <th class="p-3 text-left rounded-tl-lg">Seleccionar</th>
                            <th class="p-3 text-left">Tipo</th>
                            <th class="p-3 text-left">Marca</th>
                            <th class="p-3 text-left">Modelo</th>
                            <th class="p-3 text-left rounded-tr-lg">Tag</th>
                        </tr>
                    </thead>
                    <tbody id="availableDevicesTable" class="divide-y divide-gray-200">
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 p-4 bg-white">Seleccione empresa...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <button id="closeModal"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancelar
                </button>
                <button id="addSelectedBtn"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition duration-150">
                    Agregar seleccionados
                </button>
            </div>
        </div>
    </div>

    {{-- =====================
    6️⃣ SCRIPT (MODIFICADO)
===================== --}}
    <script>
        // Pasa las rutas de Laravel a JavaScript
        const EMPLOYEES_SEARCH_URL = "{{ route('assignments.getEmployeesByCompany') }}";
        const CONSECUTIVE_URL_BASE = "{{ url('assignments/consecutive') }}";
        const DEVICES_FILTER_URL = "{{ route('assignments.filterDevices') }}";
        // 💡 NUEVA RUTA: Asumiendo que crearás una ruta para buscar empleados
        // fuera del contexto de una empresa específica (o usaremos EMPLOYEES_SEARCH_URL)
        const EMPLOYEE_GLOBAL_SEARCH_URL = "{{ route('employees.search') }}";

        // Elementos DOM (Empleado)
        const employeeModal = document.getElementById('employeeModal');
        const openEmployeeModal = document.getElementById('openEmployeeModal');
        const closeEmployeeModal = document.getElementById('closeEmployeeModal');
        const employeeSearchInput = document.getElementById('employeeSearchInput');
        const availableEmployeesTable = document.getElementById('availableEmployeesTable');
        const selectEmployeeBtn = document.getElementById('selectEmployeeBtn');
        const selectedEmployeeName = document.getElementById('selectedEmployeeName');
        const selectedEmployeeId = document.getElementById('selectedEmployeeId');

        // Elementos DOM (Asignación y Equipos)
        const companySelect = document.getElementById('companySelect');
        const consecutiveInput = document.getElementById('consecutive');
        const modal = document.getElementById('deviceModal');
        const openModal = document.getElementById('openModal');
        const closeModal = document.getElementById('closeModal');
        const addSelectedBtn = document.getElementById('addSelectedBtn');
        const table = document.getElementById('selectedDevicesTable');
        const deviceIdsInput = document.getElementById('deviceIds');
        const availableTable = document.getElementById('availableDevicesTable');
        const searchInput = document.getElementById('searchInput');
        const typeFilter = document.getElementById('typeFilter');

        let selectedDevices = [];
        let availableDevices = [];
        let selectedEmployee = null;
        let searchTimeout;

        // ... (Inicializar dispositivos seleccionados (renderTable) - sin cambios) ...
        if (deviceIdsInput.value && deviceIdsInput.value !== '[]') {
            try {
                const oldIds = JSON.parse(deviceIdsInput.value);
                selectedDevices = oldIds.map(id => ({
                    id: id,
                    asset_tag: 'Cargando...',
                    brand: 'Cargando...',
                    model: '',
                    type: 'Cargando...'
                }));
                renderTable();
            } catch (e) {}
        }


        // 🔹 Cargar consecutivo y limpiar empleado al cambiar empresa
        companySelect.addEventListener('change', async () => {
            const companyId = companySelect.value;

            // Limpiar empleado seleccionado previamente al cambiar la empresa
            selectedEmployee = null;
            selectedEmployeeId.value = '';
            selectedEmployeeName.value = '';

            // Limpiar si no hay empresa seleccionada
            if (!companyId) {
                consecutiveInput.value = '';
                selectedDevices = [];
                renderTable();
                selectedEmployeeName.placeholder = 'Seleccionar empresa y luego buscar empleado...';
                return;
            }

            selectedEmployeeName.placeholder = 'Haga clic en "Buscar Empleado"';

            // --- Obtener Consecutivo ---
            try {
                const consRes = await fetch(`${CONSECUTIVE_URL_BASE}/${companyId}`);
                const consData = await consRes.json();
                consecutiveInput.value = consData.consecutive ?? '';
            } catch (error) {
                console.error('Error al cargar consecutivo:', error);
                consecutiveInput.value = '';
            }
        });

        // ===================================
        // 👥 LÓGICA DEL MODAL DE EMPLEADOS
        // ===================================

        // 🔹 Abrir modal de empleado
        openEmployeeModal.addEventListener('click', () => {
            if (!companySelect.value) {
                alert('Selecciona primero una empresa.');
                return;
            }
            employeeModal.classList.remove('hidden');
            employeeSearchInput.value = '';
            availableEmployeesTable.innerHTML =
                `<tr><td colspan="4" class="text-center text-gray-500 p-2">Ingrese términos de búsqueda.</td></tr>`;
            selectEmployeeBtn.disabled = true;
        });

        // 🔹 Cerrar modal de empleado
        closeEmployeeModal.addEventListener('click', () => {
            employeeModal.classList.add('hidden');
        });

        // 🔹 Función de búsqueda global de empleados
        async function searchEmployees() {
            const companyId = companySelect.value;
            const searchTerm = employeeSearchInput.value.trim();

            if (searchTerm.length < 3) {
                availableEmployeesTable.innerHTML =
                    `<tr><td colspan="4" class="text-center text-gray-500 p-2">Escriba al menos 3 caracteres.</td></tr>`;
                return;
            }

            availableEmployeesTable.innerHTML =
                `<tr><td colspan="4" class="text-center text-gray-500 p-2">Buscando...</td></tr>`;

            try {
                // Usamos la ruta genérica de búsqueda y le pasamos el término de búsqueda (q) y la compañía (company_id)
                const url = `${EMPLOYEE_GLOBAL_SEARCH_URL}?company_id=${companyId}&q=${searchTerm}`;
                const res = await fetch(url);
                const data = await res.json();

                if (data.length === 0) {
                    availableEmployeesTable.innerHTML =
                        `<tr><td colspan="4" class="text-center text-gray-500 p-2">No se encontraron empleados.</td></tr>`;
                    return;
                }

                availableEmployeesTable.innerHTML = data.map(e => `
                    <tr>
                        <td class="p-2"><input type="radio" name="employee_select" value="${e.id}" data-name="${e.first_name} ${e.last_name}" data-doc="${e.document_id ?? ''}"></td>
                        <td class="p-2">${e.id}</td>
                        <td class="p-2">${e.first_name} ${e.last_name}</td>
                        <td class="p-2">${e.document_id ?? 'N/A'}</td>
                    </tr>
                `).join('');

            } catch (error) {
                console.error('Error en la búsqueda de empleados:', error);
                availableEmployeesTable.innerHTML =
                    `<tr><td colspan="4" class="text-center text-red-500 p-2">Error al buscar empleados.</td></tr>`;
            }
        }

        // 🔹 Evento de búsqueda con debounce
        employeeSearchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(searchEmployees, 300); // 300ms de retraso
        });

        // 🔹 Habilitar botón al seleccionar radio
        availableEmployeesTable.addEventListener('change', (e) => {
            if (e.target.name === 'employee_select') {
                selectEmployeeBtn.disabled = false;
                selectedEmployee = {
                    id: e.target.value,
                    name: e.target.dataset.name,
                };
            }
        });

        // 🔹 Botón Seleccionar (Asignar empleado al formulario)
        selectEmployeeBtn.addEventListener('click', () => {
            if (selectedEmployee) {
                selectedEmployeeId.value = selectedEmployee.id;
                selectedEmployeeName.value = selectedEmployee.name;
                employeeModal.classList.add('hidden');
            }
        });

        // ===================================
        // 💻 LÓGICA DE EQUIPOS (sin cambios)
        // ===================================
        // ... (Tu código JavaScript para el modal de equipos (loadAvailableDevices, openModal, etc.) sigue aquí) ...

        openModal.addEventListener('click', async () => {
            if (!companySelect.value) return alert('Selecciona primero una empresa.');
            modal.classList.remove('hidden');
            // Forzar la carga inicial al abrir el modal
            searchInput.value = '';
            typeFilter.value = '';
            await loadAvailableDevices();
        });

        async function loadAvailableDevices() {
            const companyId = companySelect.value;
            const typeId = typeFilter.value;
            const search = searchInput.value.toLowerCase();

            availableTable.innerHTML =
                `<tr><td colspan="5" class="p-2 text-center text-gray-500">Cargando equipos...</td></tr>`;

            try {
                const url = `${DEVICES_FILTER_URL}?company_id=${companyId}&type_id=${typeId}`;
                const res = await fetch(url);
                const data = await res.json();
                availableDevices = data;

                // Filtrado por búsqueda
                const filtered = availableDevices.filter(d =>
                    d.asset_tag?.toLowerCase().includes(search) ||
                    d.brand?.toLowerCase().includes(search) ||
                    d.model?.toLowerCase().includes(search) ||
                    d.type?.toLowerCase().includes(search)
                );

                if (filtered.length === 0) {
                    availableTable.innerHTML =
                        `<tr><td colspan="5" class="p-2 text-center text-gray-500">No hay equipos disponibles o no coinciden con la búsqueda.</td></tr>`;
                    return;
                }

                // Renderizar la tabla de dispositivos disponibles
                availableTable.innerHTML = filtered.map(d => {
                    const isChecked = selectedDevices.some(sd => sd.id === d.id) ? 'checked' : '';

                    return `
                <tr>
                    <td class="p-2"><input type="checkbox" value="${d.id}" ${isChecked}></td>
                    <td class="p-2">${d.type}</td>
                    <td class="p-2">${d.brand}</td>
                    <td class="p-2">${d.model}</td>
                    <td class="p-2">${d.asset_tag}</td>
                </tr>
            `;
                }).join('');
            } catch (error) {
                console.error('Error al cargar dispositivos:', error);
                availableTable.innerHTML =
                    `<tr><td colspan="5" class="p-2 text-center text-red-500">Error al cargar dispositivos.</td></tr>`;
            }
        }

        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(loadAvailableDevices, 300);
        });
        typeFilter.addEventListener('change', loadAvailableDevices);

        addSelectedBtn.addEventListener('click', () => {
            const checked = availableTable.querySelectorAll('input[type="checkbox"]');

            checked.forEach(chk => {
                const deviceId = parseInt(chk.value);
                if (!chk.checked) {
                    selectedDevices = selectedDevices.filter(d => d.id !== deviceId);
                }
            });

            checked.forEach(chk => {
                if (chk.checked) {
                    const deviceId = parseInt(chk.value);
                    const device = availableDevices.find(d => d.id === deviceId);

                    if (device && !selectedDevices.some(sd => sd.id === device.id)) {
                        selectedDevices.push(device);
                    }
                }
            });

            renderTable();
            modal.classList.add('hidden');
        });

        function renderTable() {
            if (selectedDevices.length === 0) {
                table.innerHTML = `<tr class="text-gray-500 text-center">
                    <td colspan="4" class="p-2">Aún no se han agregado equipos.</td>
                </tr>`;
                deviceIdsInput.value = '[]';
                return;
            }

            table.innerHTML = '';
            selectedDevices.forEach((d, i) => {
                table.innerHTML += `
                    <tr>
                        <td class="p-2">${d.type}</td>
                        <td class="p-2">${d.brand} ${d.model}</td>
                        <td class="p-2">${d.asset_tag}</td>
                        <td class="p-2 text-right">
                            <button type="button" class="text-red-500 hover:underline" onclick="removeDevice(${d.id})">Eliminar</button>
                        </td>
                    </tr>
                `;
            });
            deviceIdsInput.value = JSON.stringify(selectedDevices.map(d => d.id));
        }

        window.removeDevice = function(deviceIdToRemove) {
            selectedDevices = selectedDevices.filter(d => d.id !== deviceIdToRemove);
            renderTable();
        };

        closeModal.addEventListener('click', () => modal.classList.add('hidden'));
    </script>
</x-layouts.app>
