@php $isEdit = isset($company); @endphp

@if ($errors->any())
  <div class="mb-4 rounded border border-red-500/30 bg-red-500/10 p-3 text-sm">
    <ul class="list-disc pl-5">
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif

{{-- helpers front: upper + sugerir code desde name --}}
{{-- <script>
  function upcaseAndLimit(el, max = 6) {
    el.value = (el.value || '').toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, max);
  }
  function suggestCode() {
    const name = document.querySelector('input[name="name"]')?.value || '';
    const codeEl = document.querySelector('input[name="code"]');
    if (!codeEl || codeEl.value.trim() !== '') return;
    // toma primeras letras de hasta 2 palabras, si no, primeras 3 letras
    const words = name.trim().toUpperCase().replace(/[^A-Z0-9 ]/g,'').split(/\s+/).filter(Boolean);
    let guess = '';
    if (words.length >= 2) {
      guess = (words[0][0] || '') + (words[1][0] || '');
    } else if (words.length === 1) {
      guess = words[0].slice(0, 3);
    }
    codeEl.value = guess.slice(0, 6);
  }
  document.addEventListener('alpine:init', suggestCode);
</script> --}}

<form method="POST" action="{{ $isEdit ? route('companies.update',$company) : route('companies.store') }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="grid gap-4 md:grid-cols-3 mb-6">
    <div class="md:col-span-2">
      <label class="text-sm">Nombre comercial *</label>
      <input
        name="name"
        value="{{ old('name',$company->name ?? '') }}"
        required
        class="w-full rounded border px-3 py-2"
        onblur="suggestCode()"
      >
      <p class="mt-1 text-xs text-neutral-500">Se usa para mostrar la empresa.</p>
    </div>

    <div>
      <label class="text-sm">Código (abreviatura)</label>
      <input
        name="code"
        value="{{ old('code',$company->code ?? '') }}"
        class="w-full rounded border px-3 py-2 font-mono tracking-wider"
        placeholder="EC"
        maxlength="6"
        {{-- oninput="upcaseAndLimit(this, 6)" --}}
      >
      <p class="mt-1 text-xs text-neutral-500">
        2–6 caracteres (A–Z/0–9). Se usa en <strong>Asset Tag</strong> (ej.: <code>EC</code> → <code>EC_LP_0001</code>).
      </p>
    </div>

    <div class="md:col-span-2">
      <label class="text-sm">Razón social</label>
      <input name="legal_name" value="{{ old('legal_name',$company->legal_name ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">NIT / Tax ID</label>
      <input name="tax_id" value="{{ old('tax_id',$company->tax_id ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Email</label>
      <input type="email" name="email" value="{{ old('email',$company->email ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Teléfono</label>
      <input name="phone" value="{{ old('phone',$company->phone ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Sitio web</label>
      <input name="website" value="{{ old('website',$company->website ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div class="md:col-span-3">
      <label class="text-sm">Dirección</label>
      <input name="address" value="{{ old('address',$company->address ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">Ciudad</label>
      <input name="city" value="{{ old('city',$company->city ?? '') }}" class="w-full rounded border px-3 py-2">
    </div>

    <div>
      <label class="text-sm">País (ISO-2)</label>
      <input name="country" maxlength="2" value="{{ old('country',$company->country ?? '') }}" class="w-full rounded border px-3 py-2 uppercase" oninput="this.value=this.value.toUpperCase()">
    </div>

    <div>
      <label class="text-sm">Estado *</label>
      @php $val = old('status',$company->status ?? 'active'); @endphp
      <select name="status" class="w-full rounded border px-3 py-2" required>
        <option value="active" @selected($val==='active')>Activa</option>
        <option value="inactive" @selected($val==='inactive')>Inactiva</option>
      </select>
    </div>
  </div>

  <div class="flex gap-3">
    <a href="{{ route('companies.index') }}" class="rounded border px-4 py-2">Cancelar</a>
    <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
      {{ $isEdit ? 'Actualizar' : 'Crear' }}
    </button>
  </div>
</form>
