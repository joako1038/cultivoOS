<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'

interface CatalogoVariedad {
  id: string
  nombre: string
  banco: string
  descripcion: string | null
  dias_ciclo: number
  tipo_variedad_id: string
  tipo_variedad?: {
    nombre: string
    categoria: string
  }
}

interface Sala {
  id: string
  nombre: string
  codigo: string
  tipo: string
  capacidad_macetas: number
}

interface FaseCultivo {
  id: string
  nombre: string
  codigo: string
  fotoperiodo_sugerido?: string
}

interface EstadoCultivo {
  id: string
  nombre: string
  color?: string
}

interface User {
  id: string
  name: string
  email: string
  role?: string
}

interface Props {
  salas: Sala[]
  fases: FaseCultivo[]
  estados: EstadoCultivo[]
  usuarios: User[]
  catalogoVariedades: CatalogoVariedad[]
}

const props = defineProps<Props>()

// Pestaña activa del formulario
const tabActiva = ref<'DATOS_CULTIVO' | 'VARIEDADES' | 'PLANTAS'>('DATOS_CULTIVO')

// Formulario reactivo de Inertia.js (App\Models\Cultivo + App\Models\CultivoVariedad + App\Models\Planta)
const form = useForm({
  nombre: 'Lote Principal - Ciclo 2026',
  descripcion: 'Lote de producción comercial con fertirriego orgánico y control de VPD.',
  duracion_estimada: 63,
  sala_id: props.salas[0]?.id || '',
  fase_id: props.fases[1]?.id || props.fases[0]?.id || '',
  estado_cultivo_id: props.estados[0]?.id || '',
  usuario_responsable_id: props.usuarios[0]?.id || '',
  fotoperiodo: '18/6',
  generar_plantas_automaticas: true,
  variedades: [
    {
      catalogo_variedad_id: props.catalogoVariedades[0]?.id || '',
      cantidad_plantas: 36,
      fecha_inicio: new Date().toISOString().split('T')[0],
      descripcion: 'Esquejes seleccionados de 1ra generación',
      prefijo_codigo: 'GG4',
    },
  ],
})

// Modal para alta rápida de Variedad en Catálogo
const modalNuevaVariedad = ref(false)
const formVariedad = useForm({
  nombre: '',
  banco: '',
  tipo_variedad_id: '',
  dias_ciclo: 63,
  descripcion: '',
})

// Métricas computadas
const totalPlantas = computed(() => {
  return form.variedades.reduce((sum, v) => sum + (Number(v.cantidad_plantas) || 0), 0)
})

const salaSeleccionada = computed(() => {
  return props.salas.find((s) => s.id === form.sala_id)
})

const excedeCapacidad = computed(() => {
  if (!salaSeleccionada.value || !salaSeleccionada.value.capacidad_macetas) return false
  return totalPlantas.value > salaSeleccionada.value.capacidad_macetas
})

// Acciones sobre el array de variedades
const agregarVariedad = () => {
  const defaultVar = props.catalogoVariedades[form.variedades.length % props.catalogoVariedades.length] || props.catalogoVariedades[0]
  const prefijo = defaultVar?.nombre.replace(/[^A-Za-z0-9]/g, '').slice(0, 4).toUpperCase() || 'VAR'

  form.variedades.push({
    catalogo_variedad_id: defaultVar?.id || '',
    cantidad_plantas: 12,
    fecha_inicio: new Date().toISOString().split('T')[0],
    descripcion: 'Lote secundario',
    prefijo_codigo: prefijo,
  })
}

const removerVariedad = (index: number) => {
  if (form.variedades.length > 1) {
    form.variedades.splice(index, 1)
  }
}

// Envío del formulario a Laravel
const submit = () => {
  form.post(route('cultivos.store'), {
    preserveScroll: true,
    onSuccess: () => {
      // Redirigir al detalle o dashboard
    },
  })
}

const guardarNuevaVariedadCatalogo = () => {
  formVariedad.post(route('catalogo-variedades.store'), {
    preserveScroll: true,
    onSuccess: () => {
      modalNuevaVariedad.value = false
      formVariedad.reset()
    },
  })
}
</script>

