@php
    // Helper para repoblar valores (prioriza old() y luego valores del dispositivo)
    $old = fn($k, $def = '') => old($k, data_get($device ?? null, $k, $def));

    // Tipo inicial: old() → tipo del dispositivo → primer tipo del catálogo
    $initTypeId = old('device_type_id', $defaultType?->id ?? ($types->first()->id ?? null));
    $initTypeKey = old('type', $defaultType?->key ?? ($types->first()->key ?? 'pc'));
@endphp

@if ($errors->any())
    <div class="mb-4 rounded border border-red-500/30 bg-red-500/10 p-3 text-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<script>
    window.devForm = function() {
        return {
            type: '{{ $initTypeKey }}',

            init() {
                this.syncDeviceTypeId();
                // ⚙️ Solo recargar dinámicamente si estamos en modo CREATE
                @if (!isset($device))
                    this.swapPartial();
                @endif
            },

            // swapPartial() {
            //     const t = this.type || 'pc';
            //     const url = `{{ url('/blade/devices/partials/fields') }}/${t}?_=${Date.now()}`;
            //     fetch(url)
            //         .then(r => r.ok ? r.text() : Promise.reject())
            //         .then(html => {
            //             document.getElementById('type-fields').innerHTML = html;
            //         })
            //         .catch(() => {
            //             /* fallback SSR */
            //         });
            // },
            swapPartial() {
                const t = this.type || 'pc';
                const url = `{{ url('/blade/devices/partials/fields') }}/${t}?_=${Date.now()}`;
                fetch(url)
                    .then(r => r.ok ? r.text() : Promise.reject())
                    .then(html => {
                        document.getElementById('type-fields').innerHTML = html;
                    })
                    .catch(() => {
                        /* fallback SSR */
                    });
            },

            syncDeviceTypeId() {
                const sel = this.$root.querySelector('select[name="type"]');
                const opt = sel?.selectedOptions?.[0];
                const id = opt?.dataset?.id || '';
                const hid = this.$root.querySelector('input[name="device_type_id"]');
                if (hid) hid.value = id;
            },

            onTypeChange() {
                this.syncDeviceTypeId();
                this.swapPartial();
            }
        }
    }
</script>

<form method="POST" action="{{ $action }}" x-data="devForm()" x-init="init()">
    @csrf
    @if (in_array($method ?? 'POST', ['PUT', 'PATCH']))
        @method($method)
    @endif

    {{-- Campo oculto con el ID real del tipo --}}
    <input type="hidden" name="device_type_id" value="{{ $initTypeId }}">

    <div class="grid md:grid-cols-3 gap-4">
        {{-- Empresa --}}
        <div>
            <label class="text-sm">Empresa</label>
            <select name="company_id" class="w-full rounded border px-3 py-2" required>
                <option value="">Seleccione</option>
                @foreach ($companies as $c)
                    <option value="{{ $c->id }}" @selected($old('company_id', $device->company_id ?? null) == $c->id)>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tipo --}}
        <div>
            <label class="text-sm">Tipo</label>
            {{-- Cada opción usa key (para carga dinámica) y data-id (para el ID real) --}}
            <select name="type" x-model="type" class="w-full rounded border px-3 py-2" required
                @change="onTypeChange()">
                @foreach ($types as $t)
                    <option value="{{ $t->key }}" data-id="{{ $t->id }}" @selected($initTypeKey == $t->key)>
                        {{ $t->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Estado --}}
        <div>
            <label class="text-sm">Estado</label>
            <select name="status" class="w-full rounded border px-3 py-2">
                @foreach (['active' => 'Activo', 'in_repair' => 'En reparación', 'lost' => 'Perdido', 'retired' => 'Retirado'] as $k => $v)
                    <option value="{{ $k }}" @selected($old('status', $device->status ?? 'active') == $k)>
                        {{ $v }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Asset Tag --}}
        <div>
            <label class="text-sm">Asset Tag</label>
            <input name="asset_tag" value="{{ $old('asset_tag', $device->asset_tag ?? '') }}"
                class="w-full rounded border px-3 py-2">
        </div>

        {{-- Fecha de compra --}}
        <div>
            <label class="text-sm">Fecha compra</label>
            <input type="date" name="purchase_date"
                value="{{ old('purchase_date', optional(optional($device ?? null)->purchase_date)->format('Y-m-d')) }}"
                class="w-full rounded border px-3 py-2">
        </div>

        {{-- Garantía --}}
        <div>
            <label class="text-sm">Garantía (meses)</label>
            <input type="number" min="0" name="warranty_months"
                value="{{ $old('warranty_months', $device->warranty_months ?? '') }}"
                class="w-full rounded border px-3 py-2">
        </div>

        {{-- Notas --}}
        <div class="md:col-span-3">
            <label class="text-sm">Notas</label>
            <textarea name="notes" rows="3" class="w-full rounded border px-3 py-2">{{ $old('notes', $device->notes ?? '') }}</textarea>
        </div>
    </div>

    {{-- Campos dinámicos por tipo --}}
    <div id="type-fields" class="mt-8">
        @include('devices.partials.fields._dynamic', [
            'schema' => $schema ?? ['groups' => []],
            'device' => $device ?? null,
        ])
    </div>

    {{-- Acciones --}}
    <div class="mt-6 flex gap-3">
        <a href="{{ route('devices.index') }}" class="rounded border px-4 py-2">Cancelar</a>
        <button class="rounded bg-blue-600 text-white px-4 py-2">Guardar</button>
    </div>
</form>
