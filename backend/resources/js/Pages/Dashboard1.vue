<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

// =========================================================================
// TIPOS E INTERFACES (Compatibles con Laravel Eloquent Models)
// =========================================================================
export interface Sala {
    id: string
    nombre: string
    codigo: string
    tipo: 'VEGETACION' | 'FLORACION' | 'SECADO' | 'MADRES_Y_ESQUEJES' | 'MULTIPROPOSITO'
    estado: 'ACTIVA' | 'MANTENIMIENTO' | 'LIMPIEZA' | 'INACTIVA'
    ancho_m: number
    largo_m: number
    alto_m: number
    volumen_m3: number
    capacidad_macetas: number
    potencia_luces_w: number
    temperatura_c: number
    humedad_pct: number
    vpd_kpa: number
    co2_ppm: number
    cultivo_activo_id?: string | null
}

export interface Cultivo {
    id: string
    codigo: string
    nombre_lote: string
    sala_id: string
    sala_nombre: string
    variedad_id: string
    variedad_nombre: string
    banco: string
    tipo_categoria: 'SATIVA' | 'INDICA' | 'HIBRIDA' | 'AUTOMATICA' | 'CBD'
    etapa: 'VEGETATIVO' | 'FLORACION' | 'SECADO' | 'CURADO'
    salud: 'OPTIMO' | 'ATENCION' | 'CRITICO'
    dia_actual: number
    dias_totales_estimados: number
    semana_actual: number
    plantas_totales: number
    fotoperiodo: string
    notas?: string
}

export interface ActualizacionBitacora {
    id: string
    cultivo_id: string
    cultivo_lote: string
    sala_nombre: string
    fecha_hora: string
    tipo: 'RIEGO' | 'PODA' | 'CLIMA' | 'NUTRICION' | 'PREVENTIVO' | 'TRANSPLANTE' | 'COSECHA' | 'OBSERVACION'
    titulo: string
    descripcion: string
    ec?: number | null
    ph?: number | null
    temp_c?: number | null
    hum_pct?: number | null
    autor: string
}

export interface TareaPendiente {
    id: string
    titulo: string
    descripcion?: string
    categoria: 'RIEGO' | 'NUTRICION' | 'MANTENIMIENTO' | 'SANIDAD' | 'CALIBRACION' | 'COSECHA'
    prioridad: 'ALTA' | 'MEDIA' | 'BAJA'
    fecha_limite: string
    completada: boolean
    sala_nombre?: string
    cultivo_codigo?: string
    asignado_a?: string
}

// =========================================================================
// PROPS ENVIADAS DESDE EL CONTROLADOR LARAVEL (Inertia::render('Dashboard', [...]))
// =========================================================================
const props = withDefaults(
    defineProps<{
        salas?: Sala[]
        cultivos?: Cultivo[]
        bitacoras?: ActualizacionBitacora[]
        tareas?: TareaPendiente[]
        totalVariedades?: number
    }>(),
    {
        salas: () => [],
        cultivos: () => [],
        bitacoras: () => [],
        tareas: () => [],
        totalVariedades: 12,
    }
)

// =========================================================================
// ESTADOS LOCALES Y FILTROS DEL DASHBOARD
// =========================================================================
const filtroEtapaCultivo = ref<'TODOS' | 'VEGETATIVO' | 'FLORACION' | 'SECADO'>('TODOS')
const filtroTareas = ref<'TODAS' | 'PENDIENTES' | 'COMPLETADAS' | 'ALTA_PRIORIDAD'>('PENDIENTES')
const modalBitacoraAbierto = ref(false)
const modalTareaAbierto = ref(false)
const cultivoSeleccionadoParaBitacora = ref<Cultivo | null>(null)

// Lista reactiva de tareas locales (para toggle inmediato)
const listaTareas = ref<TareaPendiente[]>([...props.tareas])
const listaBitacoras = ref<ActualizacionBitacora[]>([...props.bitacoras])

// Formulario rápido de Bitácora
const formBitacora = useForm({
    cultivo_id: '',
    tipo: 'RIEGO' as ActualizacionBitacora['tipo'],
    titulo: '',
    descripcion: '',
    ec: null as number | null,
    ph: null as number | null,
})

