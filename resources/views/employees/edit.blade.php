<x-layouts.app>
  <div class="mb-4 flex items-center justify-between">
    <h1 class="text-xl font-semibold">Editar empleado</h1>
    <a href="{{ route('employees.index') }}" class="rounded border px-3 py-2">Volver</a>
  </div>

  @include('employees._form', ['employee' => $employee])
</x-layouts.app>
