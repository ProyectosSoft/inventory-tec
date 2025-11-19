<x-layouts.app>
  <div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-semibold mb-6">Registrar devolución</h1>

    <div class="bg-white shadow rounded p-6 border">
      <form method="POST" action="{{ route('devices.assignments.update', [$device, $assignment]) }}">
        @csrf
        @method('PATCH')

        {{-- Información actual --}}
        <div class="mb-6 border-b pb-4">
          <p class="text-gray-700 text-sm">Dispositivo:</p>
          <p class="font-semibold">{{ $device->asset_tag }} — {{ $device->type->name ?? '' }}</p>
          <p class="text-gray-500 text-sm">Usuario actual: {{ $assignment->user->name ?? '—' }}</p>
        </div>

        {{-- Fecha de devolución --}}
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Fecha de devolución</label>
          <input type="date" name="returned_at" value="{{ old('returned_at', now()->toDateString()) }}" class="w-full border rounded px-3 py-2">
        </div>

        {{-- Notas --}}
        <div class="mb-6">
          <label class="block text-sm font-medium mb-1">Notas</label>
          <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ old('notes', $assignment->notes) }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
          <a href="{{ route('devices.show', $device) }}" class="border rounded px-4 py-2">Cancelar</a>
          <button type="submit" class="bg-green-600 text-white rounded px-4 py-2">Confirmar devolución</button>
        </div>
      </form>
    </div>
  </div>
</x-layouts.app>
