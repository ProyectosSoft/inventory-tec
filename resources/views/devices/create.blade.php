<x-layouts.app>
  <h1 class="text-2xl font-semibold mb-6">Nuevo dispositivo</h1>
  @include('devices.partials._form', [
      'action' => route('devices.store'),
      'method' => 'POST',
      'device' => null,
      'companies' => $companies,
  ])
</x-layouts.app>