// Formulario rápido de Tarea
const formTarea = useForm({
    titulo: '',
    categoria: 'RIEGO' as TareaPendiente['categoria'],
    prioridad: 'ALTA' as TareaPendiente['prioridad'],
    fecha_limite: 'Hoy',
    sala_nombre: '',
})

// =========================================================================
// COMPUTED / MÉTRICAS
// =========================================================================
const salasActivas = computed(() => props.salas.filter((s) => s.estado === 'ACTIVA').length)
const totalPlantas = computed(() => props.cultivos.reduce((acc, c) => acc + (c.plantas_totales || 0), 0))
const potenciaTotalKw = computed(() => (props.salas.reduce((acc, s) => acc + (s.potencia_luces_w || 0), 0) / 1000).toFixed(1))
const tareasPendientesCount = computed(() => listaTareas.value.filter((t) => !t.completada).length)

const cultivosFiltrados = computed(() => {
    if (filtroEtapaCultivo.value === 'TODOS') return props.cultivos
    return props.cultivos.filter((c) => c.etapa === filtroEtapaCultivo.value)
})

const tareasFiltradas = computed(() => {
    if (filtroTareas.value === 'PENDIENTES') return listaTareas.value.filter((t) => !t.completada)
    if (filtroTareas.value === 'COMPLETADAS') return listaTareas.value.filter((t) => t.completada)
    if (filtroTareas.value === 'ALTA_PRIORIDAD') return listaTareas.value.filter((t) => t.prioridad === 'ALTA' && !t.completada)
    return listaTareas.value
})

// =========================================================================
// MÉTODOS Y ACCIONES
// =========================================================================
function toggleTarea(tareaId: string) {
    const tar = listaTareas.value.find((t) => t.id === tareaId)
    if (tar) {
        tar.completada = !tar.completada
    }
}

function abrirModalBitacora(cultivo?: Cultivo) {
    if (cultivo) {
        cultivoSeleccionadoParaBitacora.value = cultivo
        formBitacora.cultivo_id = cultivo.id
    } else if (props.cultivos.length > 0) {
        cultivoSeleccionadoParaBitacora.value = props.cultivos[0]
        formBitacora.cultivo_id = props.cultivos[0].id
    }
    modalBitacoraAbierto.value = true
}

function guardarBitacora() {
    if (!formBitacora.titulo.trim() || !formBitacora.cultivo_id) return

    const cultivo = props.cultivos.find((c) => c.id === formBitacora.cultivo_id)
    const nuevoRegistro: ActualizacionBitacora = {
        id: 'bit-' + Date.now(),
        cultivo_id: formBitacora.cultivo_id,
        cultivo_lote: cultivo ? cultivo.nombre_lote : 'Lote General',
        sala_nombre: cultivo ? cultivo.sala_nombre : 'Sala General',
        fecha_hora: 'Hace un momento',
        tipo: formBitacora.tipo,
        titulo: formBitacora.titulo.trim(),
        descripcion: formBitacora.descripcion.trim(),
        ec: formBitacora.ec,
        ph: formBitacora.ph,
        autor: 'Operador Principal',
    }

    listaBitacoras.value.unshift(nuevoRegistro)
    modalBitacoraAbierto.value = false
    formBitacora.reset()
}

function guardarTarea() {
    if (!formTarea.titulo.trim()) return

    const nueva: TareaPendiente = {
        id: 'tar-' + Date.now(),
        titulo: formTarea.titulo.trim(),
        categoria: formTarea.categoria,
        prioridad: formTarea.prioridad,
        fecha_limite: formTarea.fecha_limite || 'Hoy',
        completada: false,
        sala_nombre: formTarea.sala_nombre || 'General',
    }

    listaTareas.value.unshift(nueva)
    modalTareaAbierto.value = false
    formTarea.reset()
}
</script>

