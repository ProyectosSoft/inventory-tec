<x-layouts.app :title="__('Dashboard')">

    <div class="space-y-6">

        <h1 class="text-2xl font-semibold">Dashboard por empresa</h1>

        {{-- Tarjetas por empresa --}}
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($companies as $company)
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4 flex flex-col justify-between">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">
                            {{ $company->name }}
                        </h2>

                        <dl class="space-y-2 text-sm">
                            {{-- Total equipos --}}
                            <div class="flex items-baseline justify-between">
                                <dt class="text-neutral-500 dark:text-neutral-400">
                                    Total equipos registrados
                                </dt>
                                <dd>
                                    <button
                                        type="button"
                                        class="text-blue-600 dark:text-blue-400 font-semibold hover:underline"
                                        data-chart-type="devices"
                                        data-company-name="{{ $company->name }}"
                                        data-url="{{ route('dashboard.company.devicesByType', $company) }}"
                                        onclick="loadChartFromButton(this)"
                                    >
                                        {{ $company->devices_count }}
                                    </button>
                                </dd>
                            </div>

                            {{-- Total empleados --}}
                            <div class="flex items-baseline justify-between">
                                <dt class="text-neutral-500 dark:text-neutral-400">
                                    Total empleados registrados
                                </dt>
                                <dd>
                                    <button
                                        type="button"
                                        class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline"
                                        data-chart-type="employees"
                                        data-company-name="{{ $company->name }}"
                                        data-url="{{ route('dashboard.company.employeesByType', $company) }}"
                                        onclick="loadChartFromButton(this)"
                                    >
                                        {{ $company->employees_count }}
                                    </button>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @empty
                <p class="text-sm text-neutral-500">
                    No hay empresas registradas aún.
                </p>
            @endforelse
        </div>

        {{-- Sección de gráfica dinámica --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-6 mt-4">
            <h2 id="chartTitle" class="font-semibold mb-1">
                Selecciona un indicador
            </h2>
            <p id="chartSubtitle" class="text-sm text-neutral-500 dark:text-neutral-400 mb-4">
                Haz clic en "Total equipos registrados" o "Total empleados registrados" de alguna empresa para ver el detalle.
            </p>

            <div class="w-full">
                <canvas id="companyChart" class="w-full max-h-[380px]"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let companyChart = null;

        async function loadChartFromButton(button) {
            const url         = button.dataset.url;
            const chartType   = button.dataset.chartType;   // 'devices' | 'employees'
            const companyName = button.dataset.companyName;

            // Título y subtítulo
            const titleEl    = document.getElementById('chartTitle');
            const subtitleEl = document.getElementById('chartSubtitle');

            if (chartType === 'devices') {
                titleEl.textContent    = `Equipos por tipo — ${companyName}`;
                subtitleEl.textContent = 'Distribución de TODOS los equipos registrados por tipo en esta empresa.';
            } else {
                titleEl.textContent    = `Equipos asignados por tipo — ${companyName}`;
                subtitleEl.textContent = 'Cantidad de equipos actualmente asignados a empleados, agrupados por tipo.';
            }

            // Mostrar estado de carga
            subtitleEl.textContent += ' (Cargando...)';

            try {
                const res  = await fetch(url);
                const data = await res.json();

                const labels = data.map(d => d.label);
                const values = data.map(d => d.value);

                const ctx = document.getElementById('companyChart').getContext('2d');

                if (companyChart) {
                    companyChart.destroy();
                }

                companyChart = new Chart(ctx, {
                    type: 'doughnut', // si luego quieres, para "employees" se puede usar 'bar'
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: [
                                '#2563eb',
                                '#22c55e',
                                '#f97316',
                                '#e11d48',
                                '#a855f7',
                                '#14b8a6',
                                '#64748b',
                            ],
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                        },
                    },
                });

                // Quitar "Cargando..."
                subtitleEl.textContent = subtitleEl.textContent.replace(' (Cargando...)', '');
            } catch (error) {
                console.error(error);
                subtitleEl.textContent = 'Ocurrió un error al cargar los datos. Intenta nuevamente.';
            }
        }
    </script>

</x-layouts.app>
