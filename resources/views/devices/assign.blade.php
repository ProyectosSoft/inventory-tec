<x-layouts.app>
  <div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-semibold mb-6">Asignar dispositivo</h1>

    <div class="bg-white shadow rounded p-6 border">
      <form method="POST" action="{{ route('devices.assignments.store', $device) }}">
        @csrf

        {{-- Info del dispositivo --}}
        <div class="mb-6 border-b pb-4">
          <p class="text-gray-700 text-sm">Dispositivo:</p>
          <p class="font-semibold">{{ $device->asset_tag }} — {{ $device->type->name ?? '' }}</p>
          <p class="text-gray-500 text-sm">Empresa: {{ $device->company->name ?? '—' }}</p>
        </div>

        {{-- Selección de usuario --}}
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Usuario asignado</label>
          <select name="user_id" class="w-full border rounded px-3 py-2" required>
            <option value="">Seleccione un usuario</option>
            @foreach($users as $u)
              <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
          </select>
          @error('user_id')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- Fecha de asignación --}}
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Fecha de asignación</label>
          <input type="date" name="assigned_at" value="{{ old('assigned_at', now()->toDateString()) }}" class="w-full border rounded px-3 py-2">
          @error('assigned_at')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- Notas --}}
        <div class="mb-6">
          <label class="block text-sm font-medium mb-1">Notas</label>
          <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
          <a href="{{ route('devices.show', $device) }}" class="border rounded px-4 py-2">Cancelar</a>
          <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</x-layouts.app>
