<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm, Head } from '@inertiajs/vue3'

// ==========================================
// INTERFACES Y TIPOS
// ==========================================
export interface EstadoSala {
    id: string | number
    nombre: string
    color?: string
}

export interface TipoSala {
    id: string | number
    nombre: string
}

export type TipoEquipamiento =
    | 'LUMINARIA'
    | 'AIRE_ACONDICIONADO'
    | 'CO2'
    | 'VENTILADOR'
    | 'HUMIDIFICADOR'
    | 'DESHUMIDIFICADOR'
    | 'RIEGO_AUTOMATICO'

export interface EquipamientoItem {
    id: string
    tipo: TipoEquipamiento
    tipo_equipamiento_id?: string | number
    nombre: string
    marca: string
    modelo: string
    numero_serie: string
    descripcion: string
    especificaciones: Record<string, any>
}

// ==========================================
// PROPS DEL CONTROLADOR LARAVEL
// ==========================================
const props = withDefaults(
    defineProps<{
        estadosSala: EstadoSala[]
        tiposSala: TipoSala[]
        salaIdInicial?: string | number
    }>(),
    {
        estadosSala: () => [],
        tiposSala: () => [],
    }
)

// ==========================================
// ESTADO DE NAVEGACIÓN Y FORMULARIO
// ==========================================
const pasoActual = ref(1)
const maxPasoAlcanzado = ref(1)
const salaId = ref<string>(props.salaIdInicial ? String(props.salaIdInicial) : `SALA-${Date.now().toString().slice(-4)}`)
const finalizado = ref(false)

const pasos = [
    { id: 1, nombre: 'Información de la Sala', subtitulo: 'Datos generales y dimensiones' },
    { id: 2, nombre: 'Equipamiento', subtitulo: 'Luminaria, clima y dispositivos' },
    { id: 3, nombre: 'Resumen / Finalizar', subtitulo: 'Verificación y envío a Laravel' },
]

// Formulario de Sala (Inertia useForm)
const formSala = useForm({
    id: salaId.value,
    nombre: '',
    descripcion: '',
    posee_co2: false,
    posee_extraccion: false,
    posee_intraccion: false,
    posee_ins_agua: false,
    tipo_sala_id: null as string | number | null,
    estado_sala_id: null as string | number | null,
    area: null as number | null,
    altura: null as number | null,
    volumen: null as number | null,
})

// Array temporal de equipamientos (se enviarán todos juntos)
const equipamientos = ref<EquipamientoItem[]>([])

// ==========================================
// SUB-FORMULARIO DE EQUIPAMIENTO
// ==========================================
const editandoId = ref<string | null>(null)
const itemToDelete = ref<EquipamientoItem | null>(null)

const eqForm = ref({
    tipo: 'LUMINARIA' as TipoEquipamiento,
    nombre: '',
    marca: '',
    modelo: '',
    numero_serie: '',
    descripcion: '',
    especificaciones: getDefaultEspecificaciones('LUMINARIA') as Record<string, any>,
})

const eqErrors = ref<{ nombre?: string }>({})

function getDefaultEspecificaciones(tipo: TipoEquipamiento): Record<string, any> {
    switch (tipo) {
        case 'LUMINARIA':
            return {
                potencia: 720,
                tipo_iluminacion: 'LED',
                temperatura_color: 3500,
                espectro: 'Full Spectrum',
                tiene_dimmer: true,
                potencia_maxima: 720,
                area_cobertura: 1.44,
                ppfd_maximo: 1800,
                altura_recomendada: 45,
            }
        case 'AIRE_ACONDICIONADO':
            return {
                capacidad_frigorifica: 4500,
                potencia_electrica: 1350,
                tipo: 'Split',
                tiene_calefaccion: true,
                refrigerante: 'R410A',
            }
        case 'CO2':
            return {
                tipo_sistema: 'Tubo / garrafa',
                capacidad_cilindro: 10,
                tiene_sensor: true,
                tipo_sensor: 'NDIR',
                concentracion_objetivo: 1200,
            }
        case 'VENTILADOR':
            return {
                diametro: 40,
                potencia: 60,
                velocidades: 3,
                es_oscilante: true,
                caudal_aire: 1200,
            }
        case 'HUMIDIFICADOR':
            return {
                capacidad_tanque: 8,
                produccion_humedad: 400,
                potencia: 35,
                tiene_higrostato: true,
            }
        case 'DESHUMIDIFICADOR':
            return {
                capacidad_extraccion: 20,
                potencia: 420,
                tiene_drenaje_continuo: true,
                tiene_higrostato: true,
            }
        case 'RIEGO_AUTOMATICO':
            return {
                tipo_riego: 'Goteo',
                observaciones: 'Módulo hidráulico base configurado',
            }
        default:
            return {}
    }
}

function cambiarTipoEquipamiento(nuevoTipo: TipoEquipamiento) {
    eqForm.value.tipo = nuevoTipo
    eqForm.value.especificaciones = getDefaultEspecificaciones(nuevoTipo)
    eqErrors.value = {}
}

