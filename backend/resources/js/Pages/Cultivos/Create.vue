<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm, Head } from '@inertiajs/vue3'

// ==========================================
// TIPOS E INTERFACES
// ==========================================
export interface TipoVariedad {
    id: string
    nombre: string
    categoria?: 'SATIVA' | 'INDICA' | 'HIBRIDA' | 'AUTOMATICA' | 'CBD' | string
    descripcion?: string
    color?: string
    proporcion?: string
}

// ==========================================
// PROPS DEL CONTROLADOR LARAVEL
// (Inertia::render('CatalogoVariedades/Create', ['tiposVariedad' => $tipos]))
// ==========================================
const props = withDefaults(
    defineProps<{
        tiposVariedad?: TipoVariedad[]
    }>(),
    {
        tiposVariedad: () => [
            {
                id: '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                nombre: 'Sativa Dominante',
                categoria: 'SATIVA',
                descripcion: 'Estructura esbelta, entrenudos amplios, floración moderada a larga con terpenos cítricos.',
                color: 'emerald',
                proporcion: '80% Sativa / 20% Indica',
            },
            {
                id: '7c2ceb5e-4c8e-4cae-8cee-3c1e8c4eda7e',
                nombre: 'Indica Dominante',
                categoria: 'INDICA',
                descripcion: 'Porte arbustivo compacto, cogollos densos y resinosos, ciclo de floración corto.',
                color: 'indigo',
                proporcion: '80% Indica / 20% Sativa',
            },
            {
                id: '5a3deb6f-5d9f-4dbf-7dff-4d2f9d5feb8f',
                nombre: 'Híbrida Equilibrada',
                categoria: 'HIBRIDA',
                descripcion: 'Equilibrio morfológico óptimo, vigor híbrido y producción uniforme.',
                color: 'amber',
                proporcion: '50% Sativa / 50% Indica',
            },
            {
                id: '3e4feb7a-6ea0-4ec0-6e00-5e3fae6afc90',
                nombre: 'Autofloreciente (Ruderalis)',
                categoria: 'AUTOMATICA',
                descripcion: 'No fotodependiente. Inicia floración automática a partir de la 3° semana.',
                color: 'rose',
                proporcion: 'Automática',
            },
            {
                id: '1f5aeb8b-7fb1-4fd1-5f11-6f4bbf7b0da1',
                nombre: 'Rica en CBD / Medicinal',
                categoria: 'CBD',
                descripcion: 'Selección enfocada en alto contenido de cannabidiol para uso fitoterapéutico.',
                color: 'teal',
                proporcion: 'Ratio 1:1 o CBD Pure',
            },
        ],
    }
)

// ==========================================
// FORMULARIO CON INERTIA useForm
// Mapea exactamente el modelo CatalogoVariedad (HasUuids)
// ==========================================
const form = useForm({
    nombre: '',
    banco: '',
    descripcion: '',
    dias_ciclo: null as number | null,
    tipo_variedad_id: '' as string,
})

// Bancos de semillas sugeridos para acceso rápido
const bancosSugeridos = [
    'Royal Queen Seeds',
    "Barney's Farm",
    'Sweet Seeds',
    'Fast Buds',
    'Dutch Passion',
    'DNA Genetics',
    'Humboldt Seed Co.',
    'Sensi Seeds',
]

// Presets de días de ciclo
const diasPresets = [
    { dias: 55, label: '55d (~8 sem)', nota: 'Rápida' },
    { dias: 63, label: '63d (9 sem)', nota: 'Estándar' },
    { dias: 70, label: '70d (10 sem)', nota: 'Media' },
    { dias: 84, label: '84d (12 sem)', nota: 'Larga' },
]

// Estado de guardado local / modal de éxito simulado
const guardadoExitoso = ref(false)
const payloadEnviado = ref<any>(null)

// Cálculo en tiempo real de semanas
const semanasCalculadas = computed(() => {
    if (!form.dias_ciclo || form.dias_ciclo <= 0) return null
    return (form.dias_ciclo / 7).toFixed(1)
})

// Tipo de variedad seleccionado
const tipoSeleccionado = computed(() => {
    return props.tiposVariedad.find((t) => t.id === form.tipo_variedad_id) || null
})

// Acciones rápidas
function setBanco(nombreBanco: string) {
    form.banco = nombreBanco
}

function setDias(dias: number) {
    form.dias_ciclo = dias
}

function seleccionarTipo(id: string) {
    form.tipo_variedad_id = id
}

