<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm, Head, Link } from '@inertiajs/vue3'

// =========================================================================
// TIPOS E INTERFACES DEL MODELO SALA Y EQUIPAMIENTOS
// =========================================================================
export interface Organizacion {
  id: string
  nombre: string
  codigo?: string
}

export interface TipoSalaOption {
  id: string
  nombre: string
  codigo?: string
}

export interface TipoEquipamientoOption {
  id: string
  nombre: string
  codigo: string
}

export interface EquipamientoDraft {
  id: string
  nombre: string
  codigo_inventario: string
  tipo: 'ILUMINACION' | 'CLIMATIZACION' | 'CO2' | 'VENTILACION' | 'HUMIDIFICADOR' | 'DESHUMIDIFICADOR' | 'RIEGO_AUTOMATICO'
  marca?: string
  modelo?: string
  potencia_w?: number
  especificaciones?: Record<string, any>
  estado?: 'OPERATIVO' | 'MANTENIMIENTO' | 'INACTIVO'
}

// Props enviadas por SalaController@create
const props = withDefaults(
  defineProps<{
    organizaciones?: Organizacion[]
    tiposSala?: TipoSalaOption[]
    tiposEquipamiento?: TipoEquipamientoOption[]
    organizacionActivaId?: string
  }>(),
  {
    organizaciones: () => [
      { id: '01a02c9f-73c9-732b-b635-8e2ab3aa44dc', nombre: 'CultivoOS Master Facility — Parque Industrial & Tecnológico Nave 04, Buenos Aires' },
      { id: 'org-canna-02', nombre: 'Canopy Argentina S.A. — Facility Experimental Zárate' }
    ],
    tiposSala: () => [
      { id: '01a02c9f-745a-7310-be5c-660e76fbcd11', nombre: 'Floración', codigo: 'FLORACION' },
      { id: '01a02c9f-746b-7322-be8d-770e88fbcd22', nombre: 'Vegetación', codigo: 'VEGETACION' },
      { id: '01a02c9f-747c-7333-be9e-880e99fbcd33', nombre: 'Secado & Curado', codigo: 'SECADO' },
      { id: '01a02c9f-748d-7344-be0f-990e00fbcd44', nombre: 'Madres & Esquejes', codigo: 'MADRES' }
    ],
    tiposEquipamiento: () => [
      { id: '01a02bb8-453b-7274-8ffe-a9cdfe71bbcb', nombre: 'Luminaria / Iluminación LED & HPS', codigo: 'ILUMINACION' },
      { id: '01a02bb8-4552-7007-8ee2-608219f8dc93', nombre: 'Aire Acondicionado / Climatización VRV', codigo: 'CLIMATIZACION' },
      { id: '01a02bb8-455c-7268-8bca-42fd6d125540', nombre: 'Sistema de Riego / Fertirriego', codigo: 'RIEGO_AUTOMATICO' },
      { id: '01a02bb8-455b-7081-9058-4710fe4ef064', nombre: 'Deshumidificador Industrial Quest', codigo: 'DESHUMIDIFICADOR' },
      { id: '01a02bb8-456a-7092-9169-5820fe5fa175', nombre: 'Inyección & Control CO2', codigo: 'CO2' }
    ],
    organizacionActivaId: '01a02c9f-73c9-732b-b635-8e2ab3aa44dc'
  }
)

// =========================================================================
// ESTADO Y NAVEGACIÓN ENTRE PASOS
// =========================================================================
type Step = 1 | 2 | 3
const pasoActual = ref<Step>(1)

