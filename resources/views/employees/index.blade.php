{{-- resources/views/employees/index.blade.php --}}
<x-layouts.app>
  {{-- Header --}}
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Empleados</h1>
    <a href="{{ route('employees.create') }}" class="px-4 py-2 rounded bg-blue-600 text-white">
      Nuevo
    </a>
  </div>

  {{-- Alert OK (acepta ok o success) --}}
  @if(session('ok') || session('success'))
    <div class="mb-4 rounded border border-green-400/40 bg-green-400/10 p-3 text-sm">
      {{ session('ok') ?? session('success') }}
    </div>
  @endif

  {{-- Filtros (estilo simple como device_types) --}}
  <form method="GET" class="mb-4 grid gap-2 md:grid-cols-4">
    <select name="company_id" class="w-full rounded border px-3 py-2">
      <option value="">Todas las empresas</option>
      @foreach($companies as $c)
        <option value="{{ $c->id }}" @selected(request('company_id')==$c->id)>{{ $c->name }}</option>
      @endforeach
    </select>

    <select name="status" class="w-full rounded border px-3 py-2">
      <option value="">Todos los estados</option>
      <option value="active" @selected(request('status')==='active')>Activo</option>
      <option value="inactive" @selected(request('status')==='inactive')>Inactivo</option>
      <option value="suspended" @selected(request('status')==='suspended')>Suspendido</option>
    </select>

    <div class="md:col-span-2 flex gap-2">
      <input type="text" name="search" value="{{ request('search') }}"
             placeholder="Nombre, email, código, documento…"
             class="w-full rounded border px-3 py-2">
      <button class="px-3 py-2 rounded border">Buscar</button>
      <a href="{{ route('employees.index') }}" class="px-3 py-2 rounded border">Limpiar</a>
    </div>
  </form>

  {{-- Tabla (igual look que device_types) --}}
  <div class="overflow-x-auto rounded border">
    <table class="min-w-full text-sm">
      <thead class="bg-black/10">
        <tr>
          <th class="px-3 py-2 text-left">ID</th>
          <th class="px-3 py-2 text-left">Empleado</th>
          <th class="px-3 py-2 text-left">Empresa</th>
          <th class="px-3 py-2 text-left">Email</th>
          <th class="px-3 py-2 text-left">Estado</th>
          <th class="px-3 py-2 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($q as $e)
          <tr class="border-t">
            <td class="px-3 py-2">{{ $e->id }}</td>
            <td class="px-3 py-2 font-medium">
              <a href="{{ route('employees.show',$e) }}" class="hover:underline">
                {{ $e->full_name ?: '—' }}
              </a>
              <span class="opacity-60">({{ $e->code ?: 'sin código' }})</span>
            </td>
            <td class="px-3 py-2">{{ $e->company?->name ?? '—' }}</td>
            <td class="px-3 py-2">{{ $e->email ?? '—' }}</td>
            <td class="px-3 py-2">
              @php $map = ['active'=>'Activo','inactive'=>'Inactivo','suspended'=>'Suspendido']; @endphp
              {{ $map[$e->status] ?? $e->status }}
            </td>
            <td class="px-3 py-2 text-right">
              <a href="{{ route('employees.edit',$e) }}" class="px-2 py-1 rounded border inline-block mr-1">Editar</a>

              <form action="{{ route('employees.destroy',$e) }}" method="POST" class="inline"
                    onsubmit="return confirm('¿Eliminar empleado?');">
                @csrf @method('DELETE')
                <button class="px-2 py-1 rounded border inline-block">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td class="px-3 py-6 text-center text-sm opacity-70" colspan="6">Sin registros</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $q->links() }}</div>
</x-layouts.app>
