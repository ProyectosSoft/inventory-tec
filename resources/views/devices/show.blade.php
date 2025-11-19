{{-- resources/views/devices/show.blade.php --}}
<x-layouts.app>
  <div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-semibold">
      Dispositivo #{{ $device->id }}
    </h1>
    <div class="flex gap-2">
      <a class="rounded border px-3 py-2" href="{{ route('devices.index') }}">Volver</a>
      <a class="rounded bg-blue-600 text-white px-3 py-2" href="{{ route('devices.edit', $device) }}">Editar</a>
    </div>
  </div>

  {{-- Datos generales básicos del modelo --}}
  <div class="grid md:grid-cols-3 gap-4 mb-8">
    <div class="rounded border p-4">
      <div class="text-xs text-gray-400">Empresa</div>
      <div class="font-medium">{{ $device->company?->name ?? '—' }}</div>
    </div>
    <div class="rounded border p-4">
      <div class="text-xs text-gray-400">Tipo</div>
      <div class="font-medium">{{ $type?->name ?? strtoupper($device->type) }}</div>
    </div>
    <div class="rounded border p-4">
      <div class="text-xs text-gray-400">Estado</div>
      <div class="font-medium">
        @php $labels = ['active'=>'Activo','in_repair'=>'En reparación','lost'=>'Perdido','retired'=>'Retirado']; @endphp
        {{ $labels[$device->status] ?? $device->status }}
      </div>
    </div>

    <div class="rounded border p-4">
      <div class="text-xs text-gray-400">Asset Tag</div>
      <div class="font-medium">{{ $device->asset_tag ?: '—' }}</div>
    </div>
    <div class="rounded border p-4">
      <div class="text-xs text-gray-400">Fecha compra</div>
      <div class="font-medium">
        {{ optional($device->purchase_date)->format('d/m/Y') ?? '—' }}
      </div>
    </div>
    <div class="rounded border p-4">
      <div class="text-xs text-gray-400">Garantía (meses)</div>
      <div class="font-medium">{{ $device->warranty_months ?: '—' }}</div>
    </div>
  </div>

  @php
    // Helpers dinámicos
    $get = fn(string $key) => $specMap[$key] ?? '—';

    $collect = function(string $prefix, array $children) use ($specMap) {
        $rows = [];
        foreach ($specMap as $k => $v) {
            if (str_starts_with($k, $prefix . '.')) {
                $parts = explode('.', $k);
                if (count($parts) >= 3 && is_numeric($parts[1])) {
                    $idx = (int) $parts[1];
                    $leaf = implode('.', array_slice($parts, 2));
                    $rows[$idx]['#'] = $idx;
                    $rows[$idx][$leaf] = $v;
                }
            }
        }
        ksort($rows);
        foreach ($rows as &$r) {
            foreach ($children as $c) {
                if (!isset($r[$c])) $r[$c] = '—';
            }
        }
        return array_values($rows);
    };
  @endphp

  {{-- Render dinámico según el schema --}}
  @foreach(($schema['groups'] ?? []) as $group)
    <section class="mb-8">
      @if(!empty($group['label']))
        <h2 class="text-lg font-semibold mb-3">{{ $group['label'] }}</h2>
      @endif

      @php
        $isRep = isset($group['repeatable']);
      @endphp

      {{-- Bloque repetible --}}
      @if($isRep)
        @php
          $base = data_get($group, 'repeatable.key');
          $fields = $group['fields'] ?? [];
          $children = array_column($fields, 'key');

          // 🔍 Detectar el prefijo real de las claves (multinivel: gpu, gpu.units, storage.disks, etc.)
          $parts = explode('.', $base);
          $possiblePrefixes = [];
          for ($i = count($parts); $i > 0; $i--) {
              $possiblePrefixes[] = implode('.', array_slice($parts, 0, $i));
          }

          $foundPrefix = null;
          foreach ($possiblePrefixes as $p) {
              foreach (array_keys($specMap) as $k) {
                  if (str_starts_with($k, $p . '.')) {
                      $foundPrefix = $p;
                      break 2;
                  }
              }
          }

          $prefix = $foundPrefix ?? $parts[0];
          $rows = $collect($prefix, $children);
        @endphp

        <div class="rounded border p-4">
          <div class="text-xs text-gray-400 mb-2">
            {{ $group['label'] ?? ucwords(str_replace('.', ' ', $prefix)) }}
          </div>

          @if(empty($rows))
            <div class="text-sm text-gray-600">—</div>
          @else
            <div class="overflow-x-auto">
              <table class="min-w-full text-sm">
                <thead>
                  <tr class="text-left">
                    <th class="py-2 pr-4">#</th>
                    @foreach($children as $c)
                      <th class="py-2 pr-4">{{ ucwords(str_replace(['_','.'],' ', $c)) }}</th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                  @foreach($rows as $r)
                    <tr class="border-t">
                      <td class="py-2 pr-4">{{ $r['#'] }}</td>
                      @foreach($children as $c)
                        <td class="py-2 pr-4">{{ $r[$c] ?? '—' }}</td>
                      @endforeach
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>

      {{-- Campos normales --}}
      @else
        <div class="grid md:grid-cols-3 gap-4">
          @foreach(($group['fields'] ?? []) as $f)
            @php $key = $f['key'] ?? ''; @endphp
            <div class="rounded border p-4">
              <div class="text-xs text-gray-400">{{ $f['label'] ?? $key }}</div>
              <div class="font-medium">
                {{ $get($key) }}
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </section>
  @endforeach

  {{-- Notas --}}
  @if(!empty($device->notes))
    <div class="rounded border p-4">
      <div class="text-xs text-gray-400 mb-1">Notas</div>
      <div class="whitespace-pre-line">{{ $device->notes }}</div>
    </div>
  @endif
</x-layouts.app>