<template>
    <Head title="Dashboard Principal - CultivoOS" />

    <div class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased selection:bg-emerald-500 selection:text-white flex flex-col justify-between">
        
        <!-- ================================================================= -->
        <!-- HEADER PRINCIPAL CON ACCIONES RÁPIDAS                             -->
        <!-- ================================================================= -->
        <header class="bg-white border-b border-slate-200/80 px-6 sm:px-8 py-4 sticky top-0 z-30 shadow-xs">
            <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 text-white shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight text-slate-800 uppercase flex items-center gap-2">
                            Cultivo<span class="text-emerald-600">OS</span>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                Centro de Control
                            </span>
                        </h1>
                        <p class="text-xs text-slate-400 font-medium">Monitoreo Agronómico, Salas & Genéticas</p>
                    </div>
                </div>

                <!-- ACCESOS DIRECTOS A FORMULARIOS (SALAS, VARIEDADES, BITÁCORA) -->
                <div class="flex items-center flex-wrap gap-2">
                    <a
                        :href="typeof route === 'function' ? route('salas.creates') : '#'"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs transition-all cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Nueva Sala</span>
                    </a>

                    <a
                        :href="typeof route === 'function' ? route('catalogo-variedades.index') : '#'"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm shadow-emerald-200 transition-all cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Nueva Variedad</span>
                    </a>

                    <button
                        type="button"
                        @click="abrirModalBitacora()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white transition-all cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>+ Bitácora</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- ================================================================= -->
        <!-- CONTENIDO PRINCIPAL DEL DASHBOARD                                -->
        <!-- ================================================================= -->
        <main class="flex-1 p-4 sm:p-8">
            <div class="max-w-7xl mx-auto space-y-8">

                <!-- 1. GRID DE MÉTRICAS CLAVE -->
                <section class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Salas Activas -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between text-slate-400">
                            <span class="text-xs font-bold uppercase tracking-wider">Salas Operativas</span>
                            <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </span>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl sm:text-3xl font-extrabold text-slate-800">{{ salasActivas }}</span>
                            <span class="text-xs text-slate-400 font-semibold">de {{ props.salas.length }} totales</span>
                        </div>
                    </div>

                    <!-- Cultivos Activos -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between text-slate-400">
                            <span class="text-xs font-bold uppercase tracking-wider">Cultivos en Curso</span>
                            <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </span>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl sm:text-3xl font-extrabold text-slate-800">{{ props.cultivos.length }}</span>
                            <span class="text-xs text-slate-400 font-semibold">lotes activos</span>
                        </div>
                    </div>

                    <!-- Plantas Totales -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between text-slate-400">
                            <span class="text-xs font-bold uppercase tracking-wider">Población Vegetal</span>
                            <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            </span>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl sm:text-3xl font-extrabold text-slate-800">{{ totalPlantas }}</span>
                            <span class="text-xs text-slate-400 font-semibold">plantas</span>
                        </div>
                    </div>

                    <!-- Catálogo de Variedades -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between text-slate-400">
                            <span class="text-xs font-bold uppercase tracking-wider">Variedades Genéticas</span>
                            <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </span>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl sm:text-3xl font-extrabold text-slate-800">{{ props.totalVariedades }}</span>
                            <span class="text-xs text-slate-400 font-semibold">en catálogo</span>
                        </div>
                    </div>

                    <!-- Tareas Pendientes -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between col-span-2 lg:col-span-1">
                        <div class="flex items-center justify-between text-slate-400">
                            <span class="text-xs font-bold uppercase tracking-wider">Tareas Pendientes</span>
                            <span class="p-1.5 bg-rose-50 text-rose-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </span>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl sm:text-3xl font-extrabold text-rose-600">{{ tareasPendientesCount }}</span>
                            <span class="text-xs text-slate-400 font-semibold">por completar</span>
                        </div>
                    </div>
                </section>

                <!-- 2. SECCIÓN PRINCIPAL DE CULTIVOS EN CURSO -->
                <section class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                Cultivos Activos y Ciclos Botánicos
                            </h2>
                            <p class="text-xs text-slate-500">Monitoreo de etapa fenológica, salud y días transcurridos</p>
                        </div>

                        <!-- Filtros por etapa -->
                        <div class="flex items-center gap-1.5 bg-white p-1 rounded-xl border border-slate-200 self-start sm:self-auto text-xs font-bold">
                            <button
                                type="button"
                                @click="filtroEtapaCultivo = 'TODOS'"
                                :class="filtroEtapaCultivo === 'TODOS' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900'"
                                class="px-3 py-1 rounded-lg transition-colors cursor-pointer"
                            >
                                Todos ({{ props.cultivos.length }})
                            </button>
                            <button
                                type="button"
                                @click="filtroEtapaCultivo = 'FLORACION'"
                                :class="filtroEtapaCultivo === 'FLORACION' ? 'bg-amber-500 text-white' : 'text-slate-600 hover:text-slate-900'"
                                class="px-3 py-1 rounded-lg transition-colors cursor-pointer"
                            >
                                Floración
                            </button>
                            <button
                                type="button"
                                @click="filtroEtapaCultivo = 'VEGETATIVO'"
                                :class="filtroEtapaCultivo === 'VEGETATIVO' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:text-slate-900'"
                                class="px-3 py-1 rounded-lg transition-colors cursor-pointer"
                            >
                                Vegetativo
                            </button>
                            <button
                                type="button"
                                @click="filtroEtapaCultivo = 'SECADO'"
                                :class="filtroEtapaCultivo === 'SECADO' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:text-slate-900'"
                                class="px-3 py-1 rounded-lg transition-colors cursor-pointer"
                            >
                                Secado
                            </button>
                        </div>
                    </div>

                    <!-- Grid de Cards de Cultivos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            v-for="cultivo in cultivosFiltrados"
                            :key="cultivo.id"
                            class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition-shadow relative flex flex-col justify-between space-y-4"
                        >
                            <!-- Header de la card -->
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[11px] font-mono font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                                            {{ cultivo.codigo }}
                                        </span>
                                        <span
                                            class="text-[10px] font-bold px-2 py-0.5 rounded uppercase"
                                            :class="cultivo.etapa === 'FLORACION' ? 'bg-amber-100 text-amber-800' : cultivo.etapa === 'VEGETATIVO' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800'"
                                        >
                                            {{ cultivo.etapa }} &bull; Sem {{ cultivo.semana_actual }}
                                        </span>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900">{{ cultivo.nombre_lote }}</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        {{ cultivo.variedad_nombre }} &bull; <span class="font-medium">{{ cultivo.banco }}</span>
                                    </p>
                                </div>

                                <div class="text-right shrink-0">
                                    <span
                                        class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full"
                                        :class="cultivo.salud === 'OPTIMO' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full" :class="cultivo.salud === 'OPTIMO' ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                                        {{ cultivo.salud }}
                                    </span>
                                </div>
                            </div>

                            <!-- Barra de progreso del ciclo -->
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-xs font-semibold text-slate-600">
                                    <span>Día {{ cultivo.dia_actual }} de {{ cultivo.dias_totales_estimados }}</span>
                                    <span>{{ Math.round((cultivo.dia_actual / cultivo.dias_totales_estimados) * 100) }}% completado</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div
                                        class="h-full bg-emerald-500 rounded-full transition-all"
                                        :style="{ width: Math.min(100, Math.round((cultivo.dia_actual / cultivo.dias_totales_estimados) * 100)) + '%' }"
                                    ></div>
                                </div>
                            </div>

                            <!-- Footer de la card: Sala + Acciones -->
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>{{ cultivo.sala_nombre }}</span>
                                    <span class="text-slate-300">&bull;</span>
                                    <span>{{ cultivo.plantas_totales }} macetas</span>
                                </div>

                                <button
                                    type="button"
                                    @click="abrirModalBitacora(cultivo)"
                                    class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-colors cursor-pointer flex items-center gap-1"
                                >
                                    <span>+ Registro</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 3. SECCIÓN DOBLE: PENDIENTES & BITÁCORA EN VIVO -->
                <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    <!-- COLUMNA IZQUIERDA: LISTA DE PENDIENTES (7 COLS) -->
                    <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    Lista de Tareas Agronómicas
                                </h3>
                                <p class="text-xs text-slate-400">Riegos, fertilización, podas y mantenimiento</p>
                            </div>

                            <button
                                type="button"
                                @click="modalTareaAbierto = true"
                                class="text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-xl transition-colors cursor-pointer"
                            >
                                + Nueva Tarea
                            </button>
                        </div>

                        <!-- Filtros de tareas -->
                        <div class="flex gap-2 text-xs font-bold overflow-x-auto pb-1">
                            <button
                                type="button"
                                @click="filtroTareas = 'PENDIENTES'"
                                :class="filtroTareas === 'PENDIENTES' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
                                class="px-3 py-1 rounded-lg cursor-pointer transition-colors"
                            >
                                Pendientes ({{ tareasPendientesCount }})
                            </button>
                            <button
                                type="button"
                                @click="filtroTareas = 'ALTA_PRIORIDAD'"
                                :class="filtroTareas === 'ALTA_PRIORIDAD' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600'"
                                class="px-3 py-1 rounded-lg cursor-pointer transition-colors"
                            >
                                Urgentes
                            </button>
                            <button
                                type="button"
                                @click="filtroTareas = 'COMPLETADAS'"
                                :class="filtroTareas === 'COMPLETADAS' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600'"
                                class="px-3 py-1 rounded-lg cursor-pointer transition-colors"
                            >
                                Hechas
                            </button>
                        </div>

                        <!-- Listado interactivo de tareas -->
                        <div class="space-y-2.5 max-h-[380px] overflow-y-auto pr-1">
                            <div
                                v-for="tarea in tareasFiltradas"
                                :key="tarea.id"
                                @click="toggleTarea(tarea.id)"
                                class="p-3.5 rounded-2xl border transition-all cursor-pointer flex items-start gap-3 select-none"
                                :class="tarea.completada ? 'bg-slate-50 border-slate-200 opacity-60' : 'bg-white border-slate-200/90 hover:border-emerald-300 hover:shadow-xs'"
                            >
                                <div
                                    class="w-5 h-5 rounded-lg border mt-0.5 flex items-center justify-center shrink-0 transition-colors"
                                    :class="tarea.completada ? 'bg-emerald-600 border-emerald-600 text-white' : 'border-slate-300 bg-white'"
                                >
                                    <svg v-if="tarea.completada" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <h4 class="text-xs font-bold text-slate-800 truncate" :class="{ 'line-through text-slate-400': tarea.completada }">
                                            {{ tarea.titulo }}
                                        </h4>
                                        <span
                                            class="text-[10px] font-bold px-2 py-0.5 rounded shrink-0"
                                            :class="tarea.prioridad === 'ALTA' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-600'"
                                        >
                                            {{ tarea.prioridad }}
                                        </span>
                                    </div>
                                    <p v-if="tarea.descripcion" class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">
                                        {{ tarea.descripcion }}
                                    </p>
                                    <div class="mt-1 flex items-center gap-2 text-[10px] font-medium text-slate-400">
                                        <span>{{ tarea.sala_nombre }}</span>
                                        <span>&bull;</span>
                                        <span>Vence: {{ tarea.fecha_limite }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA DERECHA: BITÁCORA Y ACTUALIZACIONES (5 COLS) -->
                    <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Actualizaciones en Vivo
                                </h3>
                                <p class="text-xs text-slate-400">Feed de bitácora y manejos recientes</p>
                            </div>
                        </div>

                        <div class="space-y-3.5 max-h-[420px] overflow-y-auto pr-1">
                            <div
                                v-for="item in listaBitacoras"
                                :key="item.id"
                                class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200/70 space-y-2"
                            >
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded uppercase">
                                            {{ item.tipo }}
                                        </span>
                                        <span class="text-[11px] font-semibold text-slate-600 truncate">{{ item.sala_nombre }}</span>
                                    </div>
                                    <span class="text-[10px] font-medium text-slate-400 shrink-0">{{ item.fecha_hora }}</span>
                                </div>

                                <h4 class="text-xs font-bold text-slate-800">{{ item.titulo }}</h4>
                                <p class="text-[11px] text-slate-500 leading-relaxed">{{ item.descripcion }}</p>

                                <div v-if="item.ec || item.ph" class="flex gap-2 pt-1 border-t border-slate-200/50 text-[10px] font-mono text-slate-600">
                                    <span v-if="item.ec" class="bg-white px-2 py-0.5 rounded border border-slate-200">
                                        EC: <strong>{{ item.ec }} mS/cm</strong>
                                    </span>
                                    <span v-if="item.ph" class="bg-white px-2 py-0.5 rounded border border-slate-200">
                                        pH: <strong>{{ item.ph }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </main>

        <!-- FOOTER -->
        <footer class="bg-white border-t border-slate-200/80 px-6 py-4 text-center text-xs text-slate-400 shrink-0">
            CultivoOS &bull; Panel de Monitoreo &bull; Laravel 11 / 12 + Inertia Vue 3 + Tailwind CSS
        </footer>
    </div>
</template>
