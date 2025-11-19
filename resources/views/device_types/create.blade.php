<x-layouts.app>
  <h1 class="text-2xl font-semibold mb-6">Nuevo tipo de dispositivo</h1>
  @include('device_types._form', [
      'action' => route('device-types.store'),
      'method' => 'POST',
      'type'   => $type,
  ])
</x-layouts.app>
