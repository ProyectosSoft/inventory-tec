<x-layouts.app>
  <h1 class="text-2xl font-semibold mb-6">Editar dispositivo</h1>
  @include('devices.partials._form', [
      'action' => route('devices.update',$device),
      'method' => 'PUT',
      'device' => $device,
      'companies' => $companies,
  ])
</x-layouts.app>
