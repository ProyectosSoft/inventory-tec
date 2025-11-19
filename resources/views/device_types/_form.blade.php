@php
  $schema = $type->schema ?: ['groups'=>[]];
@endphp

@if ($errors->any())
  <div class="mb-4 rounded border border-red-500/30 bg-red-500/10 p-3 text-sm">
    <ul class="list-disc pl-5">
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif

<script>
  // Fuerza mayúsculas y límite de longitud en el campo "code"
  function upcaseAndLimit(el, max = 4) {
    el.value = el.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, max);
  }
</script>

<form method="POST" action="{{ $action }}" x-data="schemaBuilder({{ json_encode($schema) }})" x-init="init()">
  @csrf
  @if(in_array($method ?? 'POST',['PUT','PATCH'])) @method($method) @endif

  <div class="grid md:grid-cols-5 gap-4 mb-6">
    <div class="md:col-span-2">
      <label class="text-sm">Key (identificador, ej. pc, phone)</label>
      <input name="key" value="{{ old('key',$type->key) }}" class="w-full rounded border px-3 py-2" required>
      <p class="mt-1 text-xs text-neutral-500">Usada en rutas/parciales: debe ser única.</p>
    </div>

    <div class="md:col-span-2">
      <label class="text-sm">Nombre</label>
      <input name="name" value="{{ old('name',$type->name) }}" class="w-full rounded border px-3 py-2" required>
    </div>

    <div>
      <label class="text-sm">Código (abreviatura)</label>
      <input name="code"
             value="{{ old('code',$type->code) }}"
             class="w-full rounded border px-3 py-2 font-mono tracking-wider"
             placeholder="LP"
             maxlength="4"
             oninput="upcaseAndLimit(this, 4)">
      <p class="mt-1 text-xs text-neutral-500">
        2–4 caracteres. Ej.: <strong>LP</strong> para Laptop, <strong>PC</strong> para PC.
      </p>
    </div>

    <label class="flex items-center gap-2 md:col-span-5">
      <input type="checkbox" name="is_active" value="1" @checked(old('is_active',$type->is_active))>
      <span class="text-sm">Activo</span>
    </label>
  </div>

  {{-- Editor de schema --}}
  <div class="mb-4 flex items-center justify-between">
    <h2 class="text-lg font-semibold">Campos por tipo (schema)</h2>
    <div class="flex gap-2">
      <button type="button" class="px-3 py-2 rounded border" @click="addGroup()">+ Grupo</button>
      <button type="button" class="px-3 py-2 rounded border" @click="expandAll()">Expandir todo</button>
      <button type="button" class="px-3 py-2 rounded border" @click="collapseAll()">Colapsar todo</button>
    </div>
  </div>

  <template x-for="(g, gi) in schema.groups" :key="gi">
    <div class="mb-4 rounded border">
      <div class="flex items-center gap-2 p-3 bg-black/10">
        <input class="rounded border px-2 py-1 w-64" placeholder="Etiqueta del grupo" x-model="g.label">
        <input class="rounded border px-2 py-1 w-32" type="number" min="1" max="6" x-model.number="g.columns" placeholder="Columnas (1-6)">
        <button type="button" class="px-2 py-1 rounded border" @click="g._open = !g._open" x-text="g._open ? 'Ocultar' : 'Mostrar'"></button>
        <div class="ml-auto flex gap-2">
          <button type="button" class="px-2 py-1 rounded border" @click="addField(gi)">+ Campo</button>
          <button type="button" class="px-2 py-1 rounded border" @click="moveUpGroup(gi)" :disabled="gi===0">↑</button>
          <button type="button" class="px-2 py-1 rounded border" @click="moveDownGroup(gi)" :disabled="gi===schema.groups.length-1">↓</button>
          <button type="button" class="px-2 py-1 rounded border" @click="removeGroup(gi)">Eliminar</button>
        </div>
      </div>

      <div class="p-3" x-show="g._open">
        <div class="grid" :class="gridCols(g.columns)">
          <template x-for="(f, fi) in g.fields" :key="fi">
            <div class="border rounded p-3">
              <div class="flex items-center gap-2 mb-2">
                <input class="rounded border px-2 py-1 w-40 font-mono" placeholder="key (ej: cpu.model)" x-model="f.key">
                <input class="rounded border px-2 py-1 w-52" placeholder="Etiqueta" x-model="f.label">
              </div>
              <div class="flex items-center gap-2 mb-2">
                <select class="rounded border px-2 py-1" x-model="f.type">
                  <option value="text">text</option>
                  <option value="number">number</option>
                  <option value="date">date</option>
                  <option value="textarea">textarea</option>
                  <option value="select">select</option>
                </select>
                <input class="rounded border px-2 py-1 w-72" placeholder="Reglas de validación (Laravel)" x-model="f.rules">
              </div>
              <div class="flex gap-2">
                <button type="button" class="px-2 py-1 rounded border" @click="moveUpField(gi,fi)" :disabled="fi===0">↑</button>
                <button type="button" class="px-2 py-1 rounded border" @click="moveDownField(gi,fi)" :disabled="fi===g.fields.length-1">↓</button>
                <button type="button" class="px-2 py-1 rounded border" @click="removeField(gi,fi)">Eliminar</button>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </template>

  {{-- campo oculto con el JSON final --}}
  <input type="hidden" name="schema" :value="json()">

  <div class="mt-6 flex gap-3">
    <a href="{{ route('device-types.index') }}" class="rounded border px-4 py-2">Cancelar</a>
    <button class="rounded bg-blue-600 text-white px-4 py-2">Guardar</button>
  </div>