// Formulario principal de Inertia useForm
const form = useForm({
  organizacion_id: props.organizacionActivaId || props.organizaciones[0]?.id || '',
  nombre: 'Sala Épsilon - Floración #3',
  codigo: 'SALA-FLORA-03',
  tipo_sala_id: props.tiposSala[0]?.id || '',
  estado: 'ACTIVA',
  area_m2: 22.0,
  altura_m: 2.8,
  capacidad_macetas: 40,
  posee_co2: true,
  posee_extraccion: true,
  posee_intraccion: true,
  posee_ins_agua: true,
  descripcion: 'Sala optimizada para ciclo de floración con control ambiental de precisión y fertirriego.',
  equipamientos: [
    {
      id: 'eq-init-1',
      nombre: 'Array LED Samsung LM301H (6x 680W)',
      codigo_inventario: 'EQ-LED-FLORA-03',
      tipo: 'ILUMINACION',
      marca: 'Gavita Pro',
      modelo: '1700e LED ML',
      potencia_w: 4080,
      especificaciones: {
        tipo_iluminacion: 'LED',
        espectro: 'FULL_SPECTRUM',
        ppfd: 1800,
        dimmer: true
      },
      estado: 'OPERATIVO'
    },
    {
      id: 'eq-init-2',
      nombre: 'Inverter Clima Industrial 36000 BTU',
      codigo_inventario: 'EQ-CLIMA-03',
      tipo: 'CLIMATIZACION',
      marca: 'Daikin',
      modelo: 'VRV-X Inverter',
      potencia_w: 2400,
      especificaciones: {
        btu: 36000,
        frio_calor: true,
        refrigerante: 'R410A'
      },
      estado: 'OPERATIVO'
    }
  ] as EquipamientoDraft[]
})

// Subformulario para agregar un nuevo equipamiento en el Paso 2
const subFormEq = ref<EquipamientoDraft>({
  id: '',
  nombre: '',
  codigo_inventario: '',
  tipo: 'ILUMINACION',
  marca: '',
  modelo: '',
  potencia_w: 680,
  especificaciones: {
    tipo_iluminacion: 'LED',
    espectro: 'FULL_SPECTRUM',
    dimmer: true
  },
  estado: 'OPERATIVO'
})

const mostrandoNuevoEquipoForm = ref(false)

// Cálculos automáticos
const volumenCalculado = computed(() => {
  if (form.area_m2 && form.altura_m) {
    return (Number(form.area_m2) * Number(form.altura_m)).toFixed(1)
  }
  return '0.0'
})

const totalPotenciaW = computed(() => {
  return form.equipamientos.reduce((acc, eq) => acc + (Number(eq.potencia_w) || 0), 0)
})

const totalLuminariasW = computed(() => {
  return form.equipamientos
    .filter(eq => eq.tipo === 'ILUMINACION')
    .reduce((acc, eq) => acc + (Number(eq.potencia_w) || 0), 0)
})

// Helpers y validaciones
function agregarEquipamiento() {
  if (!subFormEq.value.nombre?.trim()) {
    alert('Por favor ingresa un nombre para el equipamiento.')
    return
  }
  const nuevoId = 'eq-' + Date.now()
  const codigoAuto = subFormEq.value.codigo_inventario || ('EQ-' + subFormEq.value.tipo.slice(0, 3) + '-' + Math.floor(Math.random() * 900 + 100))
  
  form.equipamientos.push({
    ...subFormEq.value,
    id: nuevoId,
    codigo_inventario: codigoAuto,
    potencia_w: Number(subFormEq.value.potencia_w) || 0
  })

  // Reset del subform
  subFormEq.value = {
    id: '',
    nombre: '',
    codigo_inventario: '',
    tipo: 'ILUMINACION',
    marca: '',
    modelo: '',
    potencia_w: 680,
    especificaciones: { tipo_iluminacion: 'LED', espectro: 'FULL_SPECTRUM' },
    estado: 'OPERATIVO'
  }
  mostrandoNuevoEquipoForm.value = false
}

function eliminarEquipamiento(index: number) {
  form.equipamientos.splice(index, 1)
}

function avanzarPaso() {
  form.clearErrors()
  if (pasoActual.value === 1) {
    if (!form.nombre?.trim()) {
      form.setError('nombre', 'El nombre de la sala es obligatorio.')
      return
    }
    if (!form.codigo?.trim()) {
      form.setError('codigo', 'El código identificador es obligatorio.')
      return
    }
    if (!form.area_m2 || form.area_m2 <= 0) {
      form.setError('area_m2', 'Ingresa una superficie válida en m².')
      return
    }
    pasoActual.value = 2
  } else if (pasoActual.value === 2) {
    pasoActual.value = 3
  }
}

function retrocederPaso() {
  if (pasoActual.value > 1) {
    pasoActual.value = (pasoActual.value - 1) as Step
  }
}

