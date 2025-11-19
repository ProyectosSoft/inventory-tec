<x-layouts.app>
  <div class="mb-4 flex items-center justify-between">
    <h1 class="text-xl font-semibold">Nueva empresa</h1>
    <a href="{{ route('companies.index') }}" class="rounded border px-3 py-2">Volver</a>
  </div>

  @include('companies._form')
</x-layouts.app>

