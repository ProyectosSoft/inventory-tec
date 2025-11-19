@php $s = optional($device)->specs_tree ?? []; $v = fn($p)=>data_get($s,$p); @endphp
<h2 class="text-lg font-semibold">Monitor</h2>
<div class="grid md:grid-cols-6 gap-3 mt-2">
  <input name="specs[pant][marca]"    value="{{ old('specs.pant.marca',    $v('pant.marca')) }}"    class="rounded border px-3 py-2" placeholder="Marca">
  <input name="specs[pant][modelo]"   value="{{ old('specs.pant.modelo',   $v('pant.modelo')) }}"   class="rounded border px-3 py-2" placeholder="Modelo">
  <input name="specs[pant][serie]"    value="{{ old('specs.pant.serie',    $v('pant.serie')) }}"    class="rounded border px-3 py-2" placeholder="Serie">
  <input name="specs[pant][pulgadas]" value="{{ old('specs.pant.pulgadas', $v('pant.pulgadas')) }}" class="rounded border px-3 py-2" placeholder="Pulgadas">
  <input name="specs[pant][conexion]" value="{{ old('specs.pant.conexion', $v('pant.conexion')) }}" class="rounded border px-3 py-2" placeholder="Conexión">
  <input name="specs[pant][otras]"    value="{{ old('specs.pant.otras',    $v('pant.otras')) }}"    class="rounded border px-3 py-2" placeholder="Otras características">
</div>
