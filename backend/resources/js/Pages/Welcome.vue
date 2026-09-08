<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();

// Estado reactivo para el simulador interactivo de la landing
const pestanaActiva = ref<'telemetria' | 'riego' | 'salas' | 'trazabilidad'>('telemetria');
const faqAbierto = ref<number | null>(null);

const toggleFaq = (idx: number) => {
    faqAbierto.value = faqAbierto.value === idx ? null : idx;
};
</script>

<template>
    <Head title="CultivoOS - Sistema Operativo Agronómico de Precisión & Telemetría IoT" />

    <div class="min-h-screen bg-slate-900 text-slate-100 selection:bg-emerald-500 selection:text-white font-sans antialiased overflow-x-hidden">
        <!-- Gradientes de fondo tecnológicos y orgánicos -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[850px] h-[550px] bg-emerald-500/10 rounded-full blur-[140px]"></div>
            <div class="absolute top-1/3 -left-40 w-[600px] h-[600px] bg-teal-500/10 rounded-full blur-[160px]"></div>
            <div class="absolute bottom-10 -right-40 w-[700px] h-[700px] bg-cyan-500/10 rounded-full blur-[160px]"></div>
            <!-- Cuadrícula sutil de precisión -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-30"></div>
        </div>

        <!-- BARRA DE NAVEGACIÓN PRINCIPAL -->
        <header class="relative z-50 border-b border-slate-800/80 bg-slate-900/80 backdrop-blur-xl sticky top-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <!-- Marca CultivoOS -->
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 p-0.5 shadow-lg shadow-emerald-500/20">
                        <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 20h10M10 20c5.5-2.5.8-6.4 3-10M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8zM14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.1-4 1-4.9 2z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xl font-black tracking-tight text-white">cultivo</span>
                            <span class="text-xl font-black tracking-tight text-emerald-400">OS</span>
                            <span class="text-[9px] font-black uppercase tracking-wider bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-1.5 py-0.5 rounded-md">
                                Enterprise IoT
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium tracking-wide">
                            
                        </p>
                    </div>
                </div>

                <!-- Enlaces de navegación desktop -->
                <nav class="hidden md:flex items-center gap-8 text-xs font-bold uppercase tracking-wider text-slate-300">
                    <a href="#salas" class="hover:text-emerald-400 transition-colors">Salas Climatizadas</a>
                    <a href="#riego" class="hover:text-emerald-400 transition-colors">Gestión Hídrica & VWC</a>
                    <a href="#telemetria" class="hover:text-emerald-400 transition-colors">Telemetría 24/7</a>
                    <a href="#roi" class="hover:text-emerald-400 transition-colors">Rentabilidad</a>
                </nav>

                <!-- Acciones de Acceso y Registro (Rutas de Laravel/Inertia) -->
                <div v-if="canLogin" class="flex items-center gap-3">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-black px-4 py-2.5 rounded-2xl shadow-lg shadow-emerald-500/20 transition-all cursor-pointer"
                    >
                        <span>Panel de Control</span>
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-xs font-bold text-slate-300 hover:text-white px-3 py-2 rounded-xl hover:bg-slate-800 transition-all"
                        >
                            Iniciar Sesión
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="inline-flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-400 active:bg-emerald-600 text-slate-950 text-xs font-black px-4 py-2.5 rounded-2xl shadow-lg shadow-emerald-500/25 transition-all cursor-pointer"
                        >
                            <span>Comenzar Prueba</span>
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- SECCIÓN 1: HERO PRINCIPAL DE ALTO IMPACTO -->
        <section class="relative z-10 pt-16 sm:pt-24 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                <!-- Badge de Estado Agronómico -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>El Estándar Industrial para Cultivo Indoor & R&D</span>
                </div>

                <!-- Título Primario -->
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-[1.1]">
                    Domina tus <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400">Cultivos</span>.<br />
                    Maximiza tus rendimientos.
                </h1>

                <!-- Subtítulo comercial -->
                <p class="text-base sm:text-lg text-slate-300 font-normal leading-relaxed max-w-2xl mx-auto">
                    CultivoOS unifica el control ambiental en tiempo real (VPD, CO₂, PAR), gestión hídrica de sustrato/RDWC por pulsos de drenaje, trazabilidad genética por lotes y aislamiento multi-organización en una sola plataforma.
                </p>

                <!-- Botones de Acción Primaria -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-sm font-black px-7 py-3.5 rounded-2xl shadow-xl shadow-emerald-500/30 hover:scale-[1.02] transition-all cursor-pointer"
                    >
                        <span>Crear Cuenta de Producción</span>
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </Link>

                    <Link
                        v-if="canLogin"
                        :href="route('login')"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-slate-200 text-sm font-bold px-6 py-3.5 rounded-2xl transition-all cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        <span>Ingresar a mi Organización</span>
                    </Link>
                </div>

                <!-- Métricas Rápidas de Confianza -->
                <div class="pt-8 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl mx-auto border-t border-slate-800/80">
                    <div>
                        <div class="text-2xl font-black text-white">+28%</div>
                        <div class="text-[11px] text-slate-400 font-semibold uppercase">Rendimiento seco / m²</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-emerald-400">-35%</div>
                        <div class="text-[11px] text-slate-400 font-semibold uppercase">Ahorro en Fertirriego</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-white">0.02</div>
                        <div class="text-[11px] text-slate-400 font-semibold uppercase">Precisión VPD (kPa)</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-teal-300">100%</div>
                        <div class="text-[11px] text-slate-400 font-semibold uppercase">Trazabilidad de Lote</div>
                    </div>
                </div>
            </div>

            <!-- SIMULADOR INTERACTIVO DEL DASHBOARD AGRONÓMICO -->
            <div class="mt-16 relative mx-auto max-w-5xl rounded-3xl p-1 bg-gradient-to-b from-slate-700/60 via-slate-800/40 to-slate-900/80 shadow-2xl shadow-emerald-950/40">
                <div class="bg-slate-950/90 rounded-[22px] p-4 sm:p-6 border border-slate-800 space-y-6">
                    <!-- Barra de herramientas del simulador -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800/80 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-rose-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500/80"></div>
                            </div>
                            <span class="text-xs font-mono text-slate-400">
                                Sede Norte &bull; Sala Épsilon [Floración #3] &bull; 48x LED Samsung LM301H
                            </span>
                        </div>

                        <!-- Selector de Módulos Demostrativos -->
                        <div class="flex items-center gap-1 bg-slate-900 p-1 rounded-xl border border-slate-800 text-xs font-bold">
                            <button
                                type="button"
                                @click="pestanaActiva = 'telemetria'"
                                :class="pestanaActiva === 'telemetria' ? 'bg-emerald-500 text-slate-950' : 'text-slate-400 hover:text-white'"
                                class="px-3 py-1.5 rounded-lg transition-all cursor-pointer"
                            >
                                Clima & VPD
                            </button>
                            <button
                                type="button"
                                @click="pestanaActiva = 'riego'"
                                :class="pestanaActiva === 'riego' ? 'bg-emerald-500 text-slate-950' : 'text-slate-400 hover:text-white'"
                                class="px-3 py-1.5 rounded-lg transition-all cursor-pointer"
                            >
                                Riego & VWC
                            </button>
                            <button
                                type="button"
                                @click="pestanaActiva = 'salas'"
                                :class="pestanaActiva === 'salas' ? 'bg-emerald-500 text-slate-950' : 'text-slate-400 hover:text-white'"
                                class="px-3 py-1.5 rounded-lg transition-all cursor-pointer"
                            >
                                Arquitectura Sala
                            </button>
                            <button
                                type="button"
                                @click="pestanaActiva = 'trazabilidad'"
                                :class="pestanaActiva === 'trazabilidad' ? 'bg-emerald-500 text-slate-950' : 'text-slate-400 hover:text-white'"
                                class="px-3 py-1.5 rounded-lg transition-all cursor-pointer"
                            >
                                Genéticas & Lotes
                            </button>
                        </div>
                    </div>

                    <!-- Vista 1: Clima & VPD -->
                    <div v-if="pestanaActiva === 'telemetria'" class="space-y-4">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="bg-slate-900/90 border border-slate-800 p-3.5 rounded-2xl">
                                <span class="text-[10px] text-slate-400 font-bold uppercase block">Temperatura Sala</span>
                                <div class="text-2xl font-black text-emerald-400 mt-0.5">24.5 °C</div>
                                <span class="text-[10px] text-emerald-500 font-mono">Setpoint: 24.0° - 25.0°</span>
                            </div>
                            <div class="bg-slate-900/90 border border-slate-800 p-3.5 rounded-2xl">
                                <span class="text-[10px] text-slate-400 font-bold uppercase block">Humedad Relativa</span>
                                <div class="text-2xl font-black text-blue-400 mt-0.5">48.2 %</div>
                                <span class="text-[10px] text-blue-400 font-mono">Quest 225 Pints ON</span>
                            </div>
                            <div class="bg-slate-900/90 border border-slate-800 p-3.5 rounded-2xl">
                                <span class="text-[10px] text-slate-400 font-bold uppercase block">VPD Transpiración</span>
                                <div class="text-2xl font-black text-amber-400 mt-0.5">1.28 kPa</div>
                                <span class="text-[10px] text-amber-400 font-mono">Zona Floración Óptima</span>
                            </div>
                            <div class="bg-slate-900/90 border border-slate-800 p-3.5 rounded-2xl">
                                <span class="text-[10px] text-slate-400 font-bold uppercase block">Inyección CO₂</span>
                                <div class="text-2xl font-black text-purple-400 mt-0.5">1,180 ppm</div>
                                <span class="text-[10px] text-purple-400 font-mono">Electroválvula Activa</span>
                            </div>
                        </div>

                        <!-- Mini gráfico telemetría simulada -->
                        <div class="p-4 rounded-2xl bg-slate-900/70 border border-slate-800">
                            <div class="flex items-center justify-between text-xs mb-3">
                                <span class="font-bold text-slate-300">Comportamiento Microclimático 24 Horas</span>
                                <span class="text-emerald-400 font-mono text-[11px] flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    En Línea (Frecuencia 30s)
                                </span>
                            </div>
                            <div class="h-28 w-full flex items-end gap-1.5 pt-4 px-2">
                                <div class="flex-1 bg-emerald-500/20 hover:bg-emerald-500/40 rounded-t h-[65%] transition-all"></div>
                                <div class="flex-1 bg-emerald-500/20 hover:bg-emerald-500/40 rounded-t h-[70%] transition-all"></div>
                                <div class="flex-1 bg-emerald-500/30 hover:bg-emerald-500/50 rounded-t h-[78%] transition-all"></div>
                                <div class="flex-1 bg-emerald-500/40 hover:bg-emerald-500/60 rounded-t h-[82%] transition-all"></div>
                                <div class="flex-1 bg-emerald-500/60 hover:bg-emerald-500/80 rounded-t h-[94%] transition-all"></div>
                                <div class="flex-1 bg-emerald-500/70 hover:bg-emerald-500/90 rounded-t h-[88%] transition-all"></div>
                                <div class="flex-1 bg-emerald-500/60 hover:bg-emerald-500/80 rounded-t h-[84%] transition-all"></div>
                                <div class="flex-1 bg-emerald-500/50 hover:bg-emerald-500/70 rounded-t h-[76%] transition-all"></div>
                                <div class="flex-1 bg-emerald-500/30 hover:bg-emerald-500/50 rounded-t h-[68%] transition-all"></div>
                                <div class="flex-1 bg-emerald-500/20 hover:bg-emerald-500/40 rounded-t h-[64%] transition-all"></div>
                                <div class="flex-1 bg-emerald-500/20 hover:bg-emerald-500/40 rounded-t h-[60%] transition-all"></div>
                                <div class="flex-1 bg-emerald-500/20 hover:bg-emerald-500/40 rounded-t h-[58%] transition-all"></div>
                            </div>
                            <div class="flex justify-between text-[10px] text-slate-500 pt-2 font-mono">
                                <span>00:00 (Noche)</span>
                                <span>06:00 (Encendido LED)</span>
                                <span>12:00 (Pico Fotosintético)</span>
                                <span>18:00 (Apagado LED)</span>
                                <span>23:59</span>
                            </div>
                        </div>
                    </div>

                    <!-- Vista 2: Riego & VWC -->
                    <div v-if="pestanaActiva === 'riego'" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="bg-slate-900/90 border border-slate-800 p-4 rounded-2xl">
                                <span class="text-[10px] text-slate-400 font-bold uppercase">Humedad Sustrato (VWC)</span>
                                <div class="text-3xl font-black text-cyan-400 mt-1">58.4 %</div>
                                <p class="text-xs text-slate-400 mt-1">Sensor Teros 12 en maceta testigo #4</p>
                            </div>
                            <div class="bg-slate-900/90 border border-slate-800 p-4 rounded-2xl">
                                <span class="text-[10px] text-slate-400 font-bold uppercase">Drenaje & Dryback</span>
                                <div class="text-3xl font-black text-teal-400 mt-1">16.8 %</div>
                                <p class="text-xs text-slate-400 mt-1">Dryback nocturno planificado: 18%</p>
                            </div>
                            <div class="bg-slate-900/90 border border-slate-800 p-4 rounded-2xl">
                                <span class="text-[10px] text-slate-400 font-bold uppercase">EC Entrada / Drenaje</span>
                                <div class="text-3xl font-black text-emerald-400 mt-1">2.4 / 2.6</div>
                                <p class="text-xs text-slate-400 mt-1">pH 6.1 &bull; Solución balanceada</p>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-cyan-950/20 border border-cyan-800/40 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-cyan-300">Estrategia Activa: Riego por Micro-Pulsos P1 + P2</span>
                                    <p class="text-slate-400 text-[11px]">3 pulsos de 120ml aplicados hoy &bull; Próximo riego automático a las 14:30 hs</p>
                                </div>
                            </div>
                            <span class="font-mono bg-cyan-900/50 text-cyan-300 px-2.5 py-1 rounded-md text-[11px] font-bold">
                                Automatizado
                            </span>
                        </div>
                    </div>

                    <!-- Vista 3: Arquitectura Sala -->
                    <div v-if="pestanaActiva === 'salas'" class="space-y-4">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="bg-slate-900/90 border border-slate-800 p-3.5 rounded-2xl text-center">
                                <span class="text-[10px] text-slate-400 font-bold uppercase">Superficie & Cubaje</span>
                                <div class="text-xl font-bold text-white mt-1">42.0 m² / 126 m³</div>
                            </div>
                            <div class="bg-slate-900/90 border border-slate-800 p-3.5 rounded-2xl text-center">
                                <span class="text-[10px] text-slate-400 font-bold uppercase">Carga Lumínica</span>
                                <div class="text-xl font-bold text-amber-400 mt-1">32.6 kW Total</div>
                            </div>
                            <div class="bg-slate-900/90 border border-slate-800 p-3.5 rounded-2xl text-center">
                                <span class="text-[10px] text-slate-400 font-bold uppercase">Deshumidificación</span>
                                <div class="text-xl font-bold text-blue-400 mt-1">450 Pints/Día</div>
                            </div>
                            <div class="bg-slate-900/90 border border-slate-800 p-3.5 rounded-2xl text-center">
                                <span class="text-[10px] text-slate-400 font-bold uppercase">Capacidad Macetas</span>
                                <div class="text-xl font-bold text-emerald-400 mt-1">280 Plantas</div>
                            </div>
                        </div>
                        <div class="p-3 bg-slate-900 rounded-xl border border-slate-800 text-xs text-slate-300 flex items-center justify-between">
                            <span>Equipos asignados: 48x LED SpiderFarmer SE7000, 2x Quest 225, 1x Daikin Inverter 60k BTU, 1x Sensor TrolMaster Hydro-X</span>
                            <span class="text-emerald-400 font-bold">100% Operativo</span>
                        </div>
                    </div>

                    <!-- Vista 4: Genéticas & Lotes -->
                    <div v-if="pestanaActiva === 'trazabilidad'" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="bg-slate-900/90 border border-slate-800 p-3.5 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-emerald-400">Gorilla Zkittlez</span>
                                    <span class="text-[10px] font-mono text-slate-400">LOTE-GZK-03</span>
                                </div>
                                <div class="text-lg font-black text-white mt-1">Día 48 de Floración</div>
                                <div class="text-xs text-slate-400">THC 24% &bull; Cosecha en 12 días</div>
                            </div>
                            <div class="bg-slate-900/90 border border-slate-800 p-3.5 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-purple-400">Super Lemon Haze</span>
                                    <span class="text-[10px] font-mono text-slate-400">LOTE-SLH-01</span>
                                </div>
                                <div class="text-lg font-black text-white mt-1">Semana 3 Vegetativo</div>
                                <div class="text-xs text-slate-400">Sativa 70% &bull; Poda apical lista</div>
                            </div>
                            <div class="bg-slate-900/90 border border-slate-800 p-3.5 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-teal-400">Cannatonic CBD</span>
                                    <span class="text-[10px] font-mono text-slate-400">LOTE-CBD-02</span>
                                </div>
                                <div class="text-lg font-black text-white mt-1">Secado Controlado</div>
                                <div class="text-xs text-slate-400">CBD 18% &bull; 18°C y 60% HR constante</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN 2: CARACTERÍSTICAS TÉCNICAS (POR QUÉ VENDER CULTIVOOS) -->
        <section id="salas" class="relative z-10 py-20 bg-slate-950/60 border-y border-slate-800/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-400">Arquitectura de Misión Crítica</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white mt-2">
                        Construido para cultivadores comerciales e investigadores
                    </h2>
                    <p class="text-sm text-slate-400 mt-3">
                        Elimina conjeturas humanas y sustitúyelas por trazabilidad matemática, algoritmos de VPD y prevención activa de plagas.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Tarjeta 1 -->
                    <div class="bg-slate-900/90 border border-slate-800 hover:border-emerald-500/40 p-6 rounded-3xl transition-all hover:shadow-xl hover:shadow-emerald-950/20 group">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white group-hover:text-emerald-400 transition-colors">
                            Modelado de Salas & Balance Térmico
                        </h3>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                            Cálculo dinámico de m³ de volumen de aire, disipación de calor por luminarias en Watts/kW y capacidad de extracción de agua por deshumidificadores comerciales en función de la etapa fenológica.
                        </p>
                    </div>

                    <!-- Tarjeta 2 -->
                    <div class="bg-slate-900/90 border border-slate-800 hover:border-teal-500/40 p-6 rounded-3xl transition-all hover:shadow-xl hover:shadow-teal-950/20 group">
                        <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-400 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white group-hover:text-teal-400 transition-colors">
                            Gestión Hídrica de Precisión
                        </h3>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                            Configura curvas de secado (dryback), micro-pulsos de fertirriego, monitoreo de salinidad (EC) y acidez (pH) tanto para sustratos (coco/tierra) como hidroponía recirculante RDWC o aeroponía.
                        </p>
                    </div>

                    <!-- Tarjeta 3 -->
                    <div class="bg-slate-900/90 border border-slate-800 hover:border-cyan-500/40 p-6 rounded-3xl transition-all hover:shadow-xl hover:shadow-cyan-950/20 group">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white group-hover:text-cyan-400 transition-colors">
                            Multi-Tenancy & Roles Operativos
                        </h3>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                            Aislamiento estricto de bases de datos por Organización. Permite gestionar múltiples granjas o clubes cannábicos con accesos diferenciados para Directores Técnicos, Agrónomos y Operadores.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN 3: RENTABILIDAD & RETORNO DE INVERSIÓN (ROI) -->
        <section id="roi" class="relative z-10 py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <div class="bg-gradient-to-r from-emerald-950/40 via-slate-900 to-teal-950/40 border border-emerald-500/30 rounded-3xl p-8 sm:p-12">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-7 space-y-4">
                        <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Calculadora de Retorno</span>
                        <h2 class="text-3xl sm:text-4xl font-black text-white">
                            ¿Cuánto dinero pierde tu sala por oscilaciones de VPD no detectadas?
                        </h2>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            Un desfase de solo 0.3 kPa durante floración inhibe el intercambio de CO₂, reduce la densidad de los cogollos en hasta un 15% y dispara el riesgo de hongos patógenos. CultivoOS amortiza su costo desde la primera cosecha.
                        </p>
                        <div class="pt-4 flex flex-wrap gap-4 text-xs">
                            <div class="flex items-center gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                <span>Exportación de bitácoras para auditoría GMP</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                <span>Alertas tempranas vía Telegram / Webhooks</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                <span>Historial permanente sin pérdida de telemetría</span>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5 bg-slate-900/90 border border-slate-800 p-6 rounded-2xl space-y-4">
                        <h4 class="text-sm font-extrabold text-white">Compara el Método Tradicional vs CultivoOS</h4>
                        <div class="space-y-3 text-xs">
                            <div class="p-3 rounded-xl bg-rose-950/20 border border-rose-900/30 text-rose-300 flex items-start gap-2.5">
                                <span class="text-rose-500 font-bold">✕</span>
                                <div>
                                    <span class="font-bold">Método Convencional:</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Planillas de papel, registros a destiempo, riegos a ojo y sin trazabilidad de pérdidas de fertilizante.</p>
                                </div>
                            </div>
                            <div class="p-3 rounded-xl bg-emerald-950/30 border border-emerald-500/30 text-emerald-300 flex items-start gap-2.5">
                                <span class="text-emerald-400 font-bold">✓</span>
                                <div>
                                    <span class="font-bold">Con CultivoOS:</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Automatización estricta de tareas, sensorica en tiempo real, alarmas predictivas y consistencia fenotípica en cada lote.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN 4: PREGUNTAS FRECUENTES (FAQ AGRONÓMICO) -->
        <section class="py-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h3 class="text-2xl font-black text-white">Preguntas Frecuentes de Cultivadores Comerciales</h3>
                <p class="text-xs text-slate-400 mt-1">Todo lo que necesitas saber para desplegar CultivoOS en tus instalaciones.</p>
            </div>

            <div class="space-y-3">
                <div class="border border-slate-800 rounded-2xl bg-slate-900/60 overflow-hidden">
                    <button
                        type="button"
                        @click="toggleFaq(1)"
                        class="w-full text-left p-4 flex items-center justify-between text-sm font-bold text-slate-200 hover:text-white cursor-pointer"
                    >
                        <span>¿Es compatible con mis sensores y equipos existentes (TrolMaster, Quest, Daikin, etc.)?</span>
                        <span class="text-emerald-400 font-mono">{{ faqAbierto === 1 ? '−' : '+' }}</span>
                    </button>
                    <div v-show="faqAbierto === 1" class="px-4 pb-4 text-xs text-slate-400 border-t border-slate-800/80 pt-3 leading-relaxed">
                        Sí. CultivoOS cuenta con un módulo de arquitectura de salas donde puedes registrar y tipificar cualquier equipo comercial por potencia en kW, amperaje y capacidad volumétrica, además de conectarse a controladores IoT y microcontroladores ESP32/MQTT.
                    </div>
                </div>

                <div class="border border-slate-800 rounded-2xl bg-slate-900/60 overflow-hidden">
                    <button
                        type="button"
                        @click="toggleFaq(2)"
                        class="w-full text-left p-4 flex items-center justify-between text-sm font-bold text-slate-200 hover:text-white cursor-pointer"
                    >
                        <span>¿Puedo gestionar múltiples salas y sedes en la misma cuenta?</span>
                        <span class="text-emerald-400 font-mono">{{ faqAbierto === 2 ? '−' : '+' }}</span>
                    </button>
                    <div v-show="faqAbierto === 2" class="px-4 pb-4 text-xs text-slate-400 border-t border-slate-800/80 pt-3 leading-relaxed">
                        Absolutamente. CultivoOS está diseñado con arquitectura Multi-Tenancy estricta. Puedes crear distintas Organizaciones (ej: Sede Producción Interior, Sede R&D Genética, Club Cannábico) con salas, cultivos y personal asignado de forma totalmente aislada.
                    </div>
                </div>

                <div class="border border-slate-800 rounded-2xl bg-slate-900/60 overflow-hidden">
                    <button
                        type="button"
                        @click="toggleFaq(3)"
                        class="w-full text-left p-4 flex items-center justify-between text-sm font-bold text-slate-200 hover:text-white cursor-pointer"
                    >
                        <span>¿Soporta cultivo en sustrato inerte e hidroponía RDWC?</span>
                        <span class="text-emerald-400 font-mono">{{ faqAbierto === 3 ? '−' : '+' }}</span>
                    </button>
                    <div v-show="faqAbierto === 3" class="px-4 pb-4 text-xs text-slate-400 border-t border-slate-800/80 pt-3 leading-relaxed">
                        El módulo de Gestión Hídrica adapta automáticamente sus cálculos según el medio seleccionado: curvas de VWC y dryback para turba/coco o balance de reservorios de recirculación continua (RDWC, NFT, DWC) con rangos de temperatura de solución radicular y oxígeno disuelto.
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN 5: CTA FINAL DE CONVERSIÓN -->
        <section class="py-20 text-center relative z-10 px-4">
            <div class="max-w-3xl mx-auto space-y-6">
                <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight">
                    Eleva tu instalación al nivel de ingeniería agronómica.
                </h2>
                <p class="text-sm text-slate-300 max-w-xl mx-auto">
                    Únete a los equipos de cultivo que ya transformaron sus resultados con telemetría en tiempo real y toma de decisiones basada en datos.
                </p>
                <div class="pt-2">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 text-sm font-black px-8 py-4 rounded-2xl shadow-xl shadow-emerald-500/25 hover:scale-105 transition-all cursor-pointer"
                    >
                        <span>Iniciar en CultivoOS Hoy Mismo</span>
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- PIE DE PÁGINA (FOOTER) -->
        <footer class="border-t border-slate-800/80 py-10 px-4 sm:px-6 lg:px-8 text-xs text-slate-500 max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-black text-slate-400">cultivo<span class="text-emerald-400">OS</span></span>
                <span>&bull; &copy; 2026 CultivoOS Core Platform</span>
            </div>

            <div class="flex items-center gap-4 text-[11px]">
                <span class="flex items-center gap-1.5 text-emerald-400 font-mono">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    API IoT Activa
                </span>
                <span class="font-mono text-slate-500">
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </span>
            </div>
        </footer>
    </div>
</template>
