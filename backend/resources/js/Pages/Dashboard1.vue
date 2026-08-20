<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { MOCK_DASHBOARD_PROPS } from '@/mocks/mockData'

interface Props {
  salas?: Array<any>
  cultivos?: Array<any>
  registros?: Array<any>
  planificaciones?: Array<any>
  eventos?: Array<any>
  totalVariedades?: number
  auth?: {
    user?: {
      id?: string
      name?: string
      email?: string
      role?: string
    }
  }
}

const useMockIfEmpty = <T>(value: T[] | undefined, mock: T[]): T[] => {
  return value && value.length > 0 ? value : mock
}

const inertiaProps = defineProps<Props>()

const data = {
  salas: useMockIfEmpty(inertiaProps.salas, MOCK_DASHBOARD_PROPS.salas),
  cultivos: useMockIfEmpty(inertiaProps.cultivos, MOCK_DASHBOARD_PROPS.cultivos),
  planificaciones: useMockIfEmpty(inertiaProps.planificaciones, MOCK_DASHBOARD_PROPS.planificaciones),
  eventos: useMockIfEmpty(inertiaProps.eventos, MOCK_DASHBOARD_PROPS.eventos),
  registros: useMockIfEmpty(inertiaProps.registros, MOCK_DASHBOARD_PROPS.registros),
  totalVariedades: inertiaProps.totalVariedades ?? MOCK_DASHBOARD_PROPS.totalVariedades,
  user: inertiaProps.auth?.user || {
    id: 'usr-001',
    name: 'Ing. Juan Pérez',
    email: 'operador@cultivoos.internal',
    role: 'Director de Cultivo & Agrónomo',
  },
}

// Estados locales de interfaz
const menuPerfilAbierto = ref(false)
const menuMobileAbierto = ref(false)
const estaAutenticado = ref(true) // Toggle interactivo para modo usuario vs invitado
const filtroEventos = ref<'TODOS' | 'PENDIENTES' | 'PLANIFICADOS' | 'INDEPENDIENTES' | 'REALIZADOS'>('PENDIENTES')
const filtroCultivoRegistro = ref<string>('TODOS')
const filtroEtapaCultivo = ref<string>('TODOS')
const filtroTipoSala = ref<string>('TODOS')

// Modales
const modalEventoAbierto = ref(false)
const modalRegistroAbierto = ref(false)
const modalDetalleSala = ref<any | null>(null)
const modalDetalleCultivo = ref<any | null>(null)

// Formulario de Registro Diario (App\\Models\\Registro)
const formRegistro = useForm({
  nombre: 'Registro de Telemetría y Clima Diaria',
  fecha_registro: new Date().toISOString().slice(0, 16),
  descripcion: '',
  cultivo_id: data.cultivos[0]?.id || '',
  temperatura: 24.5,
  humedad: 50.0,
  vpd: 1.25,
  co2: 1100.0,
  temperatura_solucion: 20.5,
  temperatura_sustrato: 21.5,
  flujo_hora_intraccion: 450.0,
  flujo_hora_extracion: 600.0,
})

// Formulario de Evento (App\\Models\\Evento)
const formEvento = useForm({
  nombre: '',
  descripcion: '',
  fecha_inicio_planificacion: new Date().toISOString().split('T')[0],
  fecha_fin_planificacion: new Date().toISOString().split('T')[0],
  tipo_evento_id: '',
  estado_evento_id: '',
  cultivo_id: data.cultivos[0]?.id || '',
  time_line_id: '',
  es_asociado_plan: true,
})

// Métricas computadas
const totalPlantas = computed(() => {
  return data.cultivos.reduce((acc, c) => acc + (c.plantas_totales || 0), 0)
})

const totalWatts = computed(() => {
  return data.salas.reduce((acc, s) => acc + (s.potencia_luces_w || 0), 0)
})

const salasFiltradas = computed(() => {
  if (filtroTipoSala.value === 'TODOS') return data.salas
  return data.salas.filter((s) => s.tipo === filtroTipoSala.value)
})

