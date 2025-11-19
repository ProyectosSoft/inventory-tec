<x-layouts.app>
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Tipos de dispositivo</h1>
    <a class="px-4 py-2 rounded bg-blue-600 text-white" href="{{ route('device-types.create') }}">Nuevo</a>
  </div>

  @if (session('ok'))
    <div class="mb-4 rounded border border-green-400/40 bg-green-400/10 p-3 text-sm">
      {{ session('ok') }}
    </div>
  @endif

  <form class="mb-4 flex gap-2">
    <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por nombre o key"
           class="w-full rounded border px-3 py-2">
    <button class="px-3 py-2 rounded border">Buscar</button>
  </form>

  <div class="overflow-x-auto rounded border">
    <table class="min-w-full text-sm">
      <thead class="bg-black/10">
        <tr>
          <th class="px-3 py-2 text-left">Key</th>
          <th class="px-3 py-2 text-left">Nombre</th>
          <th class="px-3 py-2 text-left">Activo</th>
          <th class="px-3 py-2 text-left">Grupos</th>
          <th class="px-3 py-2 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($types as $t)
          <tr class="border-t">
            <td class="px-3 py-2 font-mono">{{ $t->key }}</td>
            <td class="px-3 py-2">{{ $t->name }}</td>
            <td class="px-3 py-2">{{ $t->is_active ? 'Sí' : 'No' }}</td>
            <td class="px-3 py-2">{{ count(data_get($t->schema,'groups',[])) }}</td>
            <td class="px-3 py-2 text-right">
              <a class="px-2 py-1 rounded border" href="{{ route('device-types.edit',$t) }}">Editar</a>
              <form action="{{ route('device-types.destroy',$t) }}" method="POST" class="inline"
                    onsubmit="return confirm('¿Eliminar tipo?')">
                @csrf @method('DELETE')
                <button class="px-2 py-1 rounded border">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="px-3 py-6 text-center text-sm opacity-70">Sin resultados</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $types->links() }}</div>
</x-layouts.app>
