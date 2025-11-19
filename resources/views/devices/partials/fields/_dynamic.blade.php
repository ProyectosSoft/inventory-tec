{{-- resources/views/devices/partials/dynamic.blade.php --}}
@php
    /**
     * Espera:
     *  - $schema = ['groups' => [
     *        [
     *          'label' => 'Datos generales',
     *          'columns' => 3,
     *          'fields' => [
     *             ['key'=>'marca','label'=>'Marca','type'=>'text'],
     *             ['key'=>'modelo','label'=>'Modelo','type'=>'text'],
     *             ...
     *          ]
     *        ],
     *        [
     *          'label' => 'RAM por slot',
     *          'columns' => 3,
     *          'repeatable' => ['key'=>'ram.slots','min'=>1,'max'=>8,'itemLabel'=>'Slot'],
     *          'fields' => [
     *             ['key'=>'type','label'=>'Tipo','type'=>'text'],
     *             ['key'=>'size_gb','label'=>'Tamaño (GB)','type'=>'number'],
     *          ]
     *        ]
     *    ]]
     *  - Opcional: $device (si hidratas valores por defecto desde BD)
     */

    $schema = $schema ?? ['groups' => []];

    // helper para name="specs[a][b][c]" a partir de "a.b.c"
    $nameFromDot = function (string $dot) {
        return 'specs' . collect(explode('.', $dot))->reduce(fn($c, $p) => $c . "[$p]", '');
    };

    // helper para old() compatible
    $oldKey = function (string $dot) {
        return str_replace('.', '_', $dot);
    };

    // ===== Estilos temables (light/dark) -> Dark NEUTRAL-800 =====
    $panel = 'mb-8 rounded border border-slate-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 shadow-sm';
    $header = 'flex flex-wrap items-center gap-3 border-b border-slate-200 dark:border-neutral-700 px-3 py-2';
    $subpanel = 'rounded border border-slate-200 dark:border-neutral-700 p-3 bg-white dark:bg-neutral-800';

    $labelClass = 'mb-1 block text-xs text-slate-700 dark:text-slate-100';
    $textMuted = 'text-xs text-slate-500 dark:text-neutral-300';
    $chipClass =
        'inline-flex h-5 w-5 items-center justify-center rounded-full border border-slate-300 dark:border-neutral-600 text-xs text-slate-700 dark:text-slate-100';

    $inputClass = 'w-full rounded border border-slate-300 dark:border-neutral-600
                 bg-white dark:bg-neutral-800 text-slate-900 dark:text-slate-100
                 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/60 focus:border-blue-500';

    $btnBorder = 'rounded border border-slate-300 dark:border-neutral-600
                 px-3 py-1.5 text-sm text-slate-800 dark:text-slate-100
                 hover:bg-black/5 dark:hover:bg-white/10';

    $btnDanger = 'rounded border border-rose-300 dark:border-rose-700
                 px-2 py-1 text-xs text-rose-700 dark:text-rose-300
                 hover:bg-rose-50 dark:hover:bg-rose-900/30';
@endphp