function resetEqForm() {
    editandoId.value = null
    eqForm.value = {
        tipo: 'LUMINARIA',
        nombre: '',
        marca: '',
        modelo: '',
        numero_serie: '',
        descripcion: '',
        especificaciones: getDefaultEspecificaciones('LUMINARIA'),
    }
    eqErrors.value = {}
}

function iniciarEdicion(item: EquipamientoItem) {
    editandoId.value = item.id
    eqForm.value = {
        tipo: item.tipo,
        nombre: item.nombre,
        marca: item.marca,
        modelo: item.modelo,
        numero_serie: item.numero_serie,
        descripcion: item.descripcion,
        especificaciones: JSON.parse(JSON.stringify(item.especificaciones || {})),
    }
}

function agregarOActualizarEquipamiento() {
    eqErrors.value = {}
    if (!eqForm.value.nombre.trim()) {
        eqErrors.value.nombre = 'El nombre o identificador del equipo es obligatorio.'
        return
    }

    if (editandoId.value) {
        const index = equipamientos.value.findIndex((e) => e.id === editandoId.value)
        if (index !== -1) {
            equipamientos.value[index] = {
                id: editandoId.value,
                tipo: eqForm.value.tipo,
                nombre: eqForm.value.nombre.trim(),
                marca: eqForm.value.marca.trim(),
                modelo: eqForm.value.modelo.trim(),
                numero_serie: eqForm.value.numero_serie.trim(),
                descripcion: eqForm.value.descripcion.trim(),
                especificaciones: JSON.parse(JSON.stringify(eqForm.value.especificaciones)),
            }
        }
    } else {
        const nuevo: EquipamientoItem = {
            id: 'eq-' + Date.now().toString().slice(-6),
            tipo: eqForm.value.tipo,
            nombre: eqForm.value.nombre.trim(),
            marca: eqForm.value.marca.trim(),
            modelo: eqForm.value.modelo.trim(),
            numero_serie: eqForm.value.numero_serie.trim(),
            descripcion: eqForm.value.descripcion.trim(),
            especificaciones: JSON.parse(JSON.stringify(eqForm.value.especificaciones)),
        }
        equipamientos.value.push(nuevo)
    }

    resetEqForm()
}

function confirmarEliminar() {
    if (itemToDelete.value) {
        equipamientos.value = equipamientos.value.filter((e) => e.id !== itemToDelete.value?.id)
        if (editandoId.value === itemToDelete.value.id) {
            resetEqForm()
        }
        itemToDelete.value = null
    }
}

function getCaracteristicasResumen(eq: EquipamientoItem): string {
    const s = eq.especificaciones || {}
    switch (eq.tipo) {
        case 'LUMINARIA': {
            const p: string[] = []
            if (s.potencia) p.push(`${s.potencia}W`)
            if (s.tipo_iluminacion) p.push(s.tipo_iluminacion)
            if (s.espectro) p.push(s.espectro)
            if (s.tiene_dimmer) p.push('Dimmer')
            return p.length > 0 ? p.join(' · ') : 'Luminaria configurada'
        }
        case 'AIRE_ACONDICIONADO': {
            const p: string[] = []
            if (s.capacidad_frigorifica) p.push(`${s.capacidad_frigorifica} BTU`)
            if (s.tipo) p.push(s.tipo)
            if (s.tiene_calefaccion) p.push('Frío/Calor')
            return p.length > 0 ? p.join(' · ') : 'A/C configurado'
        }
        case 'CO2': {
            const p: string[] = []
            if (s.concentracion_objetivo) p.push(`${s.concentracion_objetivo} ppm`)
            if (s.tipo_sistema) p.push(s.tipo_sistema)
            if (s.tiene_sensor) p.push('Sensor NDIR')
            return p.length > 0 ? p.join(' · ') : 'Sistema CO2'
        }
        case 'VENTILADOR': {
            const p: string[] = []
            if (s.diametro) p.push(`Ø ${s.diametro}cm`)
            if (s.potencia) p.push(`${s.potencia}W`)
            if (s.es_oscilante) p.push('Oscilante')
            return p.length > 0 ? p.join(' · ') : 'Ventilación'
        }
        case 'HUMIDIFICADOR': {
            const p: string[] = []
            if (s.capacidad_tanque) p.push(`${s.capacidad_tanque} L`)
            if (s.produccion_humedad) p.push(`${s.produccion_humedad} ml/h`)
            return p.length > 0 ? p.join(' · ') : 'Humidificación'
        }
        case 'DESHUMIDIFICADOR': {
            const p: string[] = []
            if (s.capacidad_extraccion) p.push(`${s.capacidad_extraccion} L/día`)
            if (s.tiene_drenaje_continuo) p.push('Drenaje continuo')
            return p.length > 0 ? p.join(' · ') : 'Extracción de humedad'
        }
        case 'RIEGO_AUTOMATICO':
            return s.observaciones || 'Módulo base registrado'
        default:
            return 'Dispositivo configurado'
    }
}

// ==========================================
// VALIDACIONES Y NAVEGACIÓN DE PASOS
// ==========================================
const volumenCalculado = computed(() => {
    if (formSala.area && formSala.altura && formSala.area > 0 && formSala.altura > 0) {
        return Number((formSala.area * formSala.altura).toFixed(2))
    }
    return null
})