// Envío a Laravel
function submit() {
    // Validaciones locales antes del submit
    form.clearErrors()
    let hayErrores = false

    if (!form.nombre?.trim()) {
        form.setError('nombre', 'El nombre de la variedad es obligatorio.')
        hayErrores = true
    }
    if (!form.tipo_variedad_id) {
        form.setError('tipo_variedad_id', 'Debes seleccionar el tipo de variedad genética.')
        hayErrores = true
    }
    if (form.dias_ciclo !== null && (form.dias_ciclo <= 0 || isNaN(form.dias_ciclo))) {
        form.setError('dias_ciclo', 'Los días de ciclo deben ser un número decimal mayor a 0.')
        hayErrores = true
    }

    if (hayErrores) return

    payloadEnviado.value = {
        nombre: form.nombre.trim(),
        banco: form.banco.trim() || null,
        tipo_variedad_id: form.tipo_variedad_id,
        dias_ciclo: form.dias_ciclo ? Number(Number(form.dias_ciclo).toFixed(2)) : null,
        descripcion: form.descripcion.trim() || null,
    }

    // Llamada oficial Inertia:
    // @ts-ignore
    if (typeof route === 'function') {
        // @ts-ignore
        form.post(route('catalogo-variedades.store'), {
            preserveScroll: true,
            onSuccess: () => {
                guardadoExitoso.value = true
                form.reset()
            },
        })
    } else {
        // Modo demostración
        guardadoExitoso.value = true
    }
}

function reiniciar() {
    form.reset()
    form.clearErrors()
    guardadoExitoso.value = false
    payloadEnviado.value = null
}
</script>

