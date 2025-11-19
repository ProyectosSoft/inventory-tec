@php $s = optional($device)->specs_tree ?? []; $v = fn($p)=>data_get($s,$p); @endphp

<h2 class="text-lg font-semibold">PC — Especificaciones</h2>

<label class="text-sm mt-3 block">RAM (general)</label>
<input name="specs[ram][general]" value="{{ old('specs.ram.general', $v('ram.general')) }}" class="w-full rounded border px-3 py-2">

<h3 class="mt-4 font-semibold">Memoria RAM (por slot)</h3>
@for($i=1;$i<=4;$i++)
  <div class="grid md:grid-cols-4 gap-3 mt-2">
    <input name="specs[ram][ram{{ $i }}][tipo]"       value="{{ old("specs.ram.ram$i.tipo",       $v("ram.ram$i.tipo")) }}"       class="rounded border px-3 py-2" placeholder="RAM {{ $i }} - Tipo">
    <input name="specs[ram][ram{{ $i }}][marca]"      value="{{ old("specs.ram.ram$i.marca",      $v("ram.ram$i.marca")) }}"      class="rounded border px-3 py-2" placeholder="Marca">
    <input name="specs[ram][ram{{ $i }}][tamano_gb]"  value="{{ old("specs.ram.ram$i.tamano_gb",  $v("ram.ram$i.tamano_gb")) }}"  class="rounded border px-3 py-2" placeholder="Tamaño (GB)" type="number" min="1">
    <input name="specs[ram][ram{{ $i }}][frecuencia]" value="{{ old("specs.ram.ram$i.frecuencia", $v("ram.ram$i.frecuencia")) }}" class="rounded border px-3 py-2" placeholder="Frecuencia">
  </div>
@endfor

<h3 class="mt-6 font-semibold">Procesador</h3>
<div class="grid md:grid-cols-6 gap-3">
  <input name="specs[proc][general]"   value="{{ old('specs.proc.general',   $v('proc.general')) }}"   class="md:col-span-6 rounded border px-3 py-2" placeholder="Descripción general">
  <input name="specs[proc][marca]"     value="{{ old('specs.proc.marca',     $v('proc.marca')) }}"     class="rounded border px-3 py-2" placeholder="Marca">
  <input name="specs[proc][modelo]"    value="{{ old('specs.proc.modelo',    $v('proc.modelo')) }}"    class="rounded border px-3 py-2" placeholder="Modelo">
  <input name="specs[proc][velocidad]" value="{{ old('specs.proc.velocidad', $v('proc.velocidad')) }}" class="rounded border px-3 py-2" placeholder="Velocidad">
  <input name="specs[proc][nucleos]"   value="{{ old('specs.proc.nucleos',   $v('proc.nucleos')) }}"   class="rounded border px-3 py-2" type="number" min="1" placeholder="Núcleos">
  <input name="specs[proc][hilos]"     value="{{ old('specs.proc.hilos',     $v('proc.hilos')) }}"     class="rounded border px-3 py-2" type="number" min="1" placeholder="Hilos">
</div>

<h3 class="mt-6 font-semibold">Gráficos</h3>
<div class="grid md:grid-cols-4 gap-3">
  <input name="specs[gpu][general]" value="{{ old('specs.gpu.general', $v('gpu.general')) }}" class="md:col-span-4 rounded border px-3 py-2" placeholder="Descripción general">
  @for($g=1;$g<=2;$g++)
    <input name="specs[gpu][{{ $g }}][marca]"  value="{{ old("specs.gpu.$g.marca",  $v("gpu.$g.marca")) }}"  class="rounded border px-3 py-2" placeholder="GPU {{ $g }} - Marca">
    <input name="specs[gpu][{{ $g }}][modelo]" value="{{ old("specs.gpu.$g.modelo", $v("gpu.$g.modelo")) }}" class="rounded border px-3 py-2" placeholder="Modelo">
    <input name="specs[gpu][{{ $g }}][gddr]"   value="{{ old("specs.gpu.$g.gddr",   $v("gpu.$g.gddr")) }}"   class="rounded border px-3 py-2" placeholder="GDDR">
    <input name="specs[gpu][{{ $g }}][tamano]" value="{{ old("specs.gpu.$g.tamano", $v("gpu.$g.tamano")) }}" class="rounded border px-3 py-2" placeholder="Tamaño">
  @endfor
