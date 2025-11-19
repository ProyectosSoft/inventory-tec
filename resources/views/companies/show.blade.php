<x-layouts.app>
  <div class="mb-4 flex items-center justify-between">
    <h1 class="text-xl font-semibold">{{ $company->name }}</h1>
    <div class="flex gap-2">
      <a href="{{ route('companies.edit',$company) }}" class="rounded border px-3 py-2">Editar</a>
      <a href="{{ route('companies.index') }}" class="rounded border px-3 py-2">Volver</a>
    </div>
  </div>

  <div class="grid gap-4 md:grid-cols-2">
    {{-- …contenido del show que ya te pasé… --}}
  </div>
</x-layouts.app>