<template>
    <Head title="Nueva Variedad - Catálogo Genético - CultivoOS" />

    <div class="min-h-screen bg-slate-50 font-sans flex flex-col justify-between text-slate-900 antialiased selection:bg-emerald-500 selection:text-white">
        <!-- HEADER SUPERIOR -->
        <header class="bg-white border-b border-slate-200/80 px-6 sm:px-8 py-4 flex justify-between items-center shrink-0 sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 text-white shrink-0">
                    <!-- Icono SVG: Hoja Genética -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold tracking-tight text-slate-800 uppercase flex items-center gap-2">
                        Cultivo<span class="text-emerald-600">OS</span>
                        <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                            Catálogo
                        </span>
                    </h1>
                    <p class="text-xs text-slate-400 font-medium">Gestión de Variedades y Linaje Botánico</p>
                </div>
            </div>

            <div class="flex items-center gap-3 text-xs font-semibold">
                <span class="hidden sm:flex items-center gap-1.5 text-slate-500 bg-slate-100/80 px-3 py-1.5 rounded-xl border border-slate-200">
                    <!-- Icono SVG: Base de Datos / Laravel Model -->
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                    CatalogoVariedad (UUID)
                </span>
            </div>
        </header>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="flex-1 p-4 sm:p-8 flex flex-col justify-center">
            <div class="max-w-4xl mx-auto w-full">

                <!-- ENCABEZADO DE SECCIÓN -->
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-600 mb-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Formulario de Registro Único
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            Nueva Variedad en Catálogo
                        </h2>
                        <p class="text-sm text-slate-500 mt-0.5">
                            Completá la información botánica, banco productor y ciclo de floración para alimentar los cultivos futuros.
                        </p>
                    </div>

                    <!-- Badge de HasUuids -->
                    <div class="inline-flex items-center gap-2 bg-white px-3.5 py-2 rounded-2xl border border-slate-200 shadow-xs text-xs self-start sm:self-auto font-mono text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>PK: HasUuids</span>
                    </div>
                </div>

                <!-- ALERTA DE ÉXITO -->
                <div v-if="guardadoExitoso" class="mb-6 bg-emerald-50 border-2 border-emerald-500/30 rounded-3xl p-6 shadow-lg shadow-emerald-500/5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-bold text-emerald-950">¡Variedad Registrada con Éxito!</h3>
                            <p class="text-xs text-emerald-800 mt-1">
                                Los datos fueron formateados según el modelo <code class="font-mono bg-emerald-100/80 px-1 py-0.5 rounded font-bold">CatalogoVariedad</code> y vinculados a su tipo genético.
                            </p>

                            <div v-if="payloadEnviado" class="mt-3 p-3 bg-white/80 rounded-xl border border-emerald-200 text-xs font-mono text-slate-700">
                                <div class="font-bold text-emerald-900 mb-1">Payload enviado:</div>
                                <pre class="overflow-x-auto text-[11px]">{{ JSON.stringify(payloadEnviado, null, 2) }}</pre>
                            </div>

                            <div class="mt-4 flex gap-3">
                                <button
                                    type="button"
                                    @click="reiniciar"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-xs cursor-pointer"
                                >
                                    Registrar Otra Variedad
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD PRINCIPAL DEL FORMULARIO -->
                <form @submit.prevent="submit" class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-6 sm:p-10 relative overflow-hidden space-y-8">
                    <!-- Indicador visual lateral verde -->
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-600"></div>

                    <!-- ========================================================= -->
                    <!-- SECCIÓN 1: IDENTIFICACIÓN PRINCIPAL Y BANCO              -->
                    <!-- ========================================================= -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <!-- Icono SVG: Tag / Identificación -->
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                                1. Identificación y Origen
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- NOMBRE -->
                            <div>
                                <label for="nombre" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">
                                    Nombre de la Variedad <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        id="nombre"
                                        v-model="form.nombre"
                                        type="text"
                                        placeholder="Ej. Amnesia Haze, Gorilla Glue #4, Gelato 33"
                                        class="w-full bg-slate-50 border rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 outline-none transition-all"
                                        :class="form.errors.nombre ? 'border-red-400 bg-red-50/30' : 'border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500'"
                                    />
                                    <div class="absolute right-3.5 top-3.5 text-slate-400 pointer-events-none">
                                        <!-- Icono SVG: Hoja -->
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                </div>
                                <p v-if="form.errors.nombre" class="mt-1.5 text-xs font-semibold text-red-500 ml-1">
                                    {{ form.errors.nombre }}
                                </p>
                            </div>

                            <!-- BANCO DE SEMILLAS -->
                            <div>
                                <label for="banco" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">
                                    Banco de Semillas / Breeder
                                </label>
                                <div class="relative">
                                    <input
                                        id="banco"
                                        v-model="form.banco"
                                        type="text"
                                        placeholder="Ej. Royal Queen Seeds, Barney's Farm, Clon Élite"
                                        class="w-full bg-slate-50 border rounded-2xl px-4 py-3.5 text-sm font-medium text-slate-800 outline-none transition-all"
                                        :class="form.errors.banco ? 'border-red-400 bg-red-50/30' : 'border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500'"
                                    />
                                    <div class="absolute right-3.5 top-3.5 text-slate-400 pointer-events-none">
                                        <!-- Icono SVG: Edificio / Breeder -->
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                </div>
                                <p v-if="form.errors.banco" class="mt-1.5 text-xs font-semibold text-red-500 ml-1">
                                    {{ form.errors.banco }}
                                </p>

                                <!-- Sugerencias rápidas de bancos -->
                                <div class="mt-2 flex flex-wrap gap-1.5 items-center">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase mr-1">Populares:</span>
                                    <button
                                        v-for="b in bancosSugeridos.slice(0, 4)"
                                        :key="b"
                                        type="button"
                                        @click="setBanco(b)"
                                        class="text-[10px] font-semibold bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-600 px-2 py-0.5 rounded-lg border border-slate-200 transition-colors cursor-pointer"
                                    >
                                        {{ b }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- SECCIÓN 2: TIPO DE VARIEDAD GENÉTICA                     -->
                    <!-- ========================================================= -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <!-- Icono SVG: ADN / Linaje -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                                    2. Clasificación Genética <span class="text-red-500">*</span>
                                </h3>
                            </div>
                            <span v-if="tipoSeleccionado" class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-200">
                                {{ tipoSeleccionado.proporcion || tipoSeleccionado.nombre }}
                            </span>
                        </div>

                        <!-- Selector visual interactivo de tipos -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            <div
                                v-for="tipo in tiposVariedad"
                                :key="tipo.id"
                                @click="seleccionarTipo(tipo.id)"
                                class="p-4 rounded-2xl border-2 transition-all cursor-pointer flex flex-col justify-between text-left relative"
                                :class="[
                                    form.tipo_variedad_id === tipo.id
                                        ? 'bg-emerald-50/50 border-emerald-500 shadow-md shadow-emerald-500/10'
                                        : 'bg-slate-50/70 border-slate-200 hover:border-slate-300 hover:bg-slate-50'
                                ]"
                            >
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-xl flex items-center justify-center font-bold text-xs"
                                            :class="form.tipo_variedad_id === tipo.id ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600'"
                                        >
                                            <svg v-if="tipo.categoria === 'SATIVA'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                            <svg v-else-if="tipo.categoria === 'INDICA'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                            <svg v-else-if="tipo.categoria === 'AUTOMATICA'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            <svg v-else-if="tipo.categoria === 'CBD'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                        </div>
                                        <span class="text-xs font-bold text-slate-800">{{ tipo.nombre }}</span>
                                    </div>
                                    <div
                                        class="w-4 h-4 rounded-full border flex items-center justify-center"
                                        :class="form.tipo_variedad_id === tipo.id ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-300 bg-white'"
                                    >
                                        <svg v-if="form.tipo_variedad_id === tipo.id" class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ tipo.descripcion }}
                                </p>
                            </div>
                        </div>

                        <p v-if="form.errors.tipo_variedad_id" class="mt-1 text-xs font-semibold text-red-500 ml-1">
                            {{ form.errors.tipo_variedad_id }}
                        </p>
                    </div>

                    <!-- ========================================================= -->
                    <!-- SECCIÓN 3: DÍAS DE CICLO (DECIMAL:2)                      -->
                    <!-- ========================================================= -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <!-- Icono SVG: Reloj / Calendario -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                                3. Duración del Ciclo de Floración
                            </h3>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200/80">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-center">
                                <div class="md:col-span-6">
                                    <label for="dias_ciclo" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                                        Días Estimados de Ciclo (decimal:2)
                                    </label>
                                    <div class="relative">
                                        <input
                                            id="dias_ciclo"
                                            v-model.number="form.dias_ciclo"
                                            type="number"
                                            step="0.5"
                                            min="0"
                                            max="200"
                                            placeholder="Ej. 65.00"
                                            class="w-full bg-white border rounded-2xl px-4 py-3 text-lg font-bold text-slate-800 outline-none transition-all"
                                            :class="form.errors.dias_ciclo ? 'border-red-400 bg-red-50/30' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500'"
                                        />
                                        <span class="absolute right-4 top-3.5 text-xs font-bold text-slate-400 uppercase">
                                            Días
                                        </span>
                                    </div>
                                    <p v-if="form.errors.dias_ciclo" class="mt-1.5 text-xs font-semibold text-red-500 ml-1">
                                        {{ form.errors.dias_ciclo }}
                                    </p>
                                </div>

                                <!-- Conversor / Semanas Calculadas -->
                                <div class="md:col-span-6 flex flex-col justify-center bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                                        Equivalencia en Semanas
                                    </div>
                                    <div v-if="form.dias_ciclo && form.dias_ciclo > 0" class="flex items-baseline gap-2">
                                        <span class="text-2xl font-extrabold text-emerald-700">
                                            ~{{ semanasCalculadas }}
                                        </span>
                                        <span class="text-xs font-semibold text-slate-500">
                                            semanas de floración ({{ form.dias_ciclo }} días)
                                        </span>
                                    </div>
                                    <div v-else class="text-xs text-slate-400 italic">
                                        Ingresá los días para calcular las semanas estimadas.
                                    </div>
                                </div>
                            </div>

                            <!-- Botones rápidos de días -->
                            <div class="mt-4 pt-3 border-t border-slate-200/60 flex flex-wrap items-center gap-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase mr-1">Presets Comunes:</span>
                                <button
                                    v-for="preset in diasPresets"
                                    :key="preset.dias"
                                    type="button"
                                    @click="setDias(preset.dias)"
                                    class="text-xs font-bold px-3 py-1.5 rounded-xl border transition-all cursor-pointer"
                                    :class="form.dias_ciclo === preset.dias ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:border-emerald-400'"
                                >
                                    {{ preset.label }}
                                    <span class="text-[10px] opacity-75 font-normal ml-1">({{ preset.nota }})</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- SECCIÓN 4: DESCRIPCIÓN & NOTAS BOTÁNICAS                  -->
                    <!-- ========================================================= -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <!-- Icono SVG: Notas / Documento -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                                    4. Descripción y Perfil de la Planta
                                </h3>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-400">
                                {{ form.descripcion?.length || 0 }} caracteres
                            </span>
                        </div>

                        <div>
                            <textarea
                                id="descripcion"
                                v-model="form.descripcion"
                                rows="3"
                                placeholder="Notas de aroma, perfil terpénico (mirceno, limoneno, cariofileno), resistencia a plagas o requerimientos nutricionales..."
                                class="w-full bg-slate-50 border rounded-2xl px-4 py-3 text-sm text-slate-800 outline-none transition-all leading-relaxed"
                                :class="form.errors.descripcion ? 'border-red-400 bg-red-50/30' : 'border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500'"
                            ></textarea>
                            <p v-if="form.errors.descripcion" class="mt-1 text-xs font-semibold text-red-500 ml-1">
                                {{ form.errors.descripcion }}
                            </p>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- ACCIONES DEL FORMULARIO                                  -->
                    <!-- ========================================================= -->
                    <div class="pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                        <button
                            type="button"
                            @click="reiniciar"
                            class="w-full sm:w-auto px-5 py-3 rounded-2xl text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors cursor-pointer"
                        >
                            Limpiar Formulario
                        </button>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white px-8 py-3.5 rounded-2xl font-bold text-sm shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2.5 transition-all cursor-pointer disabled:opacity-50"
                        >
                            <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ form.processing ? 'Guardando en Base de Datos...' : 'Guardar Variedad en Catálogo' }}</span>
                        </button>
                    </div>
                </form>

            </div>
        </main>

        <!-- FOOTER -->
        <footer class="bg-white border-t border-slate-200/80 px-6 py-4 text-center text-xs text-slate-400 shrink-0">
            CultivoOS &bull; Módulo de Catálogo Genético &bull; Laravel Eloquent + Inertia Vue 3
        </footer>
    </div>
</template>