const cultivosFiltrados = computed(() => {
  if (filtroEtapaCultivo.value === 'TODOS') return data.cultivos
  return data.cultivos.filter((c) => c.etapa === filtroEtapaCultivo.value)
})

const eventosFiltrados = computed(() => {
  return data.eventos.filter((e) => {
    const estado = e.estado_evento?.nombre || ''
    if (filtroEventos.value === 'PENDIENTES') return estado === 'PENDIENTE' || estado === 'EN_EJECUCION'
    if (filtroEventos.value === 'REALIZADOS') return estado === 'REALIZADO'
    if (filtroEventos.value === 'PLANIFICADOS') return Boolean(e.time_line_id)
    if (filtroEventos.value === 'INDEPENDIENTES') return !e.time_line_id
    return true
  })
})

const registrosFiltrados = computed(() => {
  if (filtroCultivoRegistro.value === 'TODOS') return data.registros
  return data.registros.filter((r) => r.cultivo?.id === filtroCultivoRegistro.value)
})

function toggleEstadoEvento(eventoId: string) {
  // En Laravel con Inertia: router.patch(route('eventos.toggle', eventoId), {}, { preserveScroll: true })
  const evt = data.eventos.find((e: any) => e.id === eventoId)
  if (evt) {
    evt.estado_evento = evt.estado_evento?.nombre === 'REALIZADO' ? { nombre: 'PENDIENTE' } : { nombre: 'REALIZADO' }
  }
}

function guardarRegistro() {
  formRegistro.post(route('registros.store'), {
    onSuccess: () => {
      modalRegistroAbierto.value = false
      formRegistro.reset()
    },
  })
}

function guardarEvento() {
  formEvento.post(route('eventos.store'), {
    onSuccess: () => {
      modalEventoAbierto.value = false
      formEvento.reset()
    },
  })
}

function cerrarSesion() {
  menuPerfilAbierto.value = false
  // En Laravel Breeze / Fortify: router.post(route('logout'))
  estaAutenticado.value = false
}
</script>

