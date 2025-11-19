@php $s = optional($device)->specs_tree ?? []; $v = fn($p)=>data_get($s,$p); @endphp

<h2 class="text-lg font-semibold">Celular — Especificaciones</h2>

<div class="grid md:grid-cols-3 gap-3 mt-2">
  <input name="specs[carac][marca]"  value="{{ old('specs.carac.marca',  $v('carac.marca')) }}"  class="rounded border px-3 py-2" placeholder="Marca">
  <input name="specs[carac][modelo]" value="{{ old('specs.carac.modelo', $v('carac.modelo')) }}" class="rounded border px-3 py-2" placeholder="Modelo">
  <input name="specs[carac][serie]"  value="{{ old('specs.carac.serie',  $v('carac.serie')) }}"  class="rounded border px-3 py-2" placeholder="Serie">
  <input name="specs[carac][imei1]"  value="{{ old('specs.carac.imei1',  $v('carac.imei1')) }}"  class="rounded border px-3 py-2" placeholder="IMEI 1">
  <input name="specs[carac][imei2]"  value="{{ old('specs.carac.imei2',  $v('carac.imei2')) }}"  class="rounded border px-3 py-2" placeholder="IMEI 2">
</div>

<h3 class="mt-6 font-semibold">Procesador</h3>
<div class="grid md:grid-cols-5 gap-3">
  <input name="specs[proc][marca]"     value="{{ old('specs.proc.marca',     $v('proc.marca')) }}"     class="rounded border px-3 py-2" placeholder="Marca">
  <input name="specs[proc][modelo]"    value="{{ old('specs.proc.modelo',    $v('proc.modelo')) }}"    class="rounded border px-3 py-2" placeholder="Modelo">
  <input name="specs[proc][velocidad]" value="{{ old('specs.proc.velocidad', $v('proc.velocidad')) }}" class="rounded border px-3 py-2" placeholder="Velocidad">
  <input name="specs[proc][nucleos]"   value="{{ old('specs.proc.nucleos',   $v('proc.nucleos')) }}"   class="rounded border px-3 py-2" type="number" placeholder="Núcleos">
  <input name="specs[proc][hilos]"     value="{{ old('specs.proc.hilos',     $v('proc.hilos')) }}"     class="rounded border px-3 py-2" type="number" placeholder="Hilos">
</div>

<h3 class="mt-6 font-semibold">Sistema</h3>
<div class="grid md:grid-cols-4 gap-3">
  <input name="specs[sis][so]"            value="{{ old('specs.sis.so',            $v('sis.so')) }}"            class="rounded border px-3 py-2" placeholder="SO">
  <input name="specs[sis][build]"         value="{{ old('specs.sis.build',         $v('sis.build')) }}"         class="rounded border px-3 py-2" placeholder="Compilación / Versión">
  <input name="specs[sis][nombre_equipo]" value="{{ old('specs.sis.nombre_equipo', $v('sis.nombre_equipo')) }}" class="rounded border px-3 py-2" placeholder="Nombre equipo">
  <input name="specs[sis][pin]"           value="{{ old('specs.sis.pin',           $v('sis.pin')) }}"           class="rounded border px-3 py-2" placeholder="PIN">
</div>