</div>

<h3 class="mt-6 font-semibold">Almacenamiento</h3>
<input name="specs[dd][general]" value="{{ old('specs.dd.general', $v('dd.general')) }}" class="w-full rounded border px-3 py-2 mb-2" placeholder="Descripción general">
@for($d=1;$d<=4;$d++)
  <div class="grid md:grid-cols-6 gap-3">
    <input name="specs[dd][{{ $d }}][nombre]"     value="{{ old("specs.dd.$d.nombre",     $v("dd.$d.nombre")) }}"     class="md:col-span-2 rounded border px-3 py-2" placeholder="DD {{ $d }} - Nombre completo">
    <input name="specs[dd][{{ $d }}][tecnologia]" value="{{ old("specs.dd.$d.tecnologia", $v("dd.$d.tecnologia")) }}" class="rounded border px-3 py-2" placeholder="Tecnología">
    <input name="specs[dd][{{ $d }}][marca]"      value="{{ old("specs.dd.$d.marca",      $v("dd.$d.marca")) }}"      class="rounded border px-3 py-2" placeholder="Marca">
    <input name="specs[dd][{{ $d }}][modelo]"     value="{{ old("specs.dd.$d.modelo",     $v("dd.$d.modelo")) }}"     class="rounded border px-3 py-2" placeholder="Modelo">
    <input name="specs[dd][{{ $d }}][capacidad]"  value="{{ old("specs.dd.$d.capacidad",  $v("dd.$d.capacidad")) }}"  class="rounded border px-3 py-2" placeholder="Capacidad">
    <input name="specs[dd][{{ $d }}][serial]"     value="{{ old("specs.dd.$d.serial",     $v("dd.$d.serial")) }}"     class="rounded border px-3 py-2" placeholder="Serial">
  </div>
@endfor

<h3 class="mt-6 font-semibold">Información del sistema</h3>
<div class="grid md:grid-cols-3 gap-3">
  <input name="specs[sis][so]"            value="{{ old('specs.sis.so',            $v('sis.so')) }}"            class="rounded border px-3 py-2" placeholder="SO">
  <input name="specs[sis][build]"         value="{{ old('specs.sis.build',         $v('sis.build')) }}"         class="rounded border px-3 py-2" placeholder="Compilación / Versión">
  <input name="specs[sis][nombre_equipo]" value="{{ old('specs.sis.nombre_equipo', $v('sis.nombre_equipo')) }}" class="rounded border px-3 py-2" placeholder="Nombre de equipo">
  <input name="specs[sis][user_admin]"    value="{{ old('specs.sis.user_admin',    $v('sis.user_admin')) }}"    class="rounded border px-3 py-2" placeholder="Usuario Admin">
  <input name="specs[sis][user_2]"        value="{{ old('specs.sis.user_2',        $v('sis.user_2')) }}"        class="rounded border px-3 py-2" placeholder="Usuario 2">
  <input name="specs[sis][otros_users]"   value="{{ old('specs.sis.otros_users',   $v('sis.otros_users')) }}"   class="rounded border px-3 py-2" placeholder="Otros usuarios">
  <input name="specs[sis][anydesk]"       value="{{ old('specs.sis.anydesk',       $v('sis.anydesk')) }}"       class="rounded border px-3 py-2" placeholder="Código AnyDesk">
</div>

<h3 class="mt-6 font-semibold">Pantalla</h3>
<div class="grid md:grid-cols-5 gap-3">
  <input name="specs[pant][marca]"    value="{{ old('specs.pant.marca',    $v('pant.marca')) }}"    class="rounded border px-3 py-2" placeholder="Marca">
  <input name="specs[pant][modelo]"   value="{{ old('specs.pant.modelo',   $v('pant.modelo')) }}"   class="rounded border px-3 py-2" placeholder="Modelo">
  <input name="specs[pant][serie]"    value="{{ old('specs.pant.serie',    $v('pant.serie')) }}"    class="rounded border px-3 py-2" placeholder="Serie">
  <input name="specs[pant][pulgadas]" value="{{ old('specs.pant.pulgadas', $v('pant.pulgadas')) }}" class="rounded border px-3 py-2" placeholder="Pulgadas">
  <input name="specs[pant][conect]"   value="{{ old('specs.pant.conect',   $v('pant.conect')) }}"   class="rounded border px-3 py-2" placeholder="Conectividad">
</div>