<template>
  <Head title="Dashboard de Control - CultivoOS" />

  <div class="min-h-screen bg-slate-50 font-sans text-slate-900 flex flex-col justify-between selection:bg-emerald-500 selection:text-white">
    
    <!-- ================================================================= -->
    <!-- TOPBAR CON NAVEGACIÓN Y MENÚ DE USUARIO / LOGIN & PERFIL          -->
    <!-- ================================================================= -->
    <header class="bg-white border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 py-3 sticky top-0 z-30 shadow-xs backdrop-blur-md bg-white/95">
      <div class="max-w-7xl mx-auto flex justify-between items-center gap-3">
        
        <!-- LOGO & BRAND -->
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 bg-emerald-600 rounded-2xl flex items-center justify-center shadow-md shadow-emerald-200 text-white font-bold shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h1 class="text-base sm:text-lg font-black tracking-tight uppercase text-slate-900">
                Cultivo<span class="text-emerald-600">OS</span>
              </h1>
              <span class="text-[10px] font-extrabold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                Dashboard
              </span>
            </div>
            <p class="text-[11px] text-slate-400 font-medium hidden md:block">
              Salas &bull; Cultivos &bull; Variedades &bull; Eventos &bull; Registros
            </p>
          </div>
        </div>

        <!-- ACCIONES RÁPIDAS (DESKTOP) -->
        <div class="hidden lg:flex items-center gap-2">
          <Link :href="route('cultivos.create')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-extrabold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm shadow-emerald-200 transition-all">
            <span>🌱</span> + Cultivo
          </Link>

          <Link :href="route('catalogo-variedades.index')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-50 hover:bg-purple-100 text-purple-800 border border-purple-200 shadow-xs transition-all">
            <span>🧬</span> + Variedad
          </Link>

          <Link :href="route('salas.create')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs transition-all">
            <span>🏢</span> + Sala
          </Link>

          <button @click="modalEventoAbierto = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-teal-50 hover:bg-teal-100 text-teal-800 border border-teal-200 shadow-xs transition-all cursor-pointer">
            <span>📅</span> + Evento
          </button>

          <button @click="modalRegistroAbierto = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white shadow-xs transition-all cursor-pointer">
            <span>📋</span> + Registro
          </button>
        </div>

        <!-- PERFIL / LOGIN & BOTÓN HAMBURGUESA MOBILE -->
        <div class="flex items-center gap-2">
          <!-- Perfil usuario -->
          <div class="relative">
            <template v-if="estaAutenticado">
              <button
                @click="menuPerfilAbierto = !menuPerfilAbierto"
                class="flex items-center gap-2 p-1 rounded-2xl hover:bg-slate-100 transition-all cursor-pointer border border-slate-200/80 bg-white"
              >
                <div class="w-7 h-7 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white text-xs font-black shadow-xs">
                  {{ data.user.name?.slice(0, 2).toUpperCase() }}
                </div>
                <div class="text-left hidden sm:block pr-1">
                  <div class="text-xs font-bold text-slate-900 leading-tight">{{ data.user.name }}</div>
                  <div class="text-[10px] text-slate-400 font-medium leading-none">{{ data.user.role || 'Operador' }}</div>
                </div>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- DROPDOWN DE PERFIL -->
              <div
                v-if="menuPerfilAbierto"
                class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50 animate-in fade-in slide-in-from-top-2"
              >
                <div class="px-4 py-3 border-b border-slate-100">
                  <p class="text-xs font-bold text-slate-900">{{ data.user.name }}</p>
                  <p class="text-[11px] text-slate-400 font-medium truncate mt-0.5">{{ data.user.email }}</p>
                  <span class="inline-block mt-1.5 text-[9px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 px-1.5 py-0.5 rounded border border-emerald-200">
                    {{ data.user.role || 'Agrónomo Autorizado' }}
                  </span>
                </div>

                <div class="py-1 text-xs">
                  <Link :href="route('profile.edit')" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 font-semibold">
                    <span>👤</span> Mi Perfil de Usuario
                  </Link>
                  <Link :href="route('cultivos.create')" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 font-semibold">
                    <span>🌱</span> Nuevo Cultivo
                  </Link>
                  <Link :href="route('catalogo-variedades.index')" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 font-semibold">
                    <span>🧬</span> Catálogo de Variedades
                  </Link>
                  <Link :href="route('salas.index')" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 font-semibold">
                    <span>🏢</span> Gestión de Instalaciones
                  </Link>
                </div>

                <div class="pt-1 border-t border-slate-100">
                  <button
                    @click="cerrarSesion"
                    class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                  >
                    <span>🚪</span> Cerrar Sesión
                  </button>
                </div>
              </div>
            </template>

            <template v-else>
              <button
                @click="estaAutenticado = true"
                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-xl border border-emerald-200 transition-colors cursor-pointer"
              >
                <span>🔑</span> Iniciar Sesión
              </button>
            </template>
          </div>

          <!-- BOTÓN HAMBURGUESA MOBILE -->
          <button
            @click="menuMobileAbierto = !menuMobileAbierto"
            class="lg:hidden p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer"
            title="Abrir menú"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="!menuMobileAbierto" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

      </div>

      <!-- MENÚ DESPLEGABLE MOBILE -->
      <div v-if="menuMobileAbierto" class="lg:hidden mt-3 pt-3 border-t border-slate-200 flex flex-col gap-2">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
          <Link :href="route('cultivos.create')" class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-emerald-600 text-white shadow-xs">
            <span>🌱</span> + Cultivo
          </Link>
          <Link :href="route('catalogo-variedades.index')" class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-purple-50 text-purple-800 border border-purple-200">
            <span>🧬</span> + Variedad
          </Link>
          <Link :href="route('salas.create')" class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 border border-slate-200">
            <span>🏢</span> + Sala
          </Link>
          <button @click="modalEventoAbierto = true; menuMobileAbierto = false" class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-teal-50 text-teal-800 border border-teal-200">
            <span>📅</span> + Evento
          </button>
          <button @click="modalRegistroAbierto = true; menuMobileAbierto = false" class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-slate-900 text-white">
            <span>📋</span> + Registro
          </button>
        </div>
      </div>
    </header>

    <!-- ================================================================= -->
    <!-- CUERPO PRINCIPAL DEL DASHBOARD                                   -->
    <!-- ================================================================= -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-8">
      
      <!-- 1. MÉTRICAS CLAVE EN TARJETAS -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
          <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Salas Operativas</span>
          <div class="text-2xl font-black text-slate-900 mt-1">{{ data.salas.length }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
          <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lotes en Floración</span>
          <div class="text-2xl font-black text-emerald-600 mt-1">{{ data.cultivos.length }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
          <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Población Vegetal</span>
          <div class="text-2xl font-black text-indigo-600 mt-1">{{ totalPlantas }} un.</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
          <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Potencia Lumínica</span>
          <div class="text-2xl font-black text-amber-600 mt-1">{{ (totalWatts / 1000).toFixed(1) }} kW</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs col-span-2 sm:col-span-1">
          <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Planificaciones Activas</span>
          <div class="text-2xl font-black text-purple-600 mt-1">{{ data.planificaciones.length }}</div>
        </div>
      </div>

      <!-- =============================================================== -->
      <!-- 2. SECCIÓN: CARDS DE SALAS CON RESÚMENES & BOTÓN DE DETALLE     -->
      <!-- =============================================================== -->
      <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-600 mb-0.5">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              Instalaciones & Climatización
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
              Salas de Cultivo & Módulos Climatizados
            </h2>
          </div>

          <div class="flex items-center gap-2">
            <div class="flex items-center gap-1 bg-white p-1 rounded-2xl border border-slate-200 text-xs font-bold shadow-xs">
              <button @click="filtroTipoSala = 'TODOS'" :class="filtroTipoSala === 'TODOS' ? 'bg-slate-900 text-white' : 'text-slate-600'" class="px-3 py-1.5 rounded-xl cursor-pointer">
                Todas ({{ data.salas.length }})
              </button>
              <button @click="filtroTipoSala = 'FLORACION'" :class="filtroTipoSala === 'FLORACION' ? 'bg-amber-500 text-white' : 'text-slate-600'" class="px-3 py-1.5 rounded-xl cursor-pointer">
                Floración
              </button>
              <button @click="filtroTipoSala = 'VEGETACION'" :class="filtroTipoSala === 'VEGETACION' ? 'bg-emerald-600 text-white' : 'text-slate-600'" class="px-3 py-1.5 rounded-xl cursor-pointer">
                Vegetación
              </button>
            </div>
            <Link :href="route('salas.create')" class="bg-white hover:bg-slate-50 text-slate-800 border border-slate-200 px-3 py-1.5 rounded-xl text-xs font-bold shadow-xs">
              + Sala
            </Link>
          </div>
        </div>

        <!-- GRID DE CARDS DE SALAS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
          <div
            v-for="sala in salasFiltradas"
            :key="sala.id"
            class="bg-white rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between overflow-hidden group"
          >
            <div class="p-5 space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-[10px] font-mono font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
                  {{ sala.codigo }}
                </span>
                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full" :class="sala.estado === 'ACTIVA' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'">
                  {{ sala.estado }}
                </span>
              </div>

              <div>
                <h3 class="text-base font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors">
                  {{ sala.nombre }}
                </h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">
                  Tipo: <strong class="text-slate-700">{{ sala.tipo }}</strong> &bull; Dimensión: {{ sala.ancho_m }}m &times; {{ sala.largo_m }}m
                </p>
              </div>

              <!-- Telemetría en vivo -->
              <div class="grid grid-cols-2 gap-2 pt-1">
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                  <span class="text-[9px] text-slate-400 uppercase font-bold">Temp / HR</span>
                  <div class="font-bold text-slate-900 text-xs mt-0.5">
                    {{ sala.temperatura_c }}°C &bull; {{ sala.humedad_pct }}%
                  </div>
                </div>
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                  <span class="text-[9px] text-slate-400 uppercase font-bold">VPD / CO₂</span>
                  <div class="font-bold text-amber-700 text-xs mt-0.5">
                    {{ sala.vpd_kpa }} kPa &bull; {{ sala.co2_ppm }} ppm
                  </div>
                </div>
              </div>

              <!-- Especificaciones -->
              <div class="text-xs pt-1 space-y-1 border-t border-slate-100 text-slate-500 font-medium">
                <div class="flex justify-between">
                  <span>Potencia Luces:</span>
                  <strong class="text-slate-800">{{ sala.potencia_luces_w }} W ({{ (sala.potencia_luces_w / 1000).toFixed(1) }} kW)</strong>
                </div>
                <div class="flex justify-between">
                  <span>Capacidad Macetas:</span>
                  <strong class="text-slate-800">{{ sala.capacidad_macetas }} macetas</strong>
                </div>
              </div>
            </div>

            <!-- BOTÓN IR AL DETALLE DE SALA -->
            <div class="p-4 bg-slate-50 border-t border-slate-200/80 flex items-center gap-2">
              <button
                @click="modalDetalleSala = sala"
                class="flex-1 text-center py-2 px-3 bg-white hover:bg-slate-900 text-slate-700 hover:text-white border border-slate-200 hover:border-slate-900 rounded-xl text-xs font-bold transition-all cursor-pointer shadow-2xs"
              >
                Ver Detalle & Sensores &rarr;
              </button>
              <Link
                :href="route('salas.show', sala.id)"
                class="px-3 py-2 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition-all"
                title="Ir a la página de sala"
              >
                ⚙️
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- =============================================================== -->
      <!-- 3. SECCIÓN: CARDS DE CULTIVOS CON RESÚMENES & BOTÓN DE DETALLE  -->
      <!-- =============================================================== -->
      <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-600 mb-0.5">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              Seguimiento Fenológico en Vivo
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
              Lotes y Cultivos en Desarrollo
            </h2>
          </div>

          <div class="flex items-center gap-2">
            <div class="flex items-center gap-1 bg-white p-1 rounded-2xl border border-slate-200 text-xs font-bold shadow-xs">
              <button @click="filtroEtapaCultivo = 'TODOS'" :class="filtroEtapaCultivo === 'TODOS' ? 'bg-slate-900 text-white' : 'text-slate-600'" class="px-3 py-1.5 rounded-xl cursor-pointer">
                Todos ({{ data.cultivos.length }})
              </button>
              <button @click="filtroEtapaCultivo = 'FLORACION'" :class="filtroEtapaCultivo === 'FLORACION' ? 'bg-amber-500 text-white' : 'text-slate-600'" class="px-3 py-1.5 rounded-xl cursor-pointer">
                Floración
              </button>
              <button @click="filtroEtapaCultivo = 'VEGETATIVO'" :class="filtroEtapaCultivo === 'VEGETATIVO' ? 'bg-emerald-600 text-white' : 'text-slate-600'" class="px-3 py-1.5 rounded-xl cursor-pointer">
                Vegetativo
              </button>
            </div>
            <Link :href="route('cultivos.create')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-xl text-xs font-bold shadow-xs">
              + Iniciar Lote
            </Link>
          </div>
        </div>

        <!-- GRID DE CARDS DE CULTIVOS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div
            v-for="cultivo in cultivosFiltrados"
            :key="cultivo.id"
            class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4 group"
          >
            <div class="flex items-start justify-between gap-3">
              <div>
                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                  <span class="text-[11px] font-mono font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200/80">
                    {{ cultivo.codigo }}
                  </span>
                  <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-lg uppercase border bg-amber-100 text-amber-800 border-amber-200">
                    {{ cultivo.etapa }} &bull; Sem {{ cultivo.semana_actual }}
                  </span>
                  <span class="text-[10px] font-semibold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md border border-slate-200">
                    ☀️ {{ cultivo.fotoperiodo }}
                  </span>
                </div>

                <h3 class="text-base sm:text-lg font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors">
                  {{ cultivo.nombre_lote }}
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                  Variedad: <strong class="text-slate-800">{{ cultivo.variedad_nombre }}</strong> &bull; <span>{{ cultivo.banco }}</span>
                </p>
              </div>

              <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                ✓ Óptimo
              </span>
            </div>

            <!-- Barra de avance de ciclo -->
            <div class="space-y-1.5 bg-slate-50/90 p-3.5 rounded-2xl border border-slate-200/70">
              <div class="flex justify-between items-center text-xs">
                <span class="font-bold text-slate-700">
                  Semana {{ cultivo.semana_actual }} &bull; Día {{ cultivo.dia_actual || cultivo.semana_actual * 7 }} de {{ cultivo.dias_totales_estimados }}
                </span>
                <span class="font-extrabold text-emerald-700">
                  {{ Math.min(100, Math.round(((cultivo.semana_actual || 1) / (cultivo.dias_totales_estimados ? Math.round(cultivo.dias_totales_estimados / 7) : 9)) * 100)) }}%
                </span>
              </div>
              <div class="w-full h-2.5 bg-slate-200/80 rounded-full overflow-hidden">
                <div
                  class="h-full bg-emerald-500 rounded-full transition-all"
                  :style="{ width: Math.min(100, Math.round(((cultivo.semana_actual || 1) / (cultivo.dias_totales_estimados ? Math.round(cultivo.dias_totales_estimados / 7) : 9)) * 100)) + '%' }"
                ></div>
              </div>
            </div>

            <!-- Footer de Card con Sala y Botón Ver Detalle -->
            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
              <div class="flex items-center gap-2">
                <span class="font-semibold text-slate-700">🏢 {{ cultivo.sala_nombre }}</span>
                <span class="text-slate-300">&bull;</span>
                <span>{{ cultivo.plantas_totales }} macetas</span>
              </div>

              <div class="flex items-center gap-1.5">
                <button
                  @click="modalDetalleCultivo = cultivo"
                  class="inline-flex items-center gap-1 text-xs font-bold text-white bg-slate-900 hover:bg-emerald-600 px-3 py-1.5 rounded-xl transition-all cursor-pointer shadow-2xs"
                >
                  Ver Detalle &rarr;
                </button>
                <Link
                  :href="route('cultivos.show', cultivo.id)"
                  class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all text-xs"
                >
                  Página
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- =============================================================== -->
      <!-- 4. SECCIÓN: PLANIFICACIONES & EVENTOS vs REGISTROS DIARIOS       -->
      <!-- =============================================================== -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- PLANIFICACIONES & EVENTOS (7 COLUMNAS) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Planificaciones & Eventos Operativos</h3>
              <p class="text-xs text-slate-400">Eventos vinculados a TimeLine o ad-hoc</p>
            </div>
            <button @click="modalEventoAbierto = true" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-xl text-xs font-bold cursor-pointer">
              + Nuevo Evento
            </button>
          </div>

          <!-- Filtros de eventos -->
          <div class="flex gap-1.5 overflow-x-auto pb-1 text-xs font-bold">
            <button @click="filtroEventos = 'PENDIENTES'" :class="filtroEventos === 'PENDIENTES' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl cursor-pointer">
              Pendientes
            </button>
            <button @click="filtroEventos = 'PLANIFICADOS'" :class="filtroEventos === 'PLANIFICADOS' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl cursor-pointer">
              Con Planificación
            </button>
            <button @click="filtroEventos = 'INDEPENDIENTES'" :class="filtroEventos === 'INDEPENDIENTES' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl cursor-pointer">
              Eventos Libres
            </button>
            <button @click="filtroEventos = 'TODOS'" :class="filtroEventos === 'TODOS' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl cursor-pointer">
              Todos
            </button>
          </div>

          <!-- Lista de Eventos -->
          <div class="space-y-3 max-h-[440px] overflow-y-auto">
            <div v-for="evento in eventosFiltrados" :key="evento.id" @click="toggleEstadoEvento(evento.id)" class="p-3.5 bg-slate-50 hover:bg-white rounded-2xl border border-slate-200 transition-all cursor-pointer space-y-1.5">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-200 text-slate-800">{{ evento.tipo_evento?.nombre || 'Evento' }}</span>
                  <span v-if="evento.time_line_id" class="text-[9px] font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded">🔗 Plan: {{ evento.time_line?.nombre }}</span>
                  <span v-else class="text-[9px] font-bold text-indigo-800 bg-indigo-100 px-2 py-0.5 rounded">⚡ Libre</span>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded" :class="evento.estado_evento?.nombre === 'REALIZADO' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'">
                  {{ evento.estado_evento?.nombre || 'PENDIENTE' }}
                </span>
              </div>
              <h4 class="text-xs font-bold text-slate-900" :class="{ 'line-through text-slate-400': evento.estado_evento?.nombre === 'REALIZADO' }">{{ evento.nombre }}</h4>
              <p v-if="evento.descripcion" class="text-[11px] text-slate-500 line-clamp-2">{{ evento.descripcion }}</p>
              <div class="flex items-center gap-3 text-[10px] text-slate-400 pt-1 border-t border-slate-100 font-medium">
                <span>🌱 {{ evento.cultivo?.nombre_lote }}</span>
                <span>📅 {{ evento.fecha_inicio_planificacion }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- REGISTROS DIARIOS DE VARIABLES (5 COLUMNAS) -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Registros Diarios de Variables</h3>
              <p class="text-xs text-slate-400">VPD, CO2, Temp (Aire/Sol/Sustrato) & Caudales</p>
            </div>
            <button @click="modalRegistroAbierto = true" class="bg-slate-900 hover:bg-slate-800 text-white px-3 py-1.5 rounded-xl text-xs font-bold cursor-pointer">
              + Nuevo
            </button>
          </div>

          <!-- Feed de Registros -->
          <div class="space-y-3.5 max-h-[440px] overflow-y-auto">
            <div v-for="item in registrosFiltrados" :key="item.id" class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2">
              <div class="flex items-start justify-between">
                <div>
                  <span class="text-[10px] font-mono font-bold bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded">{{ item.cultivo?.codigo }}</span>
                  <h4 class="text-xs font-bold text-slate-900 mt-1">{{ item.nombre }}</h4>
                </div>
                <span class="text-[10px] text-slate-400">{{ item.fecha_registro?.slice(0, 16) }}</span>
              </div>

              <!-- Grid de variables del modelo Registro -->
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5 text-[10px]">
                <div class="bg-white p-1.5 rounded-lg border border-slate-200">
                  <span class="text-[9px] text-slate-400 uppercase">Aire / HR</span>
                  <div class="font-bold text-slate-800">{{ item.temperatura }}°C / {{ item.humedad }}%</div>
                </div>
                <div class="bg-white p-1.5 rounded-lg border border-slate-200">
                  <span class="text-[9px] text-slate-400 uppercase">VPD / CO2</span>
                  <div class="font-bold text-amber-700">{{ item.vpd }} kPa / {{ item.co2 }} ppm</div>
                </div>
                <div class="bg-white p-1.5 rounded-lg border border-slate-200">
                  <span class="text-[9px] text-slate-400 uppercase">Sol / Sustrato</span>
                  <div class="font-bold text-teal-700">{{ item.temperatura_solucion }}°C / {{ item.temperatura_sustrato }}°C</div>
                </div>
                <div class="bg-white p-1.5 rounded-lg border border-slate-200">
                  <span class="text-[9px] text-slate-400 uppercase">Caudal In/Ex</span>
                  <div class="font-mono font-bold text-slate-700">{{ item.flujo_hora_intraccion }}/{{ item.flujo_hora_extracion }}</div>
                </div>
              </div>

              <div class="text-[10px] text-slate-400 pt-1 flex justify-between">
                <span>Por: <strong>{{ item.user?.name || 'Operador' }}</strong></span>
                <span class="font-mono">ΔP: {{ (item.flujo_hora_extracion - item.flujo_hora_intraccion).toFixed(0) }} m³/h</span>
              </div>
            </div>
          </div>
        </div>

      </div>

    </main>
  </div>
</template>
