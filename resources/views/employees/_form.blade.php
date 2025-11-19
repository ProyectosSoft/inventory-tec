@php $isEdit = isset($employee); @endphp

@if ($errors->any())
  <div class="mb-4 rounded border border-red-500/30 bg-red-500/10 p-3 text-sm">
    <ul class="list-disc pl-5">
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ $isEdit ? route('employees.update',$employee) : route('employees.store') }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="grid gap-4 md:grid-cols-2 mb-6">
    <div>
      <label class="text-sm">Empresa *</label>
      <select name="company_id" required class="w-full rounded border px-3 py-2">
        <option value="">— Selecciona —</option>
        @foreach($companies as $c)
          <option value="{{ $c->id }}" @selected((int)old('company_id', $employee->company_id ?? 0) === $c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="text-sm">Código</label>
      <input name="code" value="{{ old('code',$employee->code ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Nombres *</label>
      <input name="first_name" required value="{{ old('first_name',$employee->first_name ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Apellidos</label>
      <input name="last_name" value="{{ old('last_name',$employee->last_name ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Documento</label>
      <input name="document_id" value="{{ old('document_id',$employee->document_id ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Email</label>
      <input type="email" name="email" value="{{ old('email',$employee->email ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Teléfono</label>
      <input name="phone" value="{{ old('phone',$employee->phone ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Cargo</label>
      <input name="position" value="{{ old('position',$employee->position ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Sede</label>
      <input name="site" value="{{ old('site',$employee->site ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Estado *</label>
      @php $val = old('status',$employee->status ?? 'active'); @endphp
      <select name="status" required class="w-full rounded border px-3 py-2">
        <option value="active" @selected($val==='active')>Activo</option>
        <option value="inactive" @selected($val==='inactive')>Inactivo</option>
        <option value="suspended" @selected($val==='suspended')>Suspendido</option>
      </select>
    </div>
  </div>

  <div class="flex gap-3">
    <a href="{{ route('employees.index') }}" class="rounded border px-4 py-2">Cancelar</a>
    <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
      {{ $isEdit ? 'Actualizar' : 'Crear' }}
    </button>
  </div>
</form>
