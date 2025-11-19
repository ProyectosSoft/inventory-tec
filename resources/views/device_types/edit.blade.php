<x-layouts.app>
  <h1 class="text-2xl font-semibold mb-6">Editar tipo: {{ $type->name }}</h1>
  @include('device_types._form', [
      'action' => route('device-types.update',$type),
      'method' => 'PUT',
      'type'   => $type,
  ])
</x-layouts.app>