<template>
  <Head title="Alta de Cultivo & Lote Productivo" />

  <div class="min-h-screen bg-slate-900 text-slate-100 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-4xl mx-auto space-y-6">
      
      <!-- HEADER -->
      <div class="flex items-center justify-between bg-slate-950 p-6 rounded-3xl border border-slate-800 shadow-xl">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-emerald-500/20">
            🌱
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h1 class="text-xl font-extrabold text-white">Alta de Nuevo Cultivo</h1>
              <span class="text-[10px] font-mono font-bold bg-emerald-950 text-emerald-400 border border-emerald-800 px-2 py-0.5 rounded">
                App\Models\Cultivo
              </span>
            </div>
            <p class="text-xs text-slate-400 mt-0.5">
              Módulo integrado con CatalogoVariedad, CultivoVariedad y Generación de Plantas
            </p>
          </div>
        </div>

        <Link
          :href="route('dashboard')"
          class="text-xs font-bold text-slate-400 hover:text-white px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 transition-colors"
        >
          &larr; Volver al Dashboard
        </Link>
      </div>

      <!-- FORMULARIO PRINCIPAL -->
      <div class="bg-slate-950 rounded-3xl border border-slate-800 shadow-xl overflow-hidden">
        
        <!-- PESTAÑAS -->
        <div class="flex border-b border-slate-800 bg-slate-900/50 px-6 gap-4 text-xs font-bold pt-3 overflow-x-auto">
          <button
            type="button"
            @click="tabActiva = 'DATOS_CULTIVO'"
            :class="[
              'pb-3 px-3 border-b-2 flex items-center gap-2 transition-all',
              tabActiva === 'DATOS_CULTIVO'
                ? 'border-emerald-500 text-emerald-400 font-extrabold'
                : 'border-transparent text-slate-400 hover:text-slate-200'
            ]"
          >
            🏢 1. Cultivo & Sala
          </button>

          <button
            type="button"
            @click="tabActiva = 'VARIEDADES'"
            :class="[
              'pb-3 px-3 border-b-2 flex items-center gap-2 transition-all',
              tabActiva === 'VARIEDADES'
                ? 'border-emerald-500 text-emerald-400 font-extrabold'
                : 'border-transparent text-slate-400 hover:text-slate-200'
            ]"
          >
            🧬 2. Genéticas ({{ form.variedades.length }})
          </button>

          <button
            type="button"
            @click="tabActiva = 'PLANTAS'"
            :class="[
              'pb-3 px-3 border-b-2 flex items-center gap-2 transition-all',
              tabActiva === 'PLANTAS'
                ? 'border-emerald-500 text-emerald-400 font-extrabold'
                : 'border-transparent text-slate-400 hover:text-slate-200'
            ]"
          >
            🪴 3. Canopia & Plantas ({{ totalPlantas }})
          </button>
        </div>

        <form @submit.prevent="submit" class="p-6 space-y-6 text-xs font-semibold">
          
          <!-- TAB 1: DATOS CULTIVO -->
          <div v-if="tabActiva === 'DATOS_CULTIVO'" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="sm:col-span-2">
                <label class="block text-slate-400 uppercase text-[11px] mb-1">
                  Nombre del Cultivo / Lote <span class="text-rose-400">*</span>
                </label>
                <input
                  v-model="form.nombre"
                  type="text"
                  placeholder="Ej. Lote Principal - Floración 2026"
                  class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white font-bold focus:border-emerald-500 outline-none"
                />
                <span v-if="form.errors.nombre" class="text-rose-400 text-[11px] mt-1 block">
                  {{ form.errors.nombre }}
                </span>
              </div>

              <div>
                <label class="block text-slate-400 uppercase text-[11px] mb-1 flex justify-between">
                  <span>Duración Estimada</span>
                  <span class="text-[10px] text-slate-500">decimal:2</span>
                </label>
                <div class="relative">
                  <input
                    v-model="form.duracion_estimada"
                    type="number"
                    step="0.5"
                    class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white font-bold focus:border-emerald-500 outline-none pr-12"
                  />
                  <span class="absolute right-3 top-2.5 text-slate-500">días</span>
                </div>
                <span v-if="form.errors.duracion_estimada" class="text-rose-400 text-[11px] mt-1 block">
                  {{ form.errors.duracion_estimada }}
                </span>
              </div>
            </div>

            <!-- Sala & Fotoperíodo -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="sm:col-span-2">
                <label class="block text-slate-400 uppercase text-[11px] mb-1">
                  Sala de Cultivo (sala_id) <span class="text-rose-400">*</span>
                </label>
                <select
                  v-model="form.sala_id"
                  class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white font-bold focus:border-emerald-500 outline-none cursor-pointer"
                >
                  <option v-for="s in props.salas" :key="s.id" :value="s.id">
                    {{ s.nombre }} ({{ s.codigo }}) &bull; Capacidad: {{ s.capacidad_macetas }} macetas
                  </option>
                </select>
                <span v-if="form.errors.sala_id" class="text-rose-400 text-[11px] mt-1 block">
                  {{ form.errors.sala_id }}
                </span>
              </div>

              <div>
                <label class="block text-slate-400 uppercase text-[11px] mb-1">Fotoperíodo</label>
                <input
                  v-model="form.fotoperiodo"
                  type="text"
                  placeholder="18/6 o 12/12"
                  class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white font-bold text-center focus:border-emerald-500 outline-none"
                />
              </div>
            </div>

            <!-- Fase, Estado y Responsable -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-slate-400 uppercase text-[11px] mb-1">
                  Fase Inicial (fase_id) <span class="text-rose-400">*</span>
                </label>
                <select
                  v-model="form.fase_id"
                  class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white font-bold focus:border-emerald-500 outline-none cursor-pointer"
                >
                  <option v-for="f in props.fases" :key="f.id" :value="f.id">
                    {{ f.nombre }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-slate-400 uppercase text-[11px] mb-1">
                  Estado (estado_cultivo_id) <span class="text-rose-400">*</span>
                </label>
                <select
                  v-model="form.estado_cultivo_id"
                  class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white font-bold focus:border-emerald-500 outline-none cursor-pointer"
                >
                  <option v-for="est in props.estados" :key="est.id" :value="est.id">
                    {{ est.nombre }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-slate-400 uppercase text-[11px] mb-1">
                  Usuario Responsable <span class="text-rose-400">*</span>
                </label>
                <select
                  v-model="form.usuario_responsable_id"
                  class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-white font-bold focus:border-emerald-500 outline-none cursor-pointer"
                >
                  <option v-for="u in props.usuarios" :key="u.id" :value="u.id">
                    {{ u.name }}
                  </option>
                </select>
              </div>
            </div>

            <!-- Descripción -->
            <div>
              <label class="block text-slate-400 uppercase text-[11px] mb-1">Descripción / Bitácora</label>
              <textarea
                v-model="form.descripcion"
                rows="2"
                placeholder="Observaciones iniciales del lote..."
                class="w-full bg-slate-900 border border-slate-800 rounded-xl p-3 text-white focus:border-emerald-500 outline-none"
              ></textarea>
            </div>

            <div class="flex justify-end pt-2">
              <button
                type="button"
                @click="tabActiva = 'VARIEDADES'"
                class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-6 py-2.5 rounded-xl transition-all"
              >
                Continuar a Genéticas &rarr;
              </button>
            </div>
          </div>

          <!-- TAB 2: VARIEDADES ASIGNADAS -->
          <div v-if="tabActiva === 'VARIEDADES'" class="space-y-4">
            <div class="flex justify-between items-center bg-slate-900 p-4 rounded-2xl border border-slate-800">
              <div>
                <h3 class="font-extrabold text-white text-xs uppercase tracking-wider">
                  Variedades Genéticas en este Cultivo (CultivoVariedad)
                </h3>
                <p class="text-[11px] text-slate-400">
                  Lotes mono o multi-varietales asignados a este cultivo
                </p>
              </div>

              <div class="flex gap-2">
                <button
                  type="button"
                  @click="modalNuevaVariedad = true"
                  class="px-3 py-1.5 bg-purple-950 text-purple-300 border border-purple-800 rounded-xl text-xs font-bold hover:bg-purple-900 transition-all"
                >
                  + Nueva Variedad en Catálogo
                </button>
                <button
                  type="button"
                  @click="agregarVariedad"
                  class="px-3 py-1.5 bg-emerald-950 text-emerald-300 border border-emerald-800 rounded-xl text-xs font-bold hover:bg-emerald-900 transition-all"
                >
                  + Agregar Variedad
                </button>
              </div>
            </div>

            <div class="space-y-3">
              <div
                v-for="(v, idx) in form.variedades"
                :key="idx"
                class="bg-slate-900 p-4 rounded-2xl border border-slate-800 space-y-3"
              >
                <div class="flex justify-between items-center border-b border-slate-800 pb-2">
                  <span class="font-extrabold text-emerald-400">
                    Línea #{{ idx + 1 }}
                  </span>
                  <button
                    v-if="form.variedades.length > 1"
                    type="button"
                    @click="removerVariedad(idx)"
                    class="text-rose-400 hover:text-rose-300 text-xs font-bold"
                  >
                    Eliminar
                  </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                  <div class="sm:col-span-5">
                    <label class="block text-slate-400 text-[10px] uppercase mb-1">
                      Catálogo Variedad (catalogo_variedad_id)
                    </label>
                    <select
                      v-model="v.catalogo_variedad_id"
                      class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white font-bold"
                    >
                      <option v-for="cv in props.catalogoVariedades" :key="cv.id" :value="cv.id">
                        {{ cv.nombre }} &bull; {{ cv.banco }} ({{ cv.dias_ciclo }}d)
                      </option>
                    </select>
                  </div>

                  <div class="sm:col-span-3">
                    <label class="block text-slate-400 text-[10px] uppercase mb-1">
                      Cantidad Plantas (decimal:2)
                    </label>
                    <input
                      v-model.number="v.cantidad_plantas"
                      type="number"
                      class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white font-bold text-center"
                    />
                  </div>

                  <div class="sm:col-span-4">
                    <label class="block text-slate-400 text-[10px] uppercase mb-1">
                      Fecha Inicio (fecha_inicio)
                    </label>
                    <input
                      v-model="v.fecha_inicio"
                      type="date"
                      class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white font-bold"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Resumen de plantas y capacidad -->
            <div class="p-4 bg-slate-900 rounded-2xl border border-slate-800 flex justify-between items-center">
              <div>
                <span class="text-slate-400 block text-[11px]">Total Plantas Asignadas:</span>
                <span class="text-lg font-black text-emerald-400">{{ totalPlantas }} plantas</span>
              </div>

              <div class="text-right">
                <span class="text-slate-400 block text-[11px]">Capacidad Sala:</span>
                <span :class="excedeCapacidad ? 'text-amber-400 font-bold' : 'text-slate-200 font-bold'">
                  {{ salaSeleccionada?.capacidad_macetas || 'N/A' }} macetas
                </span>
              </div>
            </div>

            <div class="flex justify-between pt-2">
              <button
                type="button"
                @click="tabActiva = 'DATOS_CULTIVO'"
                class="text-slate-400 hover:text-white font-bold"
              >
                &larr; Volver
              </button>
              <button
                type="button"
                @click="tabActiva = 'PLANTAS'"
                class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-6 py-2.5 rounded-xl transition-all"
              >
                Ver Desglose de Plantas &rarr;
              </button>
            </div>
          </div>

          <!-- TAB 3: PLANTAS INDIVIDUALES -->
          <div v-if="tabActiva === 'PLANTAS'" class="space-y-4">
            <div class="bg-slate-900 p-4 rounded-2xl border border-slate-800">
              <h3 class="font-extrabold text-white text-xs uppercase tracking-wider">
                Generador de Identificadores (App\Models\Planta)
              </h3>
              <p class="text-[11px] text-slate-400 mt-1">
                Se crearán <strong>{{ totalPlantas }} registros individuales de Planta</strong> vinculados a su CultivoVariedad con código de trazabilidad único.
              </p>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-slate-800">
              <button
                type="button"
                @click="tabActiva = 'VARIEDADES'"
                class="text-slate-400 hover:text-white font-bold"
              >
                &larr; Volver a Genéticas
              </button>

              <button
                type="submit"
                :disabled="form.processing"
                class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-8 py-3 rounded-2xl shadow-lg shadow-emerald-500/20 transition-all cursor-pointer"
              >
                {{ form.processing ? 'Guardando en Base de Datos...' : 'Guardar Cultivo Completo' }}
              </button>
            </div>
          </div>

        </form>
      </div>

    </div>
  </div>
</template>