function aplicarVolumenCalculado() {
    if (volumenCalculado.value !== null) {
        formSala.volumen = volumenCalculado.value
    }
}

function validarPasoSala(): boolean {
    formSala.clearErrors()
    let valido = true

    if (!formSala.nombre?.trim()) {
        formSala.setError('nombre', 'El nombre de la sala es obligatorio.')
        valido = false
    }
    if (!formSala.estado_sala_id) {
        formSala.setError('estado_sala_id', 'Debes seleccionar un estado.')
        valido = false
    }
    if (!formSala.tipo_sala_id) {
        formSala.setError('tipo_sala_id', 'Debes seleccionar un tipo de sala.')
        valido = false
    }
    if (!formSala.area || formSala.area <= 0) {
        formSala.setError('area', 'Ingresa un área válida mayor a 0 m².')
        valido = false
    }
    if (!formSala.altura || formSala.altura <= 0) {
        formSala.setError('altura', 'Ingresa una altura válida mayor a 0 m.')
        valido = false
    }
    if (!formSala.volumen || formSala.volumen <= 0) {
        formSala.setError('volumen', 'Ingresa o calcula un volumen mayor a 0 m³.')
        valido = false
    }

    return valido
}

function siguiente() {
    if (pasoActual.value === 1) {
        if (!validarPasoSala()) return
    }
    if (pasoActual.value < pasos.length) {
        pasoActual.value++
        maxPasoAlcanzado.value = Math.max(maxPasoAlcanzado.value, pasoActual.value)
    }
}

function anterior() {
    if (pasoActual.value > 1) {
        pasoActual.value--
    }
}

function irAPaso(id: number) {
    if (id > pasoActual.value && pasoActual.value === 1) {
        if (!validarPasoSala()) return
    }
    pasoActual.value = id
    maxPasoAlcanzado.value = Math.max(maxPasoAlcanzado.value, id)
}

// ==========================================
// FINALIZACIÓN Y ENVÍO DEL PAYLOAD LARAVEL
// ==========================================
function finalizarCreacion() {
    if (!validarPasoSala()) {
        pasoActual.value = 1
        return
    }

    // Payload final preparado exactamente como requiere el backend Laravel + PostgreSQL
    const payload = {
        sala_id: salaId.value,
        sala: { ...formSala.data() },
        equipamientos: equipamientos.value.map((eq) => ({
            id: eq.id,
            tipo: eq.tipo,
            nombre: eq.nombre,
            marca: eq.marca,
            modelo: eq.modelo,
            numero_serie: eq.numero_serie,
            descripcion: eq.descripcion,
            especificaciones: eq.especificaciones,
        })),
    }

    console.log('Payload completo preparado para Laravel:', payload)
    finalizado.value = true

    // @ts-ignore (descomentar cuando el endpoint de Laravel esté listo)
    // formSala.transform(() => payload).post(typeof route === 'function' ? route('salas.store') : '/salas')
}

const estadoSeleccionado = computed(() => {
    return props.estadosSala?.find((e) => e.id === formSala.estado_sala_id)
})

const tipoSeleccionado = computed(() => {
    return props.tiposSala?.find((t) => t.id === formSala.tipo_sala_id)
})
</script>