</form>

<script>
function schemaBuilder(initial) {
  return {
    schema: initial ?? { groups: [] },

    init() {
      (this.schema.groups || []).forEach(g => g._open = (g._open ?? true));
      if (!this.schema.groups || !this.schema.groups.length) this.addGroup();
    },

    gridCols(n){
      n = Number(n || 1);
      const cls = {1:'grid-cols-1',2:'grid-cols-2',3:'grid-cols-3',4:'grid-cols-4',5:'grid-cols-5',6:'grid-cols-6'};
      return 'grid gap-3 ' + (cls[n] ?? 'grid-cols-1');
    },

    addGroup(){
      this.schema.groups.push({ label: 'Nuevo grupo', columns: 3, fields: [], _open: true });
    },
    removeGroup(i){ this.schema.groups.splice(i,1); },
    moveUpGroup(i){ if(i>0){ const t=this.schema.groups[i]; this.schema.groups.splice(i,1); this.schema.groups.splice(i-1,0,t);} },
    moveDownGroup(i){ if(i<this.schema.groups.length-1){ const t=this.schema.groups[i]; this.schema.groups.splice(i,1); this.schema.groups.splice(i+1,0,t);} },

    addField(i){
      this.schema.groups[i].fields.push({ key: '', label: '', type: 'text', rules: 'nullable' });
    },
    removeField(gi,fi){ this.schema.groups[gi].fields.splice(fi,1); },
    moveUpField(gi,fi){
      if(fi>0){ const g=this.schema.groups[gi]; const t=g.fields[fi]; g.fields.splice(fi,1); g.fields.splice(fi-1,0,t); }
    },
    moveDownField(gi,fi){
      const g=this.schema.groups[gi];
      if(fi<g.fields.length-1){ const t=g.fields[fi]; g.fields.splice(fi,1); g.fields.splice(fi+1,0,t); }
    },

    expandAll(){ (this.schema.groups||[]).forEach(g => g._open = true); },
    collapseAll(){ (this.schema.groups||[]).forEach(g => g._open = false); },

    json(){
      return JSON.stringify({
        groups: (this.schema.groups||[]).map(g => ({ label:g.label, columns:g.columns, fields:g.fields }))
      });
    }
  }
}
</script>