@foreach ($schema['groups'] as $g)
    @php
        $label = $g['label'] ?? '';
        $cols = max(1, min(6, (int) ($g['columns'] ?? 3)));
        $isRep = isset($g['repeatable']);

        // Grid responsivo respetando $cols
        $grid = match ($cols) {
            1 => 'grid gap-4 grid-cols-1',
            2 => 'grid gap-4 grid-cols-1 sm:grid-cols-2',
            3 => 'grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3',
            4 => 'grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-4',
            5 => 'grid gap-4 grid-cols-1 sm:grid-cols-3 md:grid-cols-5',
            default => 'grid gap-4 grid-cols-1 sm:grid-cols-3 md:grid-cols-6',
        };
    @endphp

    {{-- === GRUPO REPETIBLE (p. ej. RAM por slot) === --}}
    @if ($isRep)
        @php
            $repKey = data_get($g, 'repeatable.key'); // p.ej. "ram.slots"
            $min = (int) data_get($g, 'repeatable.min', 1);
            $max = (int) data_get($g, 'repeatable.max', 20);
            $itemLbl = data_get($g, 'repeatable.itemLabel', 'Ítem');
            $baseName = $nameFromDot($repKey); // "specs[ram][slots]"

            // inicialización desde old(); si no, al menos una fila vacía basada en fields
            $oldRows = old($oldKey($repKey));

            // Intentar hidratar desde DB si no hay old()
            if (!$oldRows && isset($device)) {
                $prefix = preg_replace('/\.\w+$/', '.', $repKey);

                $grouped = collect($device->specs_flat ?? [])
                    ->filter(fn($_, $k) => str_starts_with($k, $prefix))
                    ->groupBy(function ($_, $k) {
                        // Detectar índice numérico en cualquier posición
                        $parts = explode('.', $k);
                        foreach ($parts as $p) {
                            if (is_numeric($p)) {
                                return (int) $p;
                            }
                        }
                        return 0;
                    })
                    ->map(function ($items) use ($g) {
                        // 👈 aquí añadimos "use ($g)"
                        $mapped = [];

                        // Convertir lista de valores en arreglo asociativo si vienen sin clave
                        $i = 0;
                        foreach ($items as $k => $v) {
                            $parts = explode('.', $k);
                            $last = end($parts);
                            if (!is_string($last) || is_numeric($last)) {
                                // asigna claves del esquema si hay desajuste (por orden)
                                $fieldKeys = collect($g['fields'] ?? [])
                                    ->pluck('key')
                                    ->values();
                                $mapped[$fieldKeys[$i] ?? $i] = $v;
                            } else {
                                $mapped[$last] = $v;
                            }
                            $i++;
                        }

                        return $mapped;
                    })

                    ->filter(fn($arr) => !empty(array_filter($arr))) // elimina filas vacías
                    ->values()
                    ->toArray();

                $oldRows = $grouped ?: null;
            }

            $initial = $oldRows ?: [
                collect($g['fields'] ?? [])
                    ->mapWithKeys(fn($f) => [$f['key'] => ''])
                    ->all(),
            ];

            $blankRow = collect($g['fields'] ?? [])
                ->mapWithKeys(fn($f) => [$f['key'] => ''])
                ->all();
        @endphp
        {{-- @if (config('app.debug'))
            <pre class="text-xs bg-gray-100 dark:bg-neutral-800 p-2 rounded">
    {{ json_encode($initial, JSON_PRETTY_PRINT) }}
  </pre>
        @endif --}}

        <section x-data="{
            rows: @js($initial),
            add() { if (this.rows.length < {{ $max }}) this.rows.push(@js($blankRow)); },
            del(i) { if (this.rows.length > {{ $min }}) this.rows.splice(i, 1); }
        }" class="{{ $panel }}">
            @if (!blank($label))
                <header class="{{ $header }}">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $label }}</h3>
                    <span class="{{ $textMuted }}" x-text="`(${rows.length} / {{ $max }})`"></span>
                    <div class="ml-auto flex gap-2">
                        <button type="button" class="{{ $btnBorder }}" @click="add()"
                            :disabled="rows.length >= {{ $max }}">
                            + {{ $itemLbl }}
                        </button>
                    </div>
                </header>
            @endif

            <div class="p-3 space-y-3">
                <template x-for="(row, idx) in rows" :key="idx">
                    <article class="{{ $subpanel }}">
                        <div class="mb-2 flex items-center justify-between">
                            <div class="inline-flex items-center gap-2 {{ $textMuted }}">
                                <span class="{{ $chipClass }}" x-text="idx+1"></span>
                                <span class="text-slate-700 dark:text-slate-300">{{ $itemLbl }}</span>
                            </div>
                            <button type="button" class="{{ $btnDanger }}" @click="del(idx)"
                                :disabled="rows.length <= {{ $min }}">
                                Eliminar
                            </button>
                        </div>

                        <div class="{{ $grid }}">
                            @foreach ($g['fields'] ?? [] as $f)
                                @php
                                    $fKey = $f['key']; // ej "type" ó "size_gb"
                                    $labelF = $f['label'] ?? $fKey;
                                    $type = $f['type'] ?? 'text';
                                    // name="specs[ram][slots][__IDX__][type]"
                                    $nameT = $baseName . '[__IDX__][' . $fKey . ']';
                                @endphp

                                <div>
                                    <label class="{{ $labelClass }}">{{ $labelF }}</label>

                                    @switch($type)
                                        @case('textarea')
                                            <textarea x-model="row['{{ $fKey }}']" :name="'{{ $nameT }}'.replace('__IDX__', idx)"
                                                class="{{ $inputClass }}"></textarea>
                                        @break

                                        @case('number')
                                            <input type="number" x-model="row['{{ $fKey }}']"
                                                :name="'{{ $nameT }}'.replace('__IDX__', idx)"
                                                class="{{ $inputClass }}">
                                        @break

                                        @case('date')
                                            <input type="date" x-model="row['{{ $fKey }}']"
                                                :name="'{{ $nameT }}'.replace('__IDX__', idx)"
                                                class="{{ $inputClass }}">
                                        @break

                                        @default
                                            <input type="text" x-model="row['{{ $fKey }}']"
                                                :name="'{{ $nameT }}'.replace('__IDX__', idx)"
                                                class="{{ $inputClass }}">
                                    @endswitch
                                </div>
                            @endforeach

                        </div>
                    </article>
                </template>
            </div>
        </section>

        {{-- === GRUPO NO REPETIBLE === --}}
    @else
        <section class="{{ $panel }}">
            @if (!blank($label))
                <header class="{{ $header }}">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $label }}</h3>
                </header>
            @endif

            <div class="p-3 {{ $grid }}">
                @foreach ($g['fields'] ?? [] as $f)
                    @php
                        $dot = $f['key']; // ej. "brandcarac" o "cpu.model"
                        $name = $nameFromDot($dot); // specs[brandcarac] o specs[cpu][model]
                        $type = $f['type'] ?? 'text';
                        $labelF = $f['label'] ?? $dot;

                        // ✅ Buscar valor desde old() o desde el modelo ya cargado
                        $flat = $device->specs_flat ?? [];
                        $val = old(str_replace('.', '_', $dot), $flat[$dot] ?? ($device?->getSpecValue($dot) ?? ''));
                    @endphp

                    <div>
                        <label class="{{ $labelClass }}">{{ $labelF }}</label>

                        @switch($type)
                            @case('textarea')
                                <textarea name="{{ $name }}" class="{{ $inputClass }}">{{ $val }}</textarea>
                            @break

                            @case('number')
                                <input type="number" name="{{ $name }}" value="{{ $val }}"
                                    class="{{ $inputClass }}">
                            @break

                            @case('date')
                                <input type="date" name="{{ $name }}" value="{{ $val }}"
                                    class="{{ $inputClass }}">
                            @break

                            @default
                                <input type="text" name="{{ $name }}" value="{{ $val }}"
                                    class="{{ $inputClass }}">
                        @endswitch
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endforeach
