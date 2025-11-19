{{-- resources/views/companies/index.blade.php --}}
<x-layouts.app>
  {{-- Header --}}
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Empresas</h1>
    <a href="{{ route('companies.create') }}" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition">
      Nueva
    </a>
  </div>

  {{-- Alertas flash antiguas (por compatibilidad) --}}
  @if (session('ok') || session('success'))
    <div class="mb-4 rounded border border-green-400/40 bg-green-400/10 p-3 text-sm">
      {{ session('ok') ?? session('success') }}
    </div>
  @endif

  {{-- Buscador --}}
  <form class="mb-4 flex gap-2" method="GET">
    <input
      type="text"
      name="q"
      value="{{ request('q') }}"
      placeholder="Buscar por nombre o NIT"
      class="w-full rounded border px-3 py-2"
    >
    <button class="px-3 py-2 rounded border">Buscar</button>
    <a href="{{ route('companies.index') }}" class="px-3 py-2 rounded border">Limpiar</a>
  </form>

  {{-- Tabla de datos --}}
  <div class="overflow-x-auto rounded border">
    <table class="min-w-full text-sm">
      <thead class="bg-black/10">
        <tr>
          <th class="px-3 py-2 text-left">ID</th>
          <th class="px-3 py-2 text-left">Nombre</th>
          <th class="px-3 py-2 text-left">NIT</th>
          <th class="px-3 py-2 text-left">Ciudad</th>
          <th class="px-3 py-2 text-left">Estado</th>
          <th class="px-3 py-2 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($companies as $c)
          <tr class="border-t hover:bg-gray-50">
            <td class="px-3 py-2">{{ $c->id }}</td>
            <td class="px-3 py-2">
              <a href="{{ route('companies.show',$c) }}" class="hover:underline text-blue-700">
                {{ $c->name }}
              </a>
            </td>
            <td class="px-3 py-2">{{ $c->tax_id ?? '—' }}</td>
            <td class="px-3 py-2">{{ $c->city ?? '—' }}</td>
            <td class="px-3 py-2">
              @php $map = ['active'=>'Activa','inactive'=>'Inactiva']; @endphp
              <span class="{{ $c->status === 'active' ? 'text-green-700' : 'text-red-600' }}">
                {{ $map[$c->status] ?? ucfirst($c->status ?? '—') }}
              </span>
            </td>
            <td class="px-3 py-2 text-right">
              <a href="{{ route('companies.edit',$c) }}" class="px-2 py-1 rounded border inline-block mr-1 hover:bg-gray-100 transition">
                Editar
              </a>

              {{-- ⚠️ Eliminación protegida con SweetAlert --}}
              <form action="{{ route('companies.destroy',$c) }}" method="POST" class="inline delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-2 py-1 rounded border inline-block hover:bg-red-50 text-red-700 transition">
                  Eliminar
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-3 py-6 text-center text-sm opacity-70">Sin registros</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $companies->links() }}</div>

  {{-- ⚡ SweetAlert2 Confirmación de eliminación --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', e => {
          e.preventDefault();
          Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción eliminará la empresa de forma permanente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit();
            }
          });
        });
      });
    });
  </script>
</x-layouts.app>