<template>
    <Head title="Crear Sala y Equipamiento - CultivoOS" />

    <div class="min-h-screen bg-slate-50 font-sans flex flex-col justify-between text-slate-900 antialiased">
        <!-- HEADER -->
        <header class="bg-white border-b border-slate-200 px-6 sm:px-8 py-4 flex justify-between items-center shrink-0 sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 text-white shrink-0">
                    <!-- Icono SVG: Hoja / Cultivo -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-slate-800 uppercase">
                    Cultivo<span class="text-emerald-600">OS</span>
                </h1>
            </div>

            <div class="flex items-center gap-4 text-xs sm:text-sm font-medium text-slate-500">
                <span class="hidden sm:inline">Control Panel v2.4</span>
                <span v-if="equipamientos.length > 0" class="bg-emerald-50 text-emerald-700 font-bold px-3 py-1 rounded-full text-xs border border-emerald-200 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ equipamientos.length }} {{ equipamientos.length === 1 ? 'equipo' : 'equipos' }}
                </span>
                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600 text-xs shadow-xs">
                    CO
                </div>
            </div>
        </header>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-1 p-4 sm:p-8 flex flex-col justify-center">
            <div class="max-w-4xl mx-auto w-full">

                <!-- BARRA DE PROGRESO Y TABS -->
                <div class="mb-6">
                    <div class="flex items-center justify-between px-1 mb-2">
                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                            Paso {{ pasoActual }} de {{ pasos.length }}
                        </span>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                            {{ pasoActual === 3 ? 'Listo para enviar' : 'Configuración de Sala y Equipos' }}
                        </span>
                    </div>
                    <div class="h-1.5 w-full bg-slate-200/80 rounded-full overflow-hidden mb-6">
                        <div
                            class="h-full bg-emerald-600 rounded-full transition-all duration-300 ease-out"
                            :style="{ width: `${(pasoActual / pasos.length) * 100}%` }"
                        ></div>
                    </div>

                    <!-- TABS SEGMENTADOS -->
                    <nav class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <button
                            v-for="paso in pasos"
                            :key="paso.id"
                            type="button"
                            @click="irAPaso(paso.id)"
                            class="p-4 rounded-2xl flex items-center gap-3.5 transition-all text-left relative cursor-pointer outline-none border"
                            :class="[
                                pasoActual === paso.id
                                    ? 'bg-white shadow-md shadow-emerald-500/10 border-2 border-emerald-500 scale-[1.01]'
                                    : pasoActual > paso.id || maxPasoAlcanzado >= paso.id
                                    ? 'bg-white/90 border-emerald-200/80 hover:border-emerald-400 text-slate-700 shadow-xs'
                                    : 'bg-white/40 border-slate-200/60 text-slate-400 opacity-60'
                            ]"
                        >
                            <div
                                class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0"
                                :class="[
                                    pasoActual === paso.id
                                        ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-200'
                                        : pasoActual > paso.id
                                        ? 'bg-emerald-100 text-emerald-800'
                                        : 'bg-slate-100 text-slate-500'
                                ]"
                            >
                                <svg v-if="pasoActual > paso.id" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span v-else>0{{ paso.id }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider block" :class="pasoActual === paso.id ? 'text-emerald-600' : 'text-slate-400'">
                                    Etapa 0{{ paso.id }}
                                </span>
                                <span class="text-sm font-bold tracking-tight block truncate" :class="pasoActual === paso.id ? 'text-slate-800' : 'text-slate-600'">
                                    {{ paso.nombre }}
                                </span>
                            </div>
                        </button>
                    </nav>
                </div>

                <!-- CARD PRINCIPAL -->
                <main class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-6 sm:p-10 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-600"></div>

                    <!-- ========================================== -->
                    <!-- PASO 1: INFORMACIÓN DE LA SALA             -->
                    <!-- ========================================== -->
                    <div v-if="pasoActual === 1" class="space-y-6">
                        <div class="border-b border-slate-100 pb-5 flex items-start justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                                    <svg class="w-6 h-6 text-emerald-600 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Información de la Sala
                                </h3>
                                <p class="text-slate-500 text-sm mt-1">
                                    Define la identificación, cubicaje y características ambientales de la nueva sala.
                                </p>
                            </div>
                            <span class="font-mono text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                ID: {{ salaId }}
                            </span>
                        </div>

                        <!-- 1. Identificación -->
                        <div class="space-y-4">
                            <div>
                                <label for="nombre" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">
                                    Nombre de la Sala <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="nombre"
                                    v-model="formSala.nombre"
                                    type="text"
                                    placeholder="Ej. SALA FLORACIÓN 1"
                                    class="w-full bg-slate-50 border rounded-2xl px-5 py-3.5 text-slate-800 font-medium outline-none transition-all"
                                    :class="formSala.errors.nombre ? 'border-red-400 bg-red-50/30' : 'border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500'"
                                />
                                <p v-if="formSala.errors.nombre" class="mt-2 text-xs font-semibold text-red-500 ml-1">
                                    {{ formSala.errors.nombre }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">
                                        Estado de la Sala <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        v-model="formSala.estado_sala_id"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-800 font-medium outline-none cursor-pointer focus:bg-white"
                                    >
                                        <option :value="null" disabled>Seleccione un estado</option>
                                        <option v-for="estado in estadosSala" :key="estado.id" :value="estado.id">
                                            {{ estado.nombre }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">
                                        Tipo de Sala <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        v-model="formSala.tipo_sala_id"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-800 font-medium outline-none cursor-pointer focus:bg-white"
                                    >
                                        <option :value="null" disabled>Seleccione un Tipo</option>
                                        <option v-for="tipo in tiposSala" :key="tipo.id" :value="tipo.id">
                                            {{ tipo.nombre }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Dimensiones -->
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                            <h4 class="text-xs font-bold text-slate-400 uppercase mb-4 tracking-widest flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                Métricas de Espacio
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1 ml-1">Área (m²)</label>
                                    <input
                                        v-model.number="formSala.area"
                                        type="number"
                                        step="0.1"
                                        placeholder="0.0"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-base font-bold text-slate-800 outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1 ml-1">Altura (m)</label>
                                    <input
                                        v-model.number="formSala.altura"
                                        type="number"
                                        step="0.05"
                                        placeholder="0.0"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-base font-bold text-slate-800 outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1 ml-1">Volumen (m³)</label>
                                    <input
                                        v-model.number="formSala.volumen"
                                        type="number"
                                        step="0.1"
                                        placeholder="0.0"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-base font-bold text-slate-800 outline-none"
                                    />
                                </div>
                            </div>
                            <div v-if="volumenCalculado && formSala.volumen !== volumenCalculado" class="mt-3 flex items-center justify-between text-xs text-emerald-800">
                                <span>Cálculo: {{ formSala.area }} m² × {{ formSala.altura }} m = {{ volumenCalculado }} m³</span>
                                <button type="button" @click="aplicarVolumenCalculado" class="font-bold underline cursor-pointer">
                                    Usar {{ volumenCalculado }} m³
                                </button>
                            </div>
                        </div>

                        <!-- 3. Instalaciones Base -->
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">Servicios Ambientales Base</span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <label class="p-3.5 rounded-xl border flex items-center justify-between cursor-pointer transition-all" :class="formSala.posee_co2 ? 'bg-emerald-50 border-emerald-400' : 'bg-slate-50 border-slate-200'">
                                    <span class="text-xs font-bold">Posee CO₂</span>
                                    <input type="checkbox" v-model="formSala.posee_co2" class="accent-emerald-600" />
                                </label>
                                <label class="p-3.5 rounded-xl border flex items-center justify-between cursor-pointer transition-all" :class="formSala.posee_intraccion ? 'bg-emerald-50 border-emerald-400' : 'bg-slate-50 border-slate-200'">
                                    <span class="text-xs font-bold">Intracción</span>
                                    <input type="checkbox" v-model="formSala.posee_intraccion" class="accent-emerald-600" />
                                </label>
                                <label class="p-3.5 rounded-xl border flex items-center justify-between cursor-pointer transition-all" :class="formSala.posee_extraccion ? 'bg-emerald-50 border-emerald-400' : 'bg-slate-50 border-slate-200'">
                                    <span class="text-xs font-bold">Extracción</span>
                                    <input type="checkbox" v-model="formSala.posee_extraccion" class="accent-emerald-600" />
                                </label>
                                <label class="p-3.5 rounded-xl border flex items-center justify-between cursor-pointer transition-all" :class="formSala.posee_ins_agua ? 'bg-emerald-50 border-emerald-400' : 'bg-slate-50 border-slate-200'">
                                    <span class="text-xs font-bold">Inst. Agua</span>
                                    <input type="checkbox" v-model="formSala.posee_ins_agua" class="accent-emerald-600" />
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- PASO 2: EQUIPAMIENTO DE LA SALA            -->
                    <!-- ========================================== -->
                    <div v-if="pasoActual === 2" class="space-y-6">
                        <div class="border-b border-slate-100 pb-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                                    <svg class="w-6 h-6 text-emerald-600 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Equipamiento de la sala
                                </h3>
                                <p class="text-slate-500 text-sm mt-1">
                                    Agregá los equipos que actualmente forman parte de esta sala.
                                </p>
                            </div>
                            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">
                                Sala: {{ formSala.nombre || 'Sin nombre' }} ({{ salaId }})
                            </span>
                        </div>

                        <!-- SUB-FORMULARIO DINÁMICO -->
                        <div class="bg-slate-50/90 border border-slate-200 rounded-3xl p-6 shadow-xs">
                            <h4 class="font-bold text-xs uppercase tracking-wider text-slate-600 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ editandoId ? 'Editar Equipamiento Seleccionado' : 'Agregar Nuevo Equipamiento' }}
                            </h4>

                            <!-- Tipo de Equipamiento con ICONOS SVG INTEGRADOS -->
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                                    Tipo de Equipamiento *
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2">
                                    <!-- 1. LUMINARIA -->
                                    <button
                                        type="button"
                                        @click="cambiarTipoEquipamiento('LUMINARIA')"
                                        class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1.5 transition-all cursor-pointer"
                                        :class="eqForm.tipo === 'LUMINARIA' ? 'bg-amber-500 text-white border-amber-600 shadow-md scale-[1.02]' : 'bg-white border-slate-200 text-slate-700 hover:border-amber-400'"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                        <span class="text-[11px] font-bold">Luminaria</span>
                                    </button>

                                    <!-- 2. AIRE ACONDICIONADO -->
                                    <button
                                        type="button"
                                        @click="cambiarTipoEquipamiento('AIRE_ACONDICIONADO')"
                                        class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1.5 transition-all cursor-pointer"
                                        :class="eqForm.tipo === 'AIRE_ACONDICIONADO' ? 'bg-blue-600 text-white border-blue-700 shadow-md scale-[1.02]' : 'bg-white border-slate-200 text-slate-700 hover:border-blue-400'"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m0-9H3m18 0h-9m6.364-6.364l-12.728 12.728m0-12.728l12.728 12.728"/></svg>
                                        <span class="text-[11px] font-bold">Aire Acond.</span>
                                    </button>

                                    <!-- 3. CO2 -->
                                    <button
                                        type="button"
                                        @click="cambiarTipoEquipamiento('CO2')"
                                        class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1.5 transition-all cursor-pointer"
                                        :class="eqForm.tipo === 'CO2' ? 'bg-orange-500 text-white border-orange-600 shadow-md scale-[1.02]' : 'bg-white border-slate-200 text-slate-700 hover:border-orange-400'"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/></svg>
                                        <span class="text-[11px] font-bold">CO₂</span>
                                    </button>

                                    <!-- 4. VENTILADOR -->
                                    <button
                                        type="button"
                                        @click="cambiarTipoEquipamiento('VENTILADOR')"
                                        class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1.5 transition-all cursor-pointer"
                                        :class="eqForm.tipo === 'VENTILADOR' ? 'bg-teal-600 text-white border-teal-700 shadow-md scale-[1.02]' : 'bg-white border-slate-200 text-slate-700 hover:border-teal-400'"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        <span class="text-[11px] font-bold">Ventilador</span>
                                    </button>

                                    <!-- 5. HUMIDIFICADOR -->
                                    <button
                                        type="button"
                                        @click="cambiarTipoEquipamiento('HUMIDIFICADOR')"
                                        class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1.5 transition-all cursor-pointer"
                                        :class="eqForm.tipo === 'HUMIDIFICADOR' ? 'bg-cyan-600 text-white border-cyan-700 shadow-md scale-[1.02]' : 'bg-white border-slate-200 text-slate-700 hover:border-cyan-400'"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                        <span class="text-[11px] font-bold">Humidific.</span>
                                    </button>

                                    <!-- 6. DESHUMIDIFICADOR -->
                                    <button
                                        type="button"
                                        @click="cambiarTipoEquipamiento('DESHUMIDIFICADOR')"
                                        class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1.5 transition-all cursor-pointer"
                                        :class="eqForm.tipo === 'DESHUMIDIFICADOR' ? 'bg-indigo-600 text-white border-indigo-700 shadow-md scale-[1.02]' : 'bg-white border-slate-200 text-slate-700 hover:border-indigo-400'"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                        <span class="text-[11px] font-bold">Deshumid.</span>
                                    </button>

                                    <!-- 7. RIEGO AUTOMATICO -->
                                    <button
                                        type="button"
                                        @click="cambiarTipoEquipamiento('RIEGO_AUTOMATICO')"
                                        class="p-2.5 rounded-xl border flex flex-col items-center justify-center gap-1.5 transition-all cursor-pointer"
                                        :class="eqForm.tipo === 'RIEGO_AUTOMATICO' ? 'bg-emerald-600 text-white border-emerald-700 shadow-md scale-[1.02]' : 'bg-white border-slate-200 text-slate-700 hover:border-emerald-400'"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        <span class="text-[11px] font-bold">Riego Aut.</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Campos Comunes -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Nombre / Identificador *</label>
                                    <input
                                        v-model="eqForm.nombre"
                                        type="text"
                                        placeholder="Ej. LED Principal Canopia 1"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-bold text-slate-800 outline-none focus:border-emerald-500"
                                    />
                                    <p v-if="eqErrors.nombre" class="text-[10px] text-red-500 font-bold mt-1">{{ eqErrors.nombre }}</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Marca</label>
                                    <input
                                        v-model="eqForm.marca"
                                        type="text"
                                        placeholder="Ej. Lucius / Philco"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Modelo</label>
                                    <input
                                        v-model="eqForm.modelo"
                                        type="text"
                                        placeholder="Ej. EVO 720W"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 outline-none"
                                    />
                                </div>
                            </div>

                            <!-- Campos Específicos Dinámicos -->
                            <div class="bg-white p-4 rounded-2xl border border-slate-200 mb-4 shadow-xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-3">
                                    Especificaciones técnicas de {{ eqForm.tipo }}
                                </span>

                                <!-- LUMINARIA -->
                                <div v-if="eqForm.tipo === 'LUMINARIA'" class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Potencia (W)</label>
                                        <input v-model.number="eqForm.especificaciones.potencia" type="number" class="w-full bg-slate-50 border rounded-lg p-2 font-bold" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tipo Luz</label>
                                        <select v-model="eqForm.especificaciones.tipo_iluminacion" class="w-full bg-slate-50 border rounded-lg p-2">
                                            <option value="LED">LED</option>
                                            <option value="HPS / Sodio">HPS / Sodio</option>
                                            <option value="MH">MH</option>
                                            <option value="CFL">CFL</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Espectro</label>
                                        <select v-model="eqForm.especificaciones.espectro" class="w-full bg-slate-50 border rounded-lg p-2">
                                            <option value="Full Spectrum">Full Spectrum</option>
                                            <option value="Floración">Floración</option>
                                            <option value="Crecimiento">Crecimiento</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center pt-4">
                                        <label class="font-bold text-xs flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" v-model="eqForm.especificaciones.tiene_dimmer" class="accent-emerald-600" />
                                            ¿Tiene Dimmer?
                                        </label>
                                    </div>
                                </div>

                                <!-- AIRE ACONDICIONADO -->
                                <div v-if="eqForm.tipo === 'AIRE_ACONDICIONADO'" class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Capacidad (BTU)</label>
                                        <input v-model.number="eqForm.especificaciones.capacidad_frigorifica" type="number" class="w-full bg-slate-50 border rounded-lg p-2 font-bold" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tipo Unidad</label>
                                        <select v-model="eqForm.especificaciones.tipo" class="w-full bg-slate-50 border rounded-lg p-2">
                                            <option value="Split">Split</option>
                                            <option value="Portátil">Portátil</option>
                                            <option value="Ventana">Ventana</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center pt-4">
                                        <label class="font-bold text-xs flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" v-model="eqForm.especificaciones.tiene_calefaccion" class="accent-emerald-600" />
                                            ¿Frío / Calor?
                                        </label>
                                    </div>
                                </div>

                                <!-- VENTILADOR -->
                                <div v-if="eqForm.tipo === 'VENTILADOR'" class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Diámetro (cm)</label>
                                        <input v-model.number="eqForm.especificaciones.diametro" type="number" class="w-full bg-slate-50 border rounded-lg p-2 font-bold" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Potencia (W)</label>
                                        <input v-model.number="eqForm.especificaciones.potencia" type="number" class="w-full bg-slate-50 border rounded-lg p-2 font-bold" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Velocidades</label>
                                        <input v-model.number="eqForm.especificaciones.velocidades" type="number" class="w-full bg-slate-50 border rounded-lg p-2" />
                                    </div>
                                    <div class="flex items-center pt-4">
                                        <label class="font-bold text-xs flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" v-model="eqForm.especificaciones.es_oscilante" class="accent-emerald-600" />
                                            ¿Oscilante?
                                        </label>
                                    </div>
                                </div>

                                <!-- CO2 -->
                                <div v-if="eqForm.tipo === 'CO2'" class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Sistema</label>
                                        <select v-model="eqForm.especificaciones.tipo_sistema" class="w-full bg-slate-50 border rounded-lg p-2">
                                            <option value="Tubo / garrafa">Tubo / garrafa</option>
                                            <option value="Generador">Generador</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Objetivo (ppm)</label>
                                        <input v-model.number="eqForm.especificaciones.concentracion_objetivo" type="number" class="w-full bg-slate-50 border rounded-lg p-2 font-bold" />
                                    </div>
                                    <div class="flex items-center pt-4">
                                        <label class="font-bold text-xs flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" v-model="eqForm.especificaciones.tiene_sensor" class="accent-emerald-600" />
                                            ¿Tiene sensor NDIR?
                                        </label>
                                    </div>
                                </div>

                                <!-- HUMIDIFICADOR / DESHUMIDIFICADOR -->
                                <div v-if="eqForm.tipo === 'HUMIDIFICADOR' || eqForm.tipo === 'DESHUMIDIFICADOR'" class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Capacidad Tanque (L)</label>
                                        <input v-model.number="eqForm.especificaciones.capacidad_tanque" type="number" class="w-full bg-slate-50 border rounded-lg p-2 font-bold" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Potencia (W)</label>
                                        <input v-model.number="eqForm.especificaciones.potencia" type="number" class="w-full bg-slate-50 border rounded-lg p-2" />
                                    </div>
                                    <div class="flex items-center pt-4">
                                        <label class="font-bold text-xs flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" v-model="eqForm.especificaciones.tiene_higrostato" class="accent-emerald-600" />
                                            ¿Higrostato?
                                        </label>
                                    </div>
                                </div>

                                <!-- RIEGO AUTOMÁTICO -->
                                <div v-if="eqForm.tipo === 'RIEGO_AUTOMATICO'" class="text-xs text-teal-800 bg-teal-50 p-3 rounded-lg border border-teal-200 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Nota: Los parámetros de electroválvulas y sectores se configurarán en el módulo hidráulico avanzado.</span>
                                </div>
                            </div>

                            <!-- Botón Agregar / Guardar -->
                            <div class="flex justify-end gap-2">
                                <button v-if="editandoId" type="button" @click="resetEqForm" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">
                                    Cancelar
                                </button>
                                <button type="button" @click="agregarOActualizarEquipamiento" class="px-6 py-2.5 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition cursor-pointer flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ editandoId ? 'Actualizar Equipamiento' : 'Agregar Equipamiento' }}
                                </button>
                            </div>
                        </div>

                        <!-- LISTADO / TABLA DE EQUIPAMIENTOS -->
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Equipamientos Agregados ({{ equipamientos.length }})
                            </h4>

                            <div v-if="equipamientos.length === 0" class="p-8 text-center border-2 border-dashed border-slate-200 rounded-2xl text-xs text-slate-400 flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                No hay equipamientos agregados todavía a esta sala.
                            </div>

                            <div v-else class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase">
                                        <tr>
                                            <th class="p-3.5">Tipo</th>
                                            <th class="p-3.5">Nombre</th>
                                            <th class="p-3.5">Marca / Modelo</th>
                                            <th class="p-3.5">Características</th>
                                            <th class="p-3.5 text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <tr v-for="eq in equipamientos" :key="eq.id" class="hover:bg-slate-50/80 transition-colors">
                                            <td class="p-3.5 font-bold text-emerald-800">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-[11px]">
                                                    {{ eq.tipo }}
                                                </span>
                                            </td>
                                            <td class="p-3.5 font-bold text-slate-900">{{ eq.nombre }}</td>
                                            <td class="p-3.5 text-slate-600">{{ eq.marca || '—' }} {{ eq.modelo }}</td>
                                            <td class="p-3.5 text-slate-700 font-medium">{{ getCaracteristicasResumen(eq) }}</td>
                                            <td class="p-3.5 text-right whitespace-nowrap">
                                                <button
                                                    type="button"
                                                    @click="iniciarEdicion(eq)"
                                                    class="inline-flex items-center gap-1 text-emerald-700 hover:text-emerald-900 mr-3 font-bold cursor-pointer hover:underline"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    Editar
                                                </button>
                                                <button
                                                    type="button"
                                                    @click="itemToDelete = eq"
                                                    class="inline-flex items-center gap-1 text-red-500 hover:text-red-700 font-bold cursor-pointer hover:underline"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- PASO 3: RESUMEN / FINALIZAR                -->
                    <!-- ========================================== -->
                    <div v-if="pasoActual === 3" class="space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                                <svg class="w-6 h-6 text-emerald-600 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Configuración de la sala
                            </h3>
                            <p class="text-slate-500 text-sm mt-1">
                                Revisa los datos antes de finalizar la creación y enviar el payload a Laravel.
                            </p>
                        </div>

                        <!-- Card Sala -->
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200">
                                <span class="text-xs font-bold text-slate-800 uppercase flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                                    Sala: {{ formSala.nombre }}
                                </span>
                                <button type="button" @click="irAPaso(1)" class="text-xs font-bold uppercase text-emerald-700 hover:underline cursor-pointer flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Editar Sala
                                </button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                                <div><span class="text-slate-400 block font-medium">Estado:</span> <strong>{{ estadoSeleccionado?.nombre }}</strong></div>
                                <div><span class="text-slate-400 block font-medium">Tipo:</span> <strong>{{ tipoSeleccionado?.nombre }}</strong></div>
                                <div><span class="text-slate-400 block font-medium">Métricas:</span> <strong>{{ formSala.area }} m² · {{ formSala.volumen }} m³</strong></div>
                            </div>
                        </div>

                        <!-- Card Equipamientos -->
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6">
                            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200">
                                <span class="text-xs font-bold text-slate-800 uppercase flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Equipamiento: {{ equipamientos.length }} {{ equipamientos.length === 1 ? 'equipo configurado' : 'equipos configurados' }}
                                </span>
                                <button type="button" @click="irAPaso(2)" class="text-xs font-bold uppercase text-emerald-700 hover:underline cursor-pointer flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Editar Equipamiento
                                </button>
                            </div>

                            <div v-if="equipamientos.length === 0" class="text-xs text-slate-400">
                                Sin equipamientos registrados.
                            </div>
                            <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div v-for="eq in equipamientos" :key="eq.id" class="p-3 bg-white border border-slate-200 rounded-xl text-xs flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-slate-800">{{ eq.nombre }} ({{ eq.tipo }})</div>
                                        <div class="text-slate-500">{{ getCaracteristicasResumen(eq) }}</div>
                                    </div>
                                    <span class="text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Éxito / Confirmación -->
                        <div v-if="finalizado" class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-900 flex items-center gap-3">
                            <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <strong>¡Listo!</strong> Estructura enviada al backend Laravel con <code>sala_id: {{ salaId }}</code> y {{ equipamientos.length }} equipamientos.
                            </div>
                        </div>
                    </div>

                    <!-- MODAL CONFIRMACIÓN ELIMINAR -->
                    <div v-if="itemToDelete" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl space-y-4">
                            <h4 class="font-bold text-slate-800 text-base">¿Eliminar equipamiento?</h4>
                            <p class="text-xs text-slate-500">¿Estás seguro de quitar "{{ itemToDelete.nombre }}"?</p>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" @click="itemToDelete = null" class="px-4 py-2 bg-slate-100 rounded-xl text-xs font-bold hover:bg-slate-200 cursor-pointer">Cancelar</button>
                                <button type="button" @click="confirmarEliminar" class="px-4 py-2 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 cursor-pointer">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </main>

                <!-- FOOTER DE NAVEGACIÓN -->
                <footer class="mt-8 flex flex-wrap gap-4 justify-between items-center">
                    <button
                        type="button"
                        @click="formSala.reset(); equipamientos = []"
                        class="px-6 py-3 rounded-2xl text-slate-400 font-bold uppercase tracking-widest text-xs hover:bg-slate-100 hover:text-slate-600 transition-all border border-transparent cursor-pointer"
                    >
                        Limpiar todo
                    </button>

                    <div class="flex items-center gap-3">
                        <button
                            v-if="pasoActual > 1"
                            type="button"
                            @click="anterior"
                            class="px-8 sm:px-10 py-3 sm:py-3.5 rounded-2xl bg-white border border-slate-200 text-slate-600 font-bold uppercase tracking-widest text-xs shadow-sm hover:bg-slate-50 transition-all cursor-pointer flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Anterior
                        </button>

                        <button
                            v-if="pasoActual < pasos.length"
                            type="button"
                            @click="siguiente"
                            class="px-8 sm:px-10 py-3.5 sm:py-4 rounded-2xl bg-slate-900 text-white font-bold uppercase tracking-widest text-xs shadow-xl shadow-slate-300 hover:bg-emerald-600 hover:shadow-emerald-200 transition-all cursor-pointer flex items-center gap-2"
                        >
                            Continuar
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>

                        <button
                            v-else
                            type="button"
                            @click="finalizarCreacion"
                            class="px-8 sm:px-10 py-3.5 sm:py-4 rounded-2xl bg-emerald-600 text-white font-bold uppercase tracking-widest text-xs shadow-xl shadow-emerald-200 hover:bg-emerald-700 transition-all cursor-pointer flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Finalizar Creación
                        </button>
                    </div>
                </footer>

            </div>
        </div>
    </div>
</template>