function enviarFormulario() {
  // Enviar a la ruta de Laravel
  form.post('/salas', {
    onSuccess: () => {
      // Redirección exitosa manejada por Inertia
    },
    onError: (errs) => {
      console.error('Error al guardar sala:', errs)
    }
  })
}
</script>

<template>
  <Head title="Crear Nueva Sala Climatizada - CultivoOS" />

  <div class="min-h-screen bg-slate-900/40 backdrop-blur-md flex items-center justify-center p-3 sm:p-6">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/80 w-full max-w-4xl overflow-hidden flex flex-col max-h-[92vh]">
      
      <!-- ================================================================= -->
      <!-- HEADER DEL MODAL / FORMULARIO MULTI-PASO                          -->
      <!-- ================================================================= -->
      <div class="bg-slate-900 text-white px-6 py-4 flex items-center justify-between border-b border-slate-800 shrink-0">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-lg">
            🏢
          </div>
          <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-white flex items-center gap-2">
              Crear Nueva Sala Climatizada
            </h2>
            <p class="text-xs text-slate-400">
              Arquitectura de Instalación y Equipamiento Técnico
            </p>
          </div>
        </div>

        <!-- INDICADOR DE PASOS (STEPPER) -->
        <div class="flex items-center gap-1.5 text-xs font-bold bg-slate-800/80 p-1.5 rounded-2xl border border-slate-700/60">
          <button
            type="button"
            @click="pasoActual = 1"
            :class="pasoActual === 1 ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white'"
            class="px-2.5 py-1 rounded-xl transition-all cursor-pointer"
          >
            1. Información
          </button>
          <span class="text-slate-600">/</span>
          <button
            type="button"
            @click="pasoActual = 2"
            :class="pasoActual === 2 ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white'"
            class="px-2.5 py-1 rounded-xl transition-all cursor-pointer"
          >
            2. Equipamiento ({{ form.equipamientos.length }})
          </button>
          <span class="text-slate-600">/</span>
          <button
            type="button"
            @click="pasoActual = 3"
            :class="pasoActual === 3 ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white'"
            class="px-2.5 py-1 rounded-xl transition-all cursor-pointer"
          >
            3. Resumen
          </button>
        </div>
      </div>

      <!-- ================================================================= -->
      <!-- CONTENIDO PRINCIPAL SCROLLEABLE                                  -->
      <!-- ================================================================= -->
      <form @submit.prevent="enviarFormulario" class="p-6 sm:p-8 overflow-y-auto flex-1 space-y-6">

        <!-- =============================================================== -->
        <!-- PASO 1: INFORMACIÓN GENERAL Y DIMENSIONES                        -->
        <!-- =============================================================== -->
        <div v-if="pasoActual === 1" class="space-y-6">
          
          <!-- BANNER DEL PASO -->
          <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200/80 p-4 rounded-2xl">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                1
              </div>
              <div>
                <h3 class="text-sm font-bold text-emerald-950">Paso 1: Información General y Dimensiones</h3>
                <p class="text-xs text-emerald-700">Define el propósito agronómico, cubaje y facilidades técnicas</p>
              </div>
            </div>
            <div class="text-right">
              <span class="text-[10px] uppercase font-bold text-emerald-700 block">Volumen Estimado:</span>
              <span class="text-lg font-black text-emerald-900">{{ volumenCalculado }} m³</span>
            </div>
          </div>

          <!-- ORGANIZACIÓN PERTENECIENTE -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
              🏢 Organización Perteneciente <span class="text-slate-400 font-normal">(organizacion_id)</span>
            </label>
            <select
              v-model="form.organizacion_id"
              class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-hidden transition-all"
            >
              <option v-for="org in props.organizaciones" :key="org.id" :value="org.id">
                {{ org.nombre }}
              </option>
            </select>
          </div>

          <!-- NOMBRE Y CÓDIGO -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                Nombre de la Sala <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="form.nombre"
                type="text"
                placeholder="Ej. Sala Épsilon - Floración #3"
                class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-sm font-semibold text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-hidden transition-all"
                :class="{ 'border-rose-400 ring-2 ring-rose-100': form.errors.nombre }"
              />
              <span v-if="form.errors.nombre" class="text-xs text-rose-600 mt-1 block">{{ form.errors.nombre }}</span>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                Código Identificador <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="form.codigo"
                type="text"
                placeholder="Ej. SALA-FLORA-03"
                class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-sm font-mono font-bold text-slate-900 uppercase focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-hidden transition-all"
                :class="{ 'border-rose-400 ring-2 ring-rose-100': form.errors.codigo }"
              />
              <span v-if="form.errors.codigo" class="text-xs text-rose-600 mt-1 block">{{ form.errors.codigo }}</span>
            </div>
          </div>

          <!-- TIPO DE SALA Y ESTADO INICIAL -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                Tipo de Sala / Destino
              </label>
              <select
                v-model="form.tipo_sala_id"
                class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-hidden transition-all"
              >
                <option v-for="t in props.tiposSala" :key="t.id" :value="t.id">
                  {{ t.nombre }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                Estado Inicial
              </label>
              <select
                v-model="form.estado"
                class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-hidden transition-all"
              >
                <option value="ACTIVA">Activa / En Operación</option>
                <option value="MANTENIMIENTO">En Mantenimiento</option>
                <option value="INACTIVA">Inactiva / Preparación</option>
              </select>
            </div>
          </div>

          <!-- DIMENSIONES DE LA SALA -->
          <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                <span>📐</span> Dimensiones de la Sala
              </span>
              <span class="text-[11px] text-slate-400 font-medium">
                Cálculo automático: Área (m²) × Altura (m) = Volumen (m³)
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Superficie / Área (m²)</label>
                <input
                  v-model.number="form.area_m2"
                  type="number"
                  step="0.1"
                  min="1"
                  class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-800"
                />
              </div>

              <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Altura de Techo (m)</label>
                <input
                  v-model.number="form.altura_m"
                  type="number"
                  step="0.1"
                  min="1"
                  class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-800"
                />
              </div>

              <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Capacidad Máx. Macetas</label>
                <input
                  v-model.number="form.capacidad_macetas"
                  type="number"
                  min="1"
                  class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-800"
                />
              </div>
            </div>
          </div>

          <!-- INSTALACIONES Y SERVICIOS DISPONIBLES (CHECKBOXES) -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
              Instalaciones y Servicios Disponibles
            </label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50/50 transition-colors cursor-pointer text-xs font-bold text-slate-700">
                <input v-model="form.posee_co2" type="checkbox" class="w-4 h-4 rounded text-emerald-600" />
                <span>Inyección CO₂</span>
              </label>

              <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50/50 transition-colors cursor-pointer text-xs font-bold text-slate-700">
                <input v-model="form.posee_extraccion" type="checkbox" class="w-4 h-4 rounded text-emerald-600" />
                <span>Extracción Aire</span>
              </label>

              <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50/50 transition-colors cursor-pointer text-xs font-bold text-slate-700">
                <input v-model="form.posee_intraccion" type="checkbox" class="w-4 h-4 rounded text-emerald-600" />
                <span>Intracción Filtrada</span>
              </label>

              <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50/50 transition-colors cursor-pointer text-xs font-bold text-slate-700">
                <input v-model="form.posee_ins_agua" type="checkbox" class="w-4 h-4 rounded text-emerald-600" />
                <span>Red Agua / Riego</span>
              </label>
            </div>
          </div>

          <!-- DESCRIPCIÓN Y NOTAS -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
              Descripción y Notas de la Sala
            </label>
            <textarea
              v-model="form.descripcion"
              rows="3"
              placeholder="Detalles sobre revestimientos térmicos, filtros de carbón, piso epoxi o especificaciones eléctricas..."
              class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs font-medium text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-hidden transition-all"
            ></textarea>
          </div>

        </div>

        <!-- =============================================================== -->
        <!-- PASO 2: EQUIPAMIENTO TÉCNICO Y CLIMATIZACIÓN                    -->
        <!-- =============================================================== -->
        <div v-if="pasoActual === 2" class="space-y-6">
          
          <div class="flex items-center justify-between bg-purple-50 border border-purple-200/80 p-4 rounded-2xl">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-sm">
                2
              </div>
              <div>
                <h3 class="text-sm font-bold text-purple-950">Paso 2: Equipamiento Técnico & Potencia</h3>
                <p class="text-xs text-purple-700">Registra luminarias, climatizadores, deshumidificadores y fertirriego</p>
              </div>
            </div>
            <div class="text-right">
              <span class="text-[10px] uppercase font-bold text-purple-700 block">Potencia Total:</span>
              <span class="text-lg font-black text-purple-900">{{ (totalPotenciaW / 1000).toFixed(1) }} kW</span>
            </div>
          </div>

          <!-- LISTADO DE EQUIPOS AGREGADOS -->
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                Equipos Asignados ({{ form.equipamientos.length }})
              </h4>
              <button
                type="button"
                @click="mostrandoNuevoEquipoForm = !mostrandoNuevoEquipoForm"
                class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-xs"
              >
                <span>➕</span> Agregar Equipo
              </button>
            </div>

            <!-- FORMULARIO DESPLEGABLE PARA AGREGAR EQUIPO -->
            <div v-if="mostrandoNuevoEquipoForm" class="bg-purple-50/50 p-5 rounded-2xl border border-purple-200 space-y-4 animate-in fade-in">
              <div class="flex items-center justify-between border-b border-purple-100 pb-2">
                <span class="text-xs font-bold text-purple-900 uppercase">Nuevo Dispositivo</span>
                <button type="button" @click="mostrandoNuevoEquipoForm = false" class="text-slate-400 hover:text-slate-600 text-xs">
                  ✕ Cancelar
                </button>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                  <label class="block text-[11px] font-bold text-slate-700 mb-1">Nombre del Equipo *</label>
                  <input
                    v-model="subFormEq.nombre"
                    type="text"
                    placeholder="Ej. Deshumidificador Industrial Quest 155"
                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold"
                  />
                </div>
                <div>
                  <label class="block text-[11px] font-bold text-slate-700 mb-1">Tipo de Equipo</label>
                  <select v-model="subFormEq.tipo" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold">
                    <option value="ILUMINACION">Luminaria LED / HPS</option>
                    <option value="CLIMATIZACION">Aire Acondicionado / Clima</option>
                    <option value="DESHUMIDIFICADOR">Deshumidificador</option>
                    <option value="RIEGO_AUTOMATICO">Riego Automático</option>
                    <option value="CO2">Inyección CO₂</option>
                  </select>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                  <label class="block text-[11px] font-bold text-slate-700 mb-1">Marca</label>
                  <input v-model="subFormEq.marca" type="text" placeholder="Gavita, Daikin, Quest..." class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium" />
                </div>
                <div>
                  <label class="block text-[11px] font-bold text-slate-700 mb-1">Modelo</label>
                  <input v-model="subFormEq.modelo" type="text" placeholder="1700e, VRV..." class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium" />
                </div>
                <div>
                  <label class="block text-[11px] font-bold text-slate-700 mb-1">Potencia (Watts)</label>
                  <input v-model.number="subFormEq.potencia_w" type="number" step="10" placeholder="680" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-purple-900" />
                </div>
              </div>

              <div class="flex justify-end pt-2">
                <button
                  type="button"
                  @click="agregarEquipamiento"
                  class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-4 py-2 rounded-xl cursor-pointer shadow-xs"
                >
                  ✓ Guardar Dispositivo en Sala
                </button>
              </div>
            </div>

            <!-- CARDS DE EQUIPAMIENTOS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div
                v-for="(eq, index) in form.equipamientos"
                :key="eq.id || index"
                class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-start justify-between gap-3 group"
              >
                <div>
                  <div class="flex items-center gap-1.5 mb-1">
                    <span class="text-[9px] font-bold px-2 py-0.5 rounded uppercase" :class="eq.tipo === 'ILUMINACION' ? 'bg-amber-100 text-amber-800' : eq.tipo === 'CLIMATIZACION' ? 'bg-cyan-100 text-cyan-800' : 'bg-purple-100 text-purple-800'">
                      {{ eq.tipo }}
                    </span>
                    <span class="text-[10px] font-mono text-slate-400 font-bold">{{ eq.codigo_inventario }}</span>
                  </div>
                  <h5 class="text-xs font-extrabold text-slate-900">{{ eq.nombre }}</h5>
                  <p class="text-[11px] text-slate-500 mt-0.5">
                    {{ eq.marca }} {{ eq.modelo }} &bull; <strong class="text-slate-800">{{ eq.potencia_w }} W</strong>
                  </p>
                </div>
                <button
                  type="button"
                  @click="eliminarEquipamiento(index)"
                  class="text-slate-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition-colors"
                  title="Eliminar"
                >
                  🗑️
                </button>
              </div>
            </div>
          </div>

        </div>

        <!-- =============================================================== -->
        <!-- PASO 3: RESUMEN Y CONFIRMACIÓN                                   -->
        <!-- =============================================================== -->
        <div v-if="pasoActual === 3" class="space-y-6">
          
          <div class="bg-emerald-50 border border-emerald-200/80 p-5 rounded-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-emerald-200/60 pb-3">
              <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Resumen de Arquitectura</span>
                <h3 class="text-lg font-black text-slate-900 mt-0.5">{{ form.nombre }} ({{ form.codigo }})</h3>
              </div>
              <span class="text-xs font-bold px-3 py-1 bg-emerald-600 text-white rounded-full">
                {{ form.estado }}
              </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
              <div class="bg-white p-3 rounded-xl border border-emerald-100">
                <span class="text-[10px] text-slate-400 uppercase font-bold">Superficie / Altura</span>
                <div class="font-black text-slate-900 mt-0.5">{{ form.area_m2 }} m² &bull; {{ form.altura_m }}m</div>
              </div>
              <div class="bg-white p-3 rounded-xl border border-emerald-100">
                <span class="text-[10px] text-slate-400 uppercase font-bold">Volumen Total</span>
                <div class="font-black text-emerald-700 mt-0.5">{{ volumenCalculado }} m³</div>
              </div>
              <div class="bg-white p-3 rounded-xl border border-emerald-100">
                <span class="text-[10px] text-slate-400 uppercase font-bold">Capacidad</span>
                <div class="font-black text-slate-900 mt-0.5">{{ form.capacidad_macetas }} macetas</div>
              </div>
              <div class="bg-white p-3 rounded-xl border border-emerald-100">
                <span class="text-[10px] text-slate-400 uppercase font-bold">Potencia Eléctrica</span>
                <div class="font-black text-purple-700 mt-0.5">{{ (totalPotenciaW / 1000).toFixed(1) }} kW</div>
              </div>
            </div>
          </div>

          <!-- LISTA DE EQUIPOS QUE SE REGISTRARÁN EN LA BASE DE DATOS -->
          <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
              Equipamiento técnico que se creará en base de datos ({{ form.equipamientos.length }} dispositivos):
            </span>
            <ul class="text-xs space-y-1.5 text-slate-600">
              <li v-for="eq in form.equipamientos" :key="eq.id" class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-slate-200">
                <span class="font-bold text-slate-800">{{ eq.nombre }} ({{ eq.tipo }})</span>
                <span class="font-mono text-purple-700 font-bold">{{ eq.potencia_w }} W</span>
              </li>
            </ul>
          </div>

        </div>

      </form>

      <!-- ================================================================= -->
      <!-- FOOTER DE ACCIONES DEL MODAL                                      -->
      <!-- ================================================================= -->
      <div class="bg-slate-50 border-t border-slate-200/80 px-6 py-4 flex items-center justify-between shrink-0">
        <div>
          <button
            v-if="pasoActual > 1"
            type="button"
            @click="retrocederPaso"
            class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 transition-all cursor-pointer"
          >
            &larr; Paso Anterior
          </button>
          <Link
            v-else
            href="/dashboard1"
            class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-800 transition-all"
          >
            Cancelar
          </Link>
        </div>

        <div class="flex items-center gap-2">
          <button
            v-if="pasoActual < 3"
            type="button"
            @click="avanzarPaso"
            class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-200 transition-all cursor-pointer"
          >
            Siguiente Paso &rarr;
          </button>

          <button
            v-else
            type="button"
            @click="enviarFormulario"
            :disabled="form.processing"
            class="px-6 py-2.5 rounded-xl text-xs font-extrabold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-200 transition-all cursor-pointer flex items-center gap-2"
          >
            <span v-if="form.processing">Guardando...</span>
            <span v-else>✓ Crear Sala Climatizada</span>
          </button>
        </div>
      </div>

    </div>
  </div>
</template>
