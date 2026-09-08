<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 font-sans antialiased selection:bg-emerald-500 selection:text-white pb-16">
    <!-- TOPBAR / NAVEGACIÓN PRINCIPAL -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-xs">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">
          <!-- Logo y Marca -->
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="text-lg font-black tracking-tight text-slate-900">Cultivo<span class="text-emerald-600">OS</span></span>
                <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 rounded-md">Pro v3.2</span>
              </div>
              <p class="text-xs text-slate-400 font-medium">Plataforma Agronómica de Precisión</p>
            </div>
          </div>

          <!-- Selector de Organización y Multi-Tenancy -->
          <div class="relative">
            <button 
              type="button" 
              @click="toggleMenuOrg"
              class="flex items-center gap-2.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200/80 rounded-2xl border border-slate-200/80 transition-all text-left"
            >
              <div class="w-7 h-7 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shadow-xs">
                🏢
              </div>
              <div class="hidden sm:block">
                <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5 leading-none">
                  <span>{{ orgActiva.nombre }}</span>
                  <span class="px-1.5 py-0.2 text-[9px] bg-slate-200 text-slate-700 font-bold rounded">{{ orgActiva.plan }}</span>
                </div>
                <div class="text-[11px] text-slate-500 mt-0.5 leading-none">{{ rolActivo.nombre }}</div>
              </div>
              <svg class="w-4 h-4 text-slate-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Dropdown Organizaciones y Roles -->
            <div 
              v-if="menuOrgOpen" 
              class="absolute left-0 sm:right-0 sm:left-auto mt-2 w-80 bg-white rounded-3xl shadow-2xl border border-slate-200 p-4 z-50 animate-in fade-in zoom-in-95 duration-150"
            >
              <div class="border-b border-slate-100 pb-3 mb-3">
                <div class="text-xs font-black text-slate-400 uppercase tracking-wider">Organización Activa</div>
                <div class="text-sm font-bold text-slate-900 mt-1">{{ orgActiva.nombre }}</div>
                <div class="text-xs text-slate-500">{{ orgActiva.direccion || 'Sede Central' }}</div>
              </div>

              <!-- Lista de Organizaciones Disponibles -->
              <div class="space-y-1.5 mb-3">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-1">Cambiar Organización</div>
                <button
                  v-for="org in listaOrganizaciones"
                  :key="org.id"
                  @click="seleccionarOrganizacion(org)"
                  class="w-full flex items-center justify-between p-2 rounded-xl text-left text-xs transition-colors"
                  :class="org.id === orgActiva.id ? 'bg-emerald-50 text-emerald-800 font-bold border border-emerald-200' : 'hover:bg-slate-50 text-slate-700'"
                >
                  <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="org.id === orgActiva.id ? 'bg-emerald-600' : 'bg-slate-300'"></span>
                    <span>{{ org.nombre }}</span>
                  </div>
                  <span class="text-[10px] px-1.5 py-0.5 rounded font-mono" :class="org.id === orgActiva.id ? 'bg-emerald-200/60' : 'bg-slate-100'">
                    {{ org.plan }}
                  </span>
                </button>
              </div>

              <!-- Botón Crear Organización -->
              <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                <button
                  type="button"
                  @click="abrirModalNuevaOrg"
                  class="flex-1 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-colors shadow-xs"
                >
                  <span>+</span>
                  <span>Nueva Organización</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Pestañas de Vista / Navegación Central -->
          <div class="hidden lg:flex items-center gap-1 bg-slate-100 p-1.5 rounded-2xl border border-slate-200">
            <button
              v-for="tab in tabsVistas"
              :key="tab.id"
              type="button"
              @click="cambiarVista(tab.id)"
              class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all"
              :class="vistaActiva === tab.id ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50'"
            >
              <span>{{ tab.icon }}</span>
              <span>{{ tab.label }}</span>
              <span v-if="tab.badge" class="px-1.5 py-0.2 text-[10px] bg-emerald-100 text-emerald-800 rounded-full font-bold">
                {{ tab.badge }}
              </span>
            </button>
          </div>

          <!-- Acciones Rápidas & Menú de Usuario -->
          <div class="flex items-center gap-2">
            <!-- Botón ⚡ Simular Telemetría IoT -->
            <button
              type="button"
              @click="simularTelemetriaIoT"
              title="Simular nueva lectura de sensores IoT en vivo"
              class="hidden sm:flex items-center gap-1.5 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-2xl text-xs font-bold transition-all shadow-xs"
            >
              <span class="animate-pulse">⚡</span>
              <span>Simular IoT</span>
            </button>

            <!-- Botón + Nuevo Recurso Dropdown -->
            <div class="relative">
              <button
                type="button"
                @click="menuNuevoOpen = !menuNuevoOpen"
                class="flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold transition-all shadow-md shadow-emerald-600/20"
              >
                <span>+</span>
                <span class="hidden sm:inline">Nuevo</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <div 
                v-if="menuNuevoOpen"
                class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 z-50 animate-in fade-in zoom-in-95 duration-100"
              >
                <button @click="abrirModal('SALA')" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left text-xs font-medium hover:bg-slate-50 text-slate-700">
                  <span class="text-base">🏢</span>
                  <div>
                    <div class="font-bold text-slate-800">Nueva Sala Climatizada</div>
                    <div class="text-[10px] text-slate-400">Dimensiones, m³ y potencia</div>
                  </div>
                </button>
                <button @click="abrirModal('CULTIVO')" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left text-xs font-medium hover:bg-slate-50 text-slate-700">
                  <span class="text-base">🌱</span>
                  <div>
                    <div class="font-bold text-slate-800">Nuevo Lote de Cultivo</div>
                    <div class="text-[10px] text-slate-400">Variedades y genéticas</div>
                  </div>
                </button>
                <button @click="abrirModal('PLANIFICACION')" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left text-xs font-medium hover:bg-slate-50 text-slate-700">
                  <span class="text-base">📋</span>
                  <div>
                    <div class="font-bold text-slate-800">Nueva Planificación</div>
                    <div class="text-[10px] text-slate-400">Protocolos y timelines</div>
                  </div>
                </button>
                <button @click="abrirModal('TASK')" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left text-xs font-medium hover:bg-slate-50 text-slate-700">
                  <span class="text-base">✅</span>
                  <div>
                    <div class="font-bold text-slate-800">Nueva Tarea / Checklist</div>
                    <div class="text-[10px] text-slate-400">Asignar a cultivo</div>
                  </div>
                </button>
                <button @click="abrirModal('EVENTO')" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left text-xs font-medium hover:bg-slate-50 text-slate-700">
                  <span class="text-base">📅</span>
                  <div>
                    <div class="font-bold text-slate-800">Nuevo Evento</div>
                    <div class="text-[10px] text-slate-400">Riego, poda, foliar</div>
                  </div>
                </button>
                <button @click="abrirModal('REGISTRO')" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left text-xs font-medium hover:bg-slate-50 text-slate-700">
                  <span class="text-base">📊</span>
                  <div>
                    <div class="font-bold text-slate-800">Registrar Telemetría</div>
                    <div class="text-[10px] text-slate-400">Lectura manual sensores</div>
                  </div>
                </button>
              </div>
            </div>

            <!-- Perfil de Usuario Dropdown -->
            <div class="relative">
              <button
                type="button"
                @click="toggleMenuUsuario"
                class="flex items-center gap-2 p-1.5 hover:bg-slate-100 rounded-2xl border border-transparent hover:border-slate-200 transition-all"
              >
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-slate-800 to-slate-950 text-white flex items-center justify-center text-xs font-black shadow-xs">
                  JP
                </div>
                <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Popover de Perfil y Roles -->
              <div
                v-if="menuUsuarioOpen"
                class="absolute right-0 mt-2 w-72 bg-white rounded-3xl shadow-2xl border border-slate-200 p-4 z-50 animate-in fade-in zoom-in-95 duration-150"
              >
                <!-- Encabezado Usuario -->
                <div class="flex items-center gap-3 border-b border-slate-100 pb-3 mb-3">
                  <div class="w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-sm font-bold shadow-xs">
                    JP
                  </div>
                  <div>
                    <div class="text-sm font-bold text-slate-900">{{ usuarioActual.name }}</div>
                    <div class="text-xs text-slate-400">{{ usuarioActual.email }}</div>
                    <span class="inline-block mt-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md">
                      {{ rolActivo.nombre }}
                    </span>
                  </div>
                </div>

                <!-- Roles Disponibles para Conmutar -->
                <div class="space-y-1.5 mb-3">
                  <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-1">Conmutar Rol (RBAC)</div>
                  <button
                    v-for="r in listaRoles"
                    :key="r.id"
                    @click="seleccionarRol(r)"
                    class="w-full flex items-center justify-between p-2 rounded-xl text-left text-xs transition-colors"
                    :class="r.id === rolActivo.id ? 'bg-emerald-50 text-emerald-800 font-bold border border-emerald-200' : 'hover:bg-slate-50 text-slate-600'"
                  >
                    <span>{{ r.nombre }}</span>
                    <span v-if="r.id === rolActivo.id" class="text-emerald-600 font-bold">✓</span>
                  </button>
                </div>

                <!-- Botones de Acción -->
                <div class="pt-2 border-t border-slate-100 space-y-1">
                  <button
                    type="button"
                    @click="mostrarToast('Configuración del perfil actualizada')"
                    class="w-full flex items-center gap-2 p-2 rounded-xl text-left text-xs text-slate-700 hover:bg-slate-50 font-medium"
                  >
                    <span>⚙️</span>
                    <span>Preferencias de Usuario</span>
                  </button>
                  <button
                    type="button"
                    @click="mostrarToast('Sesión cerrada correctamente (Simulado)')"
                    class="w-full flex items-center gap-2 p-2 rounded-xl text-left text-xs text-rose-600 hover:bg-rose-50 font-bold"
                  >
                    <span>🚪</span>
                    <span>Cerrar Sesión</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pestañas Mobile -->
        <div class="flex lg:hidden overflow-x-auto gap-1 pb-2 pt-1 border-t border-slate-100">
          <button
            v-for="tab in tabsVistas"
            :key="tab.id"
            type="button"
            @click="cambiarVista(tab.id)"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all"
            :class="vistaActiva === tab.id ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600'"
          >
            <span>{{ tab.icon }}</span>
            <span>{{ tab.label }}</span>
          </button>
        </div>
      </div>
    </header>

    <!-- CUERPO PRINCIPAL / VISTAS -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6">

      <!-- ========================================================================= -->
      <!-- VISTA 1: DASHBOARD GENERAL & CULTIVOS                                      -->
      <!-- ========================================================================= -->
      <section v-if="vistaActiva === 'DASHBOARD'" class="space-y-6">
        
        <!-- KPI METRICS ROW -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
              🏢
            </div>
            <div>
              <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Salas Activas</div>
              <div class="text-2xl font-black text-slate-900">{{ totalSalasActivas }} <span class="text-xs font-normal text-slate-400">/ {{ listaSalas.length }}</span></div>
              <div class="text-[11px] text-emerald-600 font-medium">100% monitoreo IoT</div>
            </div>
          </div>

          <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
              🌱
            </div>
            <div>
              <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Cultivos en Curso</div>
              <div class="text-2xl font-black text-slate-900">{{ listaCultivos.length }}</div>
              <div class="text-[11px] text-blue-600 font-medium">{{ totalPlantas }} plantas totales</div>
            </div>
          </div>

          <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
              ⚡
            </div>
            <div>
              <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">VPD Promedio</div>
              <div class="text-2xl font-black text-slate-900">{{ promedioVPD }} <span class="text-xs font-normal text-slate-400">kPa</span></div>
              <div class="text-[11px] text-amber-600 font-medium">Rango Óptimo de Transpiración</div>
            </div>
          </div>

          <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
              📋
            </div>
            <div>
              <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Tareas Pendientes</div>
              <div class="text-2xl font-black text-slate-900">{{ tareasPendientesCount }}</div>
              <div class="text-[11px] text-purple-600 font-medium">Planificaciones activas</div>
            </div>
          </div>
        </div>

        <!-- TELEMETRÍA AMBIENTAL & DISTRIBUCIÓN GENÉTICA (GRÁFICOS SVG INTERACTIVOS) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
          <!-- GRÁFICO 1: TELEMETRÍA 24 HORAS CON ÁREA Y SELECTOR DE MÉTRICA -->
          <div class="lg:col-span-8 bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
              <div>
                <div class="flex items-center gap-2">
                  <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                  <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">
                    Telemetría Ambiental (Últimas 24 Horas)
                  </h3>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">
                  Promedios horarios en salas de cultivo con fotoperíodo activo
                </p>
              </div>

              <!-- Selector de Métrica (Temp, HR, VPD, CO2) -->
              <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-2xl border border-slate-200 self-start sm:self-auto">
                <button
                  type="button"
                  @click="metricaActiva = 'temp'"
                  class="px-2.5 py-1 rounded-xl text-xs font-bold transition-all"
                  :class="metricaActiva === 'temp' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                >
                  🌡️ Temp
                </button>
                <button
                  type="button"
                  @click="metricaActiva = 'hum'"
                  class="px-2.5 py-1 rounded-xl text-xs font-bold transition-all"
                  :class="metricaActiva === 'hum' ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                >
                  💧 HR%
                </button>
                <button
                  type="button"
                  @click="metricaActiva = 'vpd'"
                  class="px-2.5 py-1 rounded-xl text-xs font-bold transition-all"
                  :class="metricaActiva === 'vpd' ? 'bg-white text-amber-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                >
                  ⚡ VPD
                </button>
                <button
                  type="button"
                  @click="metricaActiva = 'co2'"
                  class="px-2.5 py-1 rounded-xl text-xs font-bold transition-all"
                  :class="metricaActiva === 'co2' ? 'bg-white text-purple-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                >
                  💨 CO2
                </button>
              </div>
            </div>

            <!-- Gráfico SVG Interactivo -->
            <div class="relative w-full h-64 bg-slate-50/50 rounded-2xl p-4 border border-slate-100">
              <svg class="w-full h-full overflow-visible" viewBox="0 0 700 200" preserveAspectRatio="none">
                <defs>
                  <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" :stop-color="configMetricaActual.color" stop-opacity="0.35" />
                    <stop offset="100%" :stop-color="configMetricaActual.color" stop-opacity="0.0" />
                  </linearGradient>
                </defs>

                <!-- Líneas de Guía Horizontal -->
                <line x1="0" y1="40" x2="700" y2="40" stroke="#e2e8f0" stroke-dasharray="4 4" stroke-width="1" />
                <line x1="0" y1="100" x2="700" y2="100" stroke="#e2e8f0" stroke-dasharray="4 4" stroke-width="1" />
                <line x1="0" y1="160" x2="700" y2="160" stroke="#e2e8f0" stroke-dasharray="4 4" stroke-width="1" />

                <!-- Área sombreada -->
                <polygon :points="svgAreaPoints" fill="url(#chartGradient)" />

                <!-- Línea de Curva Principal -->
                <polyline 
                  :points="svgLinePoints" 
                  fill="none" 
                  :stroke="configMetricaActual.color" 
                  stroke-width="3.5" 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                />

                <!-- Puntos Interactivos -->
                <circle 
                  v-for="(pt, idx) in puntosSvg" 
                  :key="idx" 
                  :cx="pt.x" 
                  :cy="pt.y" 
                  r="5" 
                  :fill="configMetricaActual.color" 
                  stroke="#ffffff" 
                  stroke-width="2.5"
                  class="cursor-pointer hover:scale-150 transition-transform"
                >
                  <title>{{ pt.hora }}: {{ pt.valor }} {{ configMetricaActual.unidad }}</title>
                </circle>
              </svg>

              <!-- Etiquetas Eje X (Horarios) -->
              <div class="flex justify-between text-[11px] text-slate-400 font-mono mt-2 px-1">
                <span v-for="d in datosClima24h" :key="d.hora">{{ d.hora }}</span>
              </div>
            </div>

            <!-- Chips Resumen de Valores Min/Max/Promedio -->
            <div class="grid grid-cols-3 gap-3 pt-2">
              <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase">Mínimo 24h</div>
                <div class="text-base font-black text-slate-800">{{ valorMinimoMetrica }} {{ configMetricaActual.unidad }}</div>
              </div>
              <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase">Promedio Actual</div>
                <div class="text-base font-black text-emerald-700">{{ valorPromedioMetrica }} {{ configMetricaActual.unidad }}</div>
              </div>
              <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase">Máximo 24h</div>
                <div class="text-base font-black text-slate-800">{{ valorMaximoMetrica }} {{ configMetricaActual.unidad }}</div>
              </div>
            </div>
          </div>

          <!-- GRÁFICO 2: DISTRIBUCIÓN DE GENÉTICAS (DONUT CHART SVG) -->
          <div class="lg:col-span-4 bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4">
            <div>
              <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <span class="text-base">🧬</span>
                <div>
                  <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">
                    Distribución Genética
                  </h3>
                  <p class="text-xs text-slate-400">Variedades en salas de floración</p>
                </div>
              </div>

              <!-- Donut SVG -->
              <div class="relative w-48 h-48 mx-auto my-4 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                  <!-- Fondo de aro -->
                  <circle cx="18" cy="18" r="14" fill="transparent" stroke="#f1f5f9" stroke-width="4.5" />
                  <!-- Sativas 35% -->
                  <circle cx="18" cy="18" r="14" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="30.8 57.2" stroke-dashoffset="0" />
                  <!-- Indicas 30% -->
                  <circle cx="18" cy="18" r="14" fill="transparent" stroke="#6366f1" stroke-width="4.5" stroke-dasharray="26.4 61.6" stroke-dashoffset="-30.8" />
                  <!-- Híbridas 25% -->
                  <circle cx="18" cy="18" r="14" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="22.0 66.0" stroke-dashoffset="-57.2" />
                  <!-- CBD 10% -->
                  <circle cx="18" cy="18" r="14" fill="transparent" stroke="#14b8a6" stroke-width="4.5" stroke-dasharray="8.8 79.2" stroke-dashoffset="-79.2" />
                </svg>

                <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                  <span class="text-2xl font-black text-slate-900">{{ totalPlantas }}</span>
                  <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Plantas</span>
                </div>
              </div>

              <!-- Leyenda de Genéticas -->
              <div class="space-y-2">
                <div v-for="g in distribucionGenetica" :key="g.name" class="flex items-center justify-between text-xs p-2 rounded-xl bg-slate-50 border border-slate-100">
                  <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" :style="{ backgroundColor: g.color }"></span>
                    <span class="font-bold text-slate-700">{{ g.name }}</span>
                  </div>
                  <span class="font-mono font-bold text-slate-900">{{ g.value }}%</span>
                </div>
              </div>
            </div>

            <button
              type="button"
              @click="mostrarToast('Catálogo de genéticas sincronizado')"
              class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors text-center"
            >
              Ver Catálogo Completo (6 Variedades)
            </button>
          </div>
        </div>

        <!-- SECCIÓN: SALAS CLIMATIZADAS (GRID CON TELEMETRÍA VIVA) -->
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-base font-black text-slate-900 uppercase tracking-wider">Salas Climatizadas</h2>
              <p class="text-xs text-slate-400">Ambientes controlados por sensores y actuadores</p>
            </div>
            <button
              type="button"
              @click="abrirModal('SALA')"
              class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold flex items-center gap-1.5 transition-colors shadow-xs"
            >
              <span>+</span>
              <span>Nueva Sala</span>
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <div
              v-for="sala in listaSalas"
              :key="sala.id"
              class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs hover:border-emerald-300 transition-all flex flex-col justify-between space-y-4"
            >
              <div>
                <div class="flex items-center justify-between">
                  <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-lg" :class="sala.estado === 'ACTIVA' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'">
                    {{ sala.tipo }}
                  </span>
                  <span class="text-xs font-mono text-slate-400">{{ sala.codigo }}</span>
                </div>

                <h3 class="text-base font-bold text-slate-900 mt-2">{{ sala.nombre }}</h3>
                <p class="text-xs text-slate-500">Cubaje: <span class="font-mono font-bold text-slate-700">{{ sala.volumen_m3 }} m³</span> ({{ sala.area }} m² · {{ sala.altura }}m)</p>

                <!-- Lecturas de Telemetría en la Sala -->
                <div class="grid grid-cols-4 gap-2 mt-4 p-3 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                  <div>
                    <div class="text-[10px] text-slate-400 font-bold">TEMP</div>
                    <div class="text-xs font-black text-slate-800">{{ sala.temperatura_c }}°C</div>
                  </div>
                  <div>
                    <div class="text-[10px] text-slate-400 font-bold">HR</div>
                    <div class="text-xs font-black text-blue-700">{{ sala.humedad_pct }}%</div>
                  </div>
                  <div>
                    <div class="text-[10px] text-slate-400 font-bold">VPD</div>
                    <div class="text-xs font-black text-amber-700">{{ sala.vpd_kpa }}</div>
                  </div>
                  <div>
                    <div class="text-[10px] text-slate-400 font-bold">CO2</div>
                    <div class="text-xs font-black text-purple-700">{{ sala.co2_ppm }}</div>
                  </div>
                </div>
              </div>

              <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">Potencia: <strong class="text-slate-800">{{ sala.potencia_luces_w }} W</strong></span>
                <button
                  type="button"
                  @click="abrirDetalleSala(sala)"
                  class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors"
                >
                  Ver Ficha Técnica →
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- SECCIÓN: CULTIVOS ACTIVOS -->
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-base font-black text-slate-900 uppercase tracking-wider">Cultivos en Curso</h2>
              <p class="text-xs text-slate-400">Progreso fenológico, fotoperíodos y genéticas</p>
            </div>
            <button
              type="button"
              @click="abrirModal('CULTIVO')"
              class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold flex items-center gap-1.5 transition-colors shadow-xs"
            >
              <span>+</span>
              <span>Iniciar Cultivo</span>
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <div
              v-for="cultivo in listaCultivos"
              :key="cultivo.id"
              class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs hover:border-emerald-300 transition-all flex flex-col justify-between space-y-4"
            >
              <div>
                <div class="flex items-center justify-between">
                  <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-lg bg-blue-100 text-blue-800">
                    {{ cultivo.etapa }}
                  </span>
                  <span class="text-xs font-mono text-slate-400">{{ cultivo.codigo }}</span>
                </div>

                <h3 class="text-base font-bold text-slate-900 mt-2">{{ cultivo.nombre_lote }}</h3>
                <p class="text-xs text-slate-500 font-medium">Sala: <strong class="text-slate-700">{{ cultivo.sala_nombre }}</strong></p>

                <!-- Barra de Progreso de Ciclo -->
                <div class="mt-4 space-y-1.5">
                  <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Día {{ cultivo.dia_actual || 35 }} de {{ cultivo.dias_totales_estimados }}</span>
                    <span class="font-bold text-emerald-700">{{ Math.round(((cultivo.dia_actual || 35) / cultivo.dias_totales_estimados) * 100) }}%</span>
                  </div>
                  <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div 
                      class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full"
                      :style="{ width: Math.min(100, Math.round(((cultivo.dia_actual || 35) / cultivo.dias_totales_estimados) * 100)) + '%' }"
                    ></div>
                  </div>
                </div>

                <!-- Chips Rápidos -->
                <div class="flex items-center gap-2 mt-4 text-[11px]">
                  <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded-lg font-mono font-bold">⏱️ {{ cultivo.fotoperiodo }}</span>
                  <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded-lg font-bold">🌿 {{ cultivo.plantas_totales }} Plantas</span>
                </div>
              </div>

              <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">Semana {{ cultivo.semana_actual }}</span>
                <button
                  type="button"
                  @click="abrirDetalleCultivo(cultivo)"
                  class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-xs font-bold transition-colors"
                >
                  Detalle de Lote →
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- SECCIÓN: EVENTOS Y REGISTROS DIARIOS (SIDE BY SIDE) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- EVENTOS / AGRO-CALENDARIO -->
          <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Eventos Programados</h3>
                <p class="text-xs text-slate-400">Riegos, podas y aplicaciones fitosanitarias</p>
              </div>
              <button
                type="button"
                @click="abrirModal('EVENTO')"
                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors"
              >
                + Nuevo Evento
              </button>
            </div>

            <div class="space-y-3">
              <div
                v-for="evt in listaEventos"
                :key="evt.id"
                class="p-3.5 rounded-2xl border transition-all flex items-start justify-between gap-3"
                :class="evt.estado_evento?.nombre === 'REALIZADO' ? 'bg-slate-50/60 border-slate-200 opacity-75' : 'bg-white border-slate-200 shadow-xs'"
              >
                <div class="space-y-1">
                  <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 text-[10px] font-black uppercase rounded-md" :class="evt.estado_evento?.nombre === 'REALIZADO' ? 'bg-slate-200 text-slate-700' : 'bg-amber-100 text-amber-800'">
                      {{ evt.tipo_evento?.nombre || 'AGRONOMICO' }}
                    </span>
                    <span class="text-xs font-mono text-slate-400">{{ evt.fecha_inicio_planificacion }}</span>
                  </div>
                  <h4 class="text-xs font-bold text-slate-900">{{ evt.nombre }}</h4>
                  <p class="text-[11px] text-slate-500">{{ evt.descripcion }}</p>
                </div>
                <button
                  type="button"
                  @click="toggleEstadoEvento(evt)"
                  class="px-2.5 py-1 rounded-xl text-[11px] font-bold transition-colors shrink-0"
                  :class="evt.estado_evento?.nombre === 'REALIZADO' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-600'"
                >
                  {{ evt.estado_evento?.nombre === 'REALIZADO' ? '✓ Listo' : 'Marcar Hecho' }}
                </button>
              </div>
            </div>
          </div>

          <!-- REGISTROS DE TELEMETRÍA / BITÁCORA -->
          <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Bitácora & Telemetría IoT</h3>
                <p class="text-xs text-slate-400">Lecturas de sensores y registros manuales</p>
              </div>
              <button
                type="button"
                @click="abrirModal('REGISTRO')"
                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors"
              >
                + Nuevo Registro
              </button>
            </div>

            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
              <div
                v-for="reg in listaRegistros"
                :key="reg.id"
                class="p-3.5 bg-slate-50/70 rounded-2xl border border-slate-200/70 space-y-2"
              >
                <div class="flex items-center justify-between">
                  <span class="text-xs font-bold text-slate-800">{{ reg.nombre }}</span>
                  <span class="text-[10px] font-mono text-slate-400">{{ reg.fecha_registro }}</span>
                </div>
                <p class="text-[11px] text-slate-500">{{ reg.descripcion }}</p>
                <div class="flex items-center gap-2 pt-1 text-[10px] font-mono font-bold">
                  <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded">🌡️ {{ reg.temperatura }}°C</span>
                  <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded">💧 {{ reg.humedad }}%</span>
                  <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded">⚡ VPD {{ reg.vpd }}</span>
                  <span v-if="reg.co2" class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded">💨 {{ reg.co2 }} ppm</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- VISTA 2: TIMELINE & PLANIFICACIONES                                       -->
      <!-- ========================================================================= -->
      <section v-if="vistaActiva === 'PLANIFICACIONES'" class="space-y-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-black text-slate-900">Planificaciones & Protocolos Agronómicos</h2>
            <p class="text-xs text-slate-400">Timelines de floración, nutrición y checklists de tareas por cultivo</p>
          </div>
          <div class="flex items-center gap-2">
            <button
              type="button"
              @click="abrirModal('TASK')"
              class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-bold transition-colors"
            >
              + Nueva Tarea
            </button>
            <button
              type="button"
              @click="abrirModal('PLANIFICACION')"
              class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold transition-colors shadow-xs"
            >
              + Nueva Planificación
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div
            v-for="plan in listaPlanificaciones"
            :key="plan.id"
            class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4"
          >
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div>
                <span class="px-2 py-0.5 text-[10px] font-black uppercase rounded bg-emerald-100 text-emerald-800">
                  {{ plan.estado || 'EN_CURSO' }}
                </span>
                <h3 class="text-base font-bold text-slate-900 mt-1">{{ plan.nombre }}</h3>
                <p class="text-xs text-slate-400 font-mono">{{ plan.codigo }} · {{ plan.cultivo?.nombre_lote }}</p>
              </div>
              <div class="text-right">
                <div class="text-xs font-bold text-slate-700">{{ plan.fecha_inicio }} → {{ plan.fecha_fin_estimada }}</div>
                <div class="text-[10px] text-slate-400 font-mono">{{ plan.duracion_hs }} Horas</div>
              </div>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">{{ plan.descripcion }}</p>

            <!-- Checklist de Tareas Asociadas -->
            <div class="space-y-2">
              <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                <span>Tareas Asociadas</span>
                <span class="text-emerald-700">
                  {{ (plan.tasks || []).filter((t: { completada: any; }) => t.completada).length }} / {{ (plan.tasks || []).length }} completadas
                </span>
              </div>

              <div class="space-y-1.5">
                <div
                  v-for="task in plan.tasks"
                  :key="task.id"
                  @click="toggleTask(plan.id, task.id)"
                  class="flex items-center justify-between p-2.5 rounded-xl border cursor-pointer transition-colors"
                  :class="task.completada ? 'bg-emerald-50/50 border-emerald-200 text-emerald-900 line-through' : 'bg-slate-50 border-slate-200 text-slate-800 hover:bg-slate-100'"
                >
                  <div class="flex items-center gap-2 text-xs">
                    <span class="w-4 h-4 rounded-md border flex items-center justify-center text-[10px]" :class="task.completada ? 'bg-emerald-600 border-emerald-600 text-white' : 'border-slate-300 bg-white'">
                      {{ task.completada ? '✓' : '' }}
                    </span>
                    <span>{{ task.titulo }}</span>
                  </div>
                  <span class="text-[10px] font-mono text-slate-400">{{ task.fecha_limite }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- VISTA 3: GESTIÓN HÍDRICA & FERTIRRIEGO DE PRECISIÓN                       -->
      <!-- ========================================================================= -->
      <section v-if="vistaActiva === 'GESTION_HIDRICA'" class="space-y-6 animate-in fade-in duration-200">
        <!-- Barra Superior de Control de Riego & Selector de Cultivo -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <span class="text-xl">💧</span>
              <h2 class="text-lg font-black text-slate-900">Gestión Hídrica & Nutrición de Precisión</h2>
              <span
                :class="esHidroponiaActual ? 'bg-cyan-100 text-cyan-800 border-cyan-200' : 'bg-emerald-100 text-emerald-800 border-emerald-200'"
                class="px-2.5 py-0.5 text-[10px] font-black uppercase rounded-full border tracking-wide"
              >
                {{ esHidroponiaActual ? 'HIDROPONÍA / RDWC' : 'SUSTRATO / COCO' }}
              </span>
            </div>
            <p class="text-xs text-slate-500">
              Monitoreo dinámico de sustrato (VWC %), dry-back, drenaje, EC/pH y reservorios recirculantes
            </p>
          </div>

          <div class="flex flex-wrap items-center gap-3">
            <!-- Selector de Cultivo Activo -->
            <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-2xl text-xs font-bold text-slate-700">
              <span class="text-slate-400">Lote:</span>
              <select
                v-model="cultivoHidricoSeleccionadoId"
                class="bg-transparent font-bold text-slate-900 focus:outline-hidden cursor-pointer"
              >
                <option v-for="c in listaCultivos" :key="c.id" :value="c.id">
                  {{ c.nombre_lote || c.nombre }} ({{ c.tipo_sistema_cultivo || 'SUSTRATO' }})
                </option>
              </select>
            </div>

            <!-- Toggle Modo Automático / Pausado -->
            <button
              type="button"
              @click="toggleModoRiegoAuto"
              :class="configHidricaActual.modo_automatico ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-amber-50 text-amber-700 border-amber-300'"
              class="px-3.5 py-2 rounded-2xl border text-xs font-bold flex items-center gap-1.5 cursor-pointer transition-colors shadow-2xs"
            >
              <span>{{ configHidricaActual.modo_automatico ? '● AUTO ACTIVO' : '⏸ PAUSADO' }}</span>
            </button>

            <!-- Botón Configuración -->
            <button
              type="button"
              @click="modalConfigHidricoOpen = true"
              class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs font-bold flex items-center gap-1.5 transition-colors cursor-pointer"
            >
              <span>⚙️</span>
              <span>Configuración</span>
            </button>

            <!-- Botón Registrar Riego -->
            <button
              type="button"
              @click="abrirModalNuevoRiego"
              class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer"
            >
              <span>+</span>
              <span>{{ esHidroponiaActual ? 'Registrar Acción Hidro' : 'Registrar Riego' }}</span>
            </button>
          </div>
        </div>

        <!-- 4 Tarjetas Métricas Dinámicas (Adaptadas a Sustrato vs Hidroponía) -->
        <div v-if="!esHidroponiaActual" class="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- 1. VWC Sustrato -->
          <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs space-y-1">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase">
              <span>VWC Humedad Sustrato</span>
              <span class="text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded font-black">ÓPTIMO</span>
            </div>
            <div class="text-2xl font-black text-slate-900">
              62.4 <span class="text-xs font-normal text-slate-400">%</span>
            </div>
            <div class="text-[11px] text-slate-500 flex justify-between pt-1 border-t border-slate-100">
              <span>Obj: {{ configHidricaActual.vwc_objetivo_pct || 62 }}%</span>
              <span class="text-amber-600 font-bold">Mín: {{ configHidricaActual.vwc_minimo_pct || 48 }}%</span>
            </div>
          </div>

          <!-- 2. Drenaje % -->
          <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs space-y-1">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase">
              <span>Drenaje & Escurrimiento</span>
              <span class="text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded font-black">18.9%</span>
            </div>
            <div class="text-2xl font-black text-slate-900">
              4.1 <span class="text-xs font-normal text-slate-400">L / día</span>
            </div>
            <div class="text-[11px] text-slate-500 flex justify-between pt-1 border-t border-slate-100">
              <span>Obj: {{ configHidricaActual.drenaje_objetivo_pct || 18 }}%</span>
              <span class="text-emerald-600 font-bold">Escurrimiento OK</span>
            </div>
          </div>

          <!-- 3. Balance EC Entrada / Salida -->
          <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs space-y-1">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase">
              <span>EC Entrada / Drenaje</span>
              <span class="text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded font-black">Δ +0.23</span>
            </div>
            <div class="text-2xl font-black text-slate-900">
              1.95 <span class="text-xs font-normal text-slate-400">/ 2.18 mS</span>
            </div>
            <div class="text-[11px] text-slate-500 flex justify-between pt-1 border-t border-slate-100">
              <span>pH Entrada: {{ configHidricaActual.ph_entrada_objetivo || 5.95 }}</span>
              <span class="text-purple-600 font-bold">Drenaje: 6.10</span>
            </div>
          </div>

          <!-- 4. Peso Maceta & Pulsos Diarios -->
          <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs space-y-1">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase">
              <span>Peso Maceta & Pulsos</span>
              <span class="text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded font-black">5 PULSOS</span>
            </div>
            <div class="text-2xl font-black text-slate-900">
              7.2 <span class="text-xs font-normal text-slate-400">kg (450ml/c.u)</span>
            </div>
            <div class="text-[11px] text-slate-500 flex justify-between pt-1 border-t border-slate-100">
              <span>Volumen Total: 21.6 L</span>
              <span class="text-slate-700 font-bold">Dry-back: 14.2%</span>
            </div>
          </div>
        </div>

        <!-- 4 Tarjetas Métricas para HIDROPONÍA RDWC -->
        <div v-else class="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- 1. Reservorio Central -->
          <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs space-y-1">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase">
              <span>Reservorio Central RDWC</span>
              <span class="text-cyan-600 bg-cyan-50 px-1.5 py-0.5 rounded font-black">85% LLENO</span>
            </div>
            <div class="text-2xl font-black text-slate-900">
              153.0 <span class="text-xs font-normal text-slate-400">/ 180 L</span>
            </div>
            <div class="text-[11px] text-slate-500 flex justify-between pt-1 border-t border-slate-100">
              <span>Módulos: 6 x 20L</span>
              <span class="text-cyan-700 font-bold">Consumo: 13.5 L/d</span>
            </div>
          </div>

          <!-- 2. pH & EC Solución Nutritiva -->
          <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs space-y-1">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase">
              <span>pH / EC Solución</span>
              <span class="text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded font-black">ESTABLE</span>
            </div>
            <div class="text-2xl font-black text-slate-900">
              5.85 <span class="text-xs font-normal text-slate-400">pH / 2.10 mS</span>
            </div>
            <div class="text-[11px] text-slate-500 flex justify-between pt-1 border-t border-slate-100">
              <span>Rango pH: 5.70 - 6.15</span>
              <span class="text-emerald-600 font-bold">EC Setpoint OK</span>
            </div>
          </div>

          <!-- 3. Temperatura Solución & Chiller -->
          <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs space-y-1">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase">
              <span>Temp. Solución & Chiller</span>
              <span class="text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded font-black">CHILLER ON</span>
            </div>
            <div class="text-2xl font-black text-slate-900">
              19.4 <span class="text-xs font-normal text-slate-400">°C</span>
            </div>
            <div class="text-[11px] text-slate-500 flex justify-between pt-1 border-t border-slate-100">
              <span>Setpoint: 19.0°C</span>
              <span class="text-blue-600 font-bold">Rango: 18.5-20.5°C</span>
            </div>
          </div>

          <!-- 4. Oxígeno Disuelto & Recirculación -->
          <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs space-y-1">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase">
              <span>Oxígeno Disuelto (DO)</span>
              <span class="text-teal-600 bg-teal-50 px-1.5 py-0.5 rounded font-black">ALTA OXIG.</span>
            </div>
            <div class="text-2xl font-black text-slate-900">
              8.2 <span class="text-xs font-normal text-slate-400">ppm</span>
            </div>
            <div class="text-[11px] text-slate-500 flex justify-between pt-1 border-t border-slate-100">
              <span>Obj: > 8.0 ppm</span>
              <span class="text-teal-700 font-bold">Bomba: 450 L/h</span>
            </div>
          </div>
        </div>

        <!-- Gráficos Interactivos de Gestión Hídrica (2 Columnas) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Gráfica 1: VWC 24h (Sustrato) o Nivel Reservorio (Hidroponía) -->
          <div class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">
                  {{ esHidroponiaActual ? 'Nivel de Reservorio Central & Reposición (24h)' : 'Curva VWC (%) Sustrato & Pulsos de Riego (24h)' }}
                </h3>
                <p class="text-xs text-slate-400">
                  {{ esHidroponiaActual ? 'Consumo de solución y reposición automática' : 'Comportamiento de humedad de sustrato con pulsos de 450 ml' }}
                </p>
              </div>
              <span class="px-2 py-1 text-[10px] font-bold rounded-lg bg-slate-100 text-slate-700">24 HORAS</span>
            </div>

            <!-- Gráfico SVG VWC 24h -->
            <div v-if="!esHidroponiaActual" class="space-y-2">
              <div class="h-44 w-full bg-slate-50/70 rounded-2xl p-2 border border-slate-100 relative flex items-end">
                <!-- Línea de Setpoint Objetivo 62% -->
                <div class="absolute inset-x-2 top-[35%] border-b border-dashed border-emerald-400 z-0 flex justify-end pr-2">
                  <span class="text-[9px] font-bold text-emerald-600 bg-white/90 px-1 rounded">Objetivo: 62%</span>
                </div>
                <!-- Línea Umbral Mínimo 48% -->
                <div class="absolute inset-x-2 top-[65%] border-b border-dashed border-amber-400 z-0 flex justify-end pr-2">
                  <span class="text-[9px] font-bold text-amber-600 bg-white/90 px-1 rounded">Mínimo: 48%</span>
                </div>

                <!-- Curva SVG de VWC -->
                <svg viewBox="0 0 700 160" class="w-full h-full overflow-visible z-10">
                  <path
                    :d="svgPathVwc"
                    fill="none"
                    stroke="#10b981"
                    stroke-width="3"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <!-- Puntos de medición -->
                  <circle
                    v-for="(pt, idx) in puntosVwcSvg"
                    :key="idx"
                    :cx="pt.x"
                    :cy="pt.y"
                    r="4"
                    fill="#ffffff"
                    stroke="#10b981"
                    stroke-width="2"
                  />
                </svg>
              </div>

              <!-- Eje Horario & Indicador de Pulsos -->
              <div class="flex justify-between text-[10px] font-mono text-slate-400 px-2">
                <span v-for="d in datosVwc24h" :key="d.hora" class="flex flex-col items-center">
                  <span>{{ d.hora }}</span>
                  <span v-if="d.pulso > 0" class="text-emerald-600 font-bold">💧 {{ d.pulso }}ml</span>
                </span>
              </div>
            </div>

            <!-- Gráfico SVG Hidroponía 24h -->
            <div v-else class="space-y-2">
              <div class="h-44 w-full bg-slate-50/70 rounded-2xl p-2 border border-slate-100 relative flex items-end">
                <svg viewBox="0 0 700 160" class="w-full h-full overflow-visible z-10">
                  <path
                    :d="svgPathHidroNivel"
                    fill="none"
                    stroke="#06b6d4"
                    stroke-width="3"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <circle
                    v-for="(pt, idx) in puntosHidroSvg"
                    :key="idx"
                    :cx="pt.x"
                    :cy="pt.y"
                    r="4"
                    fill="#ffffff"
                    stroke="#06b6d4"
                    stroke-width="2"
                  />
                </svg>
              </div>
              <div class="flex justify-between text-[10px] font-mono text-slate-400 px-2">
                <span v-for="d in datosHidro24h" :key="d.hora">{{ d.hora }} ({{ d.nivelL }}L)</span>
              </div>
            </div>
          </div>

          <!-- Gráfica 2: Drenaje & Balance EC (7 Días) o Estabilidad Química pH/EC -->
          <div class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">
                  {{ esHidroponiaActual ? 'Estabilidad Química pH & EC (7 Días)' : 'Histórico 7 Días: Drenaje % & EC Entrada vs Salida' }}
                </h3>
                <p class="text-xs text-slate-400">
                  {{ esHidroponiaActual ? 'Control de deriva y reposición de sales' : 'Evolución de acumulación de sales y porcentaje percolado' }}
                </p>
              </div>
              <span class="px-2 py-1 text-[10px] font-bold rounded-lg bg-slate-100 text-slate-700">7 DÍAS</span>
            </div>

            <!-- Gráfico de Barras / Líneas SVG 7 Días -->
            <div v-if="!esHidroponiaActual" class="space-y-3">
              <div class="h-44 w-full bg-slate-50/70 rounded-2xl p-4 border border-slate-100 flex items-end justify-between gap-2">
                <div v-for="d in datosDrenajeEc" :key="d.dia" class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
                  <div class="text-[9px] font-bold text-blue-600">{{ d.drenajePct }}%</div>
                  <!-- Barra Drenaje -->
                  <div
                    class="w-full max-w-[28px] bg-blue-500/80 rounded-t-lg transition-all"
                    :style="{ height: (((d.drenajePct || 18) / 25) * 80) + '%' }"
                  ></div>
                  <!-- Indicadores EC -->
                  <div class="text-[9px] font-mono text-slate-600 flex flex-col items-center">
                    <span class="text-purple-700 font-bold">{{ d.ecDrenaje }}mS</span>
                    <span class="text-slate-400 text-[8px]">{{ d.dia }}</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center justify-center gap-6 text-xs text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-blue-500 rounded"></span> % Drenaje Diario</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-purple-600 rounded-full"></span> EC Drenaje (mS/cm)</span>
              </div>
            </div>

            <!-- Gráfico 7 Días Hidroponía -->
            <div v-else class="space-y-3">
              <div class="h-44 w-full bg-slate-50/70 rounded-2xl p-4 border border-slate-100 flex items-end justify-between gap-2">
                <div v-for="d in datosHidro7dias" :key="d.dia" class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
                  <div class="text-[9px] font-bold text-cyan-700">{{ d.reposicionL }}L</div>
                  <div
                    class="w-full max-w-[28px] bg-cyan-500/80 rounded-t-lg transition-all"
                    :style="{ height: (((d.reposicionL || 14) / 20) * 80) + '%' }"
                  ></div>
                  <div class="text-[9px] font-mono text-slate-600 flex flex-col items-center">
                    <span class="text-emerald-700 font-bold">pH {{ d.ph }}</span>
                    <span class="text-slate-400 text-[8px]">{{ d.dia }}</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center justify-center gap-6 text-xs text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-cyan-500 rounded"></span> Reposición de Agua (L)</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-emerald-600 rounded-full"></span> pH Medido</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Estrategia de Micro-Pulsos por Fotoperíodo P1/P2/P3 -->
        <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Estrategia de Riego: Micro-Pulsos por Fotoperíodo</h3>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-xl">Algoritmo VWC Adaptativo</span>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 space-y-2">
              <div class="flex items-center justify-between">
                <span class="px-2 py-0.5 text-[10px] font-black bg-emerald-200 text-emerald-800 rounded-md">Fase P1: Ramp-Up</span>
                <span class="text-xs font-mono font-bold text-emerald-700">09:00 - 11:30</span>
              </div>
              <div class="text-xs text-slate-700">3 pulsos de 150ml para saturación progresiva hasta capacidad de campo (62% VWC) sin lavado excesivo.</div>
            </div>

            <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-200 space-y-2">
              <div class="flex items-center justify-between">
                <span class="px-2 py-0.5 text-[10px] font-black bg-blue-200 text-blue-800 rounded-md">Fase P2: Mantenimiento</span>
                <span class="text-xs font-mono font-bold text-blue-700">12:00 - 17:00</span>
              </div>
              <div class="text-xs text-slate-700">Micro-pulsos de compensación por transpiración cada 90 minutos para sostener humedad con 18% de escurrimiento.</div>
            </div>

            <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 space-y-2">
              <div class="flex items-center justify-between">
                <span class="px-2 py-0.5 text-[10px] font-black bg-amber-200 text-amber-800 rounded-md">Fase P3: Dry-Back</span>
                <span class="text-xs font-mono font-bold text-amber-700">17:00 - 09:00</span>
              </div>
              <div class="text-xs text-slate-700">Sin riego durante la noche para forzar oxigenación radicular, engorde floral y secado paulatino al 48%.</div>
            </div>
          </div>
        </div>

        <!-- Motor Agronómico: Sugerencias & Alertas de Manejo Hídrico -->
        <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span>🤖</span>
                <span>Motor Agronómico: Sugerencias & Alertas Hídricas</span>
              </h3>
              <p class="text-xs text-slate-400">Recomendaciones generadas por correlación de sensores Teros 12, balanzas y curvas de VPD</p>
            </div>
            <span class="px-2.5 py-1 text-xs font-bold bg-purple-50 text-purple-700 rounded-xl">
              {{ recomendacionesRiegoCultivo.length }} Notificaciones
            </span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="rec in recomendacionesRiegoCultivo"
              :key="rec.id"
              :class="rec.atendida ? 'opacity-60 bg-slate-50 border-slate-200' : 'bg-amber-50/50 border-amber-200'"
              class="p-4 rounded-2xl border space-y-3 transition-all"
            >
              <div class="flex items-start justify-between gap-2">
                <div class="space-y-0.5">
                  <div class="flex items-center gap-2">
                    <span :class="rec.tipo === 'ADVERTENCIA' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800'" class="px-2 py-0.5 text-[9px] font-black uppercase rounded">
                      {{ rec.tipo }}
                    </span>
                    <span class="text-[11px] font-mono text-slate-400">{{ rec.fecha }}</span>
                  </div>
                  <h4 class="text-xs font-bold text-slate-900">{{ rec.titulo }}</h4>
                </div>
                <button
                  type="button"
                  @click="atenderRecomendacionRiego(rec.id)"
                  class="px-2.5 py-1 text-[11px] font-bold rounded-lg border cursor-pointer transition-colors"
                  :class="rec.atendida ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-white hover:bg-slate-100 text-slate-700 border-slate-300'"
                >
                  {{ rec.atendida ? '✓ Revisada' : 'Marcar Revisada' }}
                </button>
              </div>

              <div class="space-y-1.5 text-xs text-slate-600 bg-white/70 p-3 rounded-xl border border-slate-100">
                <p><strong>Hecho:</strong> {{ rec.que_ocurrio }}</p>
                <p><strong>Causa Agronómica:</strong> {{ rec.por_que_relevante }}</p>
                <p class="text-emerald-800 font-medium"><strong>Acción Sugerida:</strong> {{ rec.que_revisar }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Historial Completo de Eventos de Riego y Fertirriego -->
        <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Historial de Eventos de Riego & Fertirriego</h3>
              <p class="text-xs text-slate-400">Registros de fertirrigación, lavados de raíz, recambios y calibraciones</p>
            </div>

            <!-- Filtros de Tipo de Evento -->
            <div class="flex flex-wrap items-center gap-1.5 bg-slate-100 p-1 rounded-2xl text-xs font-bold">
              <button
                v-for="filtro in ['TODOS', 'FERTIRRIEGO', 'AGUA / LIXIVIACIÓN', 'CORRECCIÓN / CALIBRACIÓN']"
                :key="filtro"
                type="button"
                @click="filtroTipoEventoRiego = filtro"
                :class="filtroTipoEventoRiego === filtro ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                class="px-3 py-1.5 rounded-xl cursor-pointer transition-all text-[11px]"
              >
                {{ filtro }}
              </button>
            </div>
          </div>

          <!-- Tabla de Eventos de Riego -->
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="border-b border-slate-200 text-[10px] font-black uppercase text-slate-400 tracking-wider">
                  <th class="py-3 px-3">Fecha & Hora</th>
                  <th class="py-3 px-3">Tipo de Acción</th>
                  <th class="py-3 px-3">Vol. Aplicado / Drenado</th>
                  <th class="py-3 px-3">% Drenaje</th>
                  <th class="py-3 px-3">EC Entrada / Salida</th>
                  <th class="py-3 px-3">pH Entrada / Salida</th>
                  <th class="py-3 px-3">Receta / Observaciones</th>
                  <th class="py-3 px-3 text-right">Estado</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="evt in eventosRiegoFiltrados" :key="evt.id" class="hover:bg-slate-50/80 transition-colors">
                  <td class="py-3 px-3 font-mono font-bold text-slate-800">{{ evt.fecha_hora }}</td>
                  <td class="py-3 px-3">
                    <span class="px-2 py-0.5 rounded-md font-bold text-[10px] bg-emerald-100 text-emerald-800">
                      {{ evt.tipo_accion }}
                    </span>
                  </td>
                  <td class="py-3 px-3 font-mono font-bold text-slate-900">
                    {{ evt.volumen_aplicado_l || evt.volumen_agua_anadida_l || '-' }} L
                    <span v-if="evt.volumen_drenado_l" class="text-slate-400 font-normal">/ {{ evt.volumen_drenado_l }} L</span>
                  </td>
                  <td class="py-3 px-3">
                    <span v-if="evt.porcentaje_drenaje_calculado" class="font-bold text-blue-700">
                      {{ evt.porcentaje_drenaje_calculado.toFixed(1) }}%
                    </span>
                    <span v-else class="text-slate-400">-</span>
                  </td>
                  <td class="py-3 px-3 font-mono text-slate-700">
                    <span v-if="evt.ec_entrada_ms">{{ evt.ec_entrada_ms }} / {{ evt.ec_drenaje_ms || '-' }} mS</span>
                    <span v-else-if="evt.ec_solucion_ms">{{ evt.ec_solucion_ms }} mS</span>
                    <span v-else>-</span>
                  </td>
                  <td class="py-3 px-3 font-mono text-slate-700">
                    <span v-if="evt.ph_entrada">{{ evt.ph_entrada }} / {{ evt.ph_drenaje || '-' }}</span>
                    <span v-else-if="evt.ph_solucion">{{ evt.ph_solucion }}</span>
                    <span v-else>-</span>
                  </td>
                  <td class="py-3 px-3 max-w-xs truncate text-slate-600">
                    <span class="font-medium text-slate-800">{{ evt.fertilizantes_receta || evt.observaciones || '-' }}</span>
                  </td>
                  <td class="py-3 px-3 text-right">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      {{ evt.estado || 'COMPLETADO' }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- VISTA 4: SALAS & EQUIPOS                                                  -->
      <!-- ========================================================================= -->
      <section v-if="vistaActiva === 'SALAS'" class="space-y-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-black text-slate-900">Infraestructura & Equipamiento Técnico</h2>
            <p class="text-xs text-slate-400">Luminarias LED, climatizadores Inverter, deshumidificadores y dosificadores</p>
          </div>
          <button
            type="button"
            @click="abrirModal('SALA')"
            class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold transition-colors shadow-xs"
          >
            + Nueva Sala Climatizada
          </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div
            v-for="sala in listaSalas"
            :key="sala.id"
            class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4"
          >
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div>
                <h3 class="text-base font-bold text-slate-900">{{ sala.nombre }}</h3>
                <p class="text-xs text-slate-400 font-mono">{{ sala.codigo }} · {{ sala.volumen_m3 }} m³</p>
              </div>
              <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-lg bg-emerald-100 text-emerald-800">
                {{ sala.estado }}
              </span>
            </div>

            <!-- Listado de Equipamiento Asignado -->
            <div class="space-y-2">
              <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">Equipamiento Técnico Asignado</div>
              <div class="space-y-1.5">
                <div
                  v-for="eq in (sala.equipamientos || [])"
                  :key="eq.id"
                  class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl border border-slate-100 text-xs"
                >
                  <div>
                    <span class="font-bold text-slate-800">{{ eq.nombre }}</span>
                    <div class="text-[10px] text-slate-400">{{ eq.marca }} · {{ eq.tipo }}</div>
                  </div>
                  <span class="font-mono font-bold text-slate-700">{{ eq.potencia_w }} W</span>
                </div>
              </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
              <span class="text-slate-500">Capacidad: <strong class="text-slate-800">{{ sala.capacidad_macetas }} macetas</strong></span>
              <button
                type="button"
                @click="abrirDetalleSala(sala)"
                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors"
              >
                Editar Parámetros →
              </button>
            </div>
          </div>
        </div>
      </section>

    </main>

    <!-- ========================================================================= -->
    <!-- MODALES INTERACTIVOS                                                      -->
    <!-- ========================================================================= -->

    <!-- 1. MODAL NUEVA ORGANIZACIÓN -->
    <div v-if="modalNuevaOrgOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
      <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <div class="flex items-center gap-2">
            <span class="text-xl">🏢</span>
            <h3 class="text-base font-bold text-slate-900">Nueva Organización Multi-Tenancy</h3>
          </div>
          <button @click="modalNuevaOrgOpen = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
        </div>

        <form @submit.prevent="guardarNuevaOrganizacion" class="space-y-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nombre de la Organización / Empresa</label>
            <input v-model="formOrg.nombre" required type="text" placeholder="Ej. BioGenetics Labs S.A." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden" />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">CUIT / Identificación Fiscal</label>
            <input v-model="formOrg.cuit_rut" type="text" placeholder="30-XXXXXXXX-X" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden" />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Dirección / Parque Industrial</label>
            <input v-model="formOrg.direccion" type="text" placeholder="Ubicación física de la instalación" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden" />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Plan de Suscripción</label>
            <select v-model="formOrg.plan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden">
              <option value="FREE">Plan Inicial (1 Sala)</option>
              <option value="PRO">Plan Profesional (Hasta 10 Salas)</option>
              <option value="ENTERPRISE">Plan Enterprise (Salas Ilimitadas + API)</option>
            </select>
          </div>

          <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
            <button type="button" @click="modalNuevaOrgOpen = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">Cancelar</button>
            <button type="submit" class="px-4 py-2 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-xs">Crear Organización</button>
          </div>
        </form>
      </div>
    </div>

    <!-- 2. MODAL NUEVA SALA CLIMATIZADA (WIZARD COMPLETO DE 3 PASOS) -->
    <div v-if="modalSalaOpen" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh]">
        <!-- Header con Indicador de 3 Pasos y Modo Full Page -->
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between border-b border-slate-800">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-emerald-500/20">
              🏢
            </div>
            <div>
              <h3 class="text-base font-bold text-slate-100">Crear Nueva Sala Climatizada</h3>
              <p class="text-xs text-slate-400">Arquitectura de Instalación y Equipamiento Técnico</p>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <!-- Stepper Badges -->
            <div class="hidden sm:flex items-center gap-1.5 bg-slate-800/80 px-3 py-1.5 rounded-full border border-slate-700">
              <span
                class="text-[11px] font-bold px-2 py-0.5 rounded-full transition-all"
                :class="pasoActualSala === 1 ? 'bg-emerald-500 text-white' : pasoActualSala > 1 ? 'bg-emerald-950 text-emerald-400 border border-emerald-700' : 'text-slate-400'"
              >
                1. Información
              </span>
              <span class="text-slate-600 text-xs">/</span>
              <span
                class="text-[11px] font-bold px-2 py-0.5 rounded-full transition-all"
                :class="pasoActualSala === 2 ? 'bg-emerald-500 text-white' : pasoActualSala > 2 ? 'bg-emerald-950 text-emerald-400 border border-emerald-700' : 'text-slate-400'"
              >
                2. Equipamiento ({{ wizardEquipamientos.length }})
              </span>
              <span class="text-slate-600 text-xs">/</span>
              <span
                class="text-[11px] font-bold px-2 py-0.5 rounded-full transition-all"
                :class="pasoActualSala === 3 ? 'bg-emerald-500 text-white' : 'text-slate-400'"
              >
                3. Resumen
              </span>
            </div>

            <!-- Botón Cerrar Modal -->
            <button
              type="button"
              @click="modalSalaOpen = false"
              class="text-slate-400 hover:text-white p-1.5 rounded-xl hover:bg-slate-800 transition-colors"
            >
              ✕
            </button>
          </div>
        </div>

        <!-- CONTENIDO SEGÚN PASO ACTUAL -->
        <div class="p-6 overflow-y-auto flex-1 space-y-6">
          <!-- ========================================================================= -->
          <!-- PASO 1: INFORMACIÓN GENERAL, DIMENSIONES & INSTALACIONES                  -->
          <!-- ========================================================================= -->
          <div v-if="pasoActualSala === 1" class="space-y-5 animate-in fade-in duration-150">
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                  1
                </div>
                <div>
                  <h4 class="text-sm font-bold text-slate-800">Paso 1: Información General y Dimensiones</h4>
                  <p class="text-xs text-slate-500">Defina el propósito agronómico, cubitaje y facilidades técnicas</p>
                </div>
              </div>
              <div class="text-right">
                <span class="text-xs text-slate-400 font-medium">Volumen Estimado:</span>
                <p class="text-sm font-black font-mono text-emerald-700">{{ volumenCalculadoWizard }} m³</p>
              </div>
            </div>

            <!-- Selector de Organización -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-2">
                <span>🏢</span>
                <span>ORGANIZACIÓN PERTENECIENTE</span>
                <span class="text-emerald-700 font-mono text-[10px] lowercase">(organizacion_id)</span>
              </label>
              <select
                v-model="wizardSalaForm.organizacion_id"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-hidden cursor-pointer"
              >
                <option v-for="org in listaOrganizaciones" :key="org.id" :value="org.id">
                  {{ org.nombre }} — {{ org.direccion || 'Sede Principal' }}
                </option>
              </select>
            </div>

            <!-- Nombre y Código -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                  NOMBRE DE LA SALA <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="wizardSalaForm.nombre"
                  type="text"
                  placeholder="Ej. Sala Épsilon - Floración #3"
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-hidden"
                />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                  CÓDIGO IDENTIFICADOR <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="wizardSalaForm.codigo"
                  type="text"
                  placeholder="Ej. SALA-FLORA-03"
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 text-sm font-mono focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-hidden uppercase"
                />
              </div>
            </div>

            <!-- Tipo y Estado -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                  TIPO DE SALA / DESTINO
                </label>
                <select
                  v-model="wizardSalaForm.tipo"
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-emerald-500/20 outline-hidden cursor-pointer"
                >
                  <option value="FLORACION">Floración</option>
                  <option value="VEGETACION">Vegetación & Madres</option>
                  <option value="SECADO">Secado & Curado</option>
                  <option value="MADRES_Y_ESQUEJES">Clones & Esquejes</option>
                  <option value="MULTIPROPOSITO">Multipropósito</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                  ESTADO INICIAL
                </label>
                <select
                  v-model="wizardSalaForm.estado"
                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-emerald-500/20 outline-hidden cursor-pointer"
                >
                  <option value="ACTIVA">Activa / En Operación</option>
                  <option value="MANTENIMIENTO">En Mantenimiento</option>
                  <option value="LIMPIEZA">Limpieza / Sanitización</option>
                  <option value="INACTIVA">Inactiva</option>
                </select>
              </div>
            </div>

            <!-- Dimensiones de la Sala -->
            <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-200 space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                  <span>📐</span> DIMENSIONES DE LA SALA
                </span>
                <span class="text-xs font-semibold text-slate-500">
                  Cálculo automático: Área (m²) × Altura (m) = Volumen (m³)
                </span>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Superficie / Área (m²)</label>
                  <input
                    v-model.number="wizardSalaForm.area_m2"
                    type="number"
                    step="0.5"
                    class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-slate-800 font-bold text-sm outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                  />
                </div>

                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Altura de Techo (m)</label>
                  <input
                    v-model.number="wizardSalaForm.altura_m"
                    type="number"
                    step="0.1"
                    class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-slate-800 font-bold text-sm outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                  />
                </div>

                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Capacidad Máx. Macetas</label>
                  <input
                    v-model.number="wizardSalaForm.capacidad_macetas"
                    type="number"
                    class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-slate-800 font-bold text-sm outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                  />
                </div>
              </div>
            </div>

            <!-- Instalaciones y Servicios Disponibles -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200">
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                INSTALACIONES Y SERVICIOS DISPONIBLES
              </label>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 cursor-pointer">
                  <input
                    type="checkbox"
                    v-model="wizardSalaForm.posee_co2"
                    class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500"
                  />
                  <span class="font-bold text-slate-700">Inyección CO₂</span>
                </label>

                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 cursor-pointer">
                  <input
                    type="checkbox"
                    v-model="wizardSalaForm.posee_extraccion"
                    class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500"
                  />
                  <span class="font-bold text-slate-700">Extracción Aire</span>
                </label>

                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 cursor-pointer">
                  <input
                    type="checkbox"
                    v-model="wizardSalaForm.posee_intraccion"
                    class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500"
                  />
                  <span class="font-bold text-slate-700">Intracción Filtrada</span>
                </label>

                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 cursor-pointer">
                  <input
                    type="checkbox"
                    v-model="wizardSalaForm.posee_ins_agua"
                    class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500"
                  />
                  <span class="font-bold text-slate-700">Red Agua / Riego</span>
                </label>
              </div>
            </div>

            <!-- Descripción y Notas -->
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                DESCRIPCIÓN Y NOTAS DE LA SALA
              </label>
              <textarea
                rows="2"
                v-model="wizardSalaForm.descripcion"
                placeholder="Detalles sobre aislación térmica, piso epóxico, cuadros eléctricos..."
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 text-xs focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-hidden"
              ></textarea>
            </div>
          </div>

          <!-- ========================================================================= -->
          <!-- PASO 2: ASIGNACIÓN DE EQUIPAMIENTO TÉCNICO                                -->
          <!-- ========================================================================= -->
          <div v-if="pasoActualSala === 2" class="space-y-6 animate-in fade-in duration-150">
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                  2
                </div>
                <div>
                  <h4 class="text-sm font-bold text-slate-800">Paso 2: Asignación de Equipamiento Técnico</h4>
                  <p class="text-xs text-slate-500">Configure luces, climatización, sensores, ventiladores y sistemas de riego</p>
                </div>
              </div>
              <div class="text-right">
                <span class="text-xs text-slate-400 font-medium">Equipos configurados:</span>
                <p class="text-sm font-black text-emerald-700">{{ wizardEquipamientos.length }} equipos</p>
              </div>
            </div>

            <!-- Selector de Tipo de Equipo -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Seleccionar Tipo de Equipo a Configurar
              </label>
              <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2">
                <button
                  v-for="item in tiposEquipamientoCatalog"
                  :key="item.tipo"
                  type="button"
                  @click="subFormTipo = item.tipo; editandoEqId = null;"
                  class="p-3 rounded-2xl border text-center transition-all flex flex-col items-center gap-1.5 cursor-pointer"
                  :class="subFormTipo === item.tipo ? 'bg-slate-900 text-white border-slate-900 shadow-md ring-2 ring-emerald-500/30' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'"
                >
                  <span class="text-lg">{{ item.icon }}</span>
                  <span class="text-[11px] font-bold tracking-tight">{{ item.label }}</span>
                </button>
              </div>
            </div>

            <!-- Subformulario de Equipo -->
            <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-4">
              <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                <h5 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                  <span>⚡</span>
                  {{ editandoEqId ? 'Modificar Especificaciones' : 'Nueva Ficha Técnica' }}: {{ subFormTipo }}
                </h5>
                <span v-if="editandoEqId" class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                  Editando equipo
                </span>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div>
                  <label class="block text-slate-500 font-semibold mb-1">Nombre / Identificador</label>
                  <input
                    type="text"
                    v-model="subFormEq.nombre"
                    placeholder="Ej. Luminaria LED Samsung 680W"
                    class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-800 outline-hidden focus:ring-2 focus:ring-emerald-500/20"
                  />
                </div>

                <div>
                  <label class="block text-slate-500 font-semibold mb-1">Marca / Modelo</label>
                  <input
                    type="text"
                    v-model="subFormEq.marca_modelo"
                    placeholder="Ej. Gavita Pro 1700e"
                    class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-800 outline-hidden focus:ring-2 focus:ring-emerald-500/20"
                  />
                </div>

                <div>
                  <label class="block text-slate-500 font-semibold mb-1">Potencia Consumo (Watts)</label>
                  <input
                    type="number"
                    v-model.number="subFormEq.potencia_w"
                    placeholder="Ej. 680"
                    class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-800 outline-hidden focus:ring-2 focus:ring-emerald-500/20"
                  />
                </div>
              </div>

              <!-- Campos específicos por tipo -->
              <div v-if="subFormTipo === 'ILUMINACION'" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs pt-2 border-t border-slate-200/80">
                <div>
                  <label class="block text-slate-500 font-semibold mb-1">Tecnología</label>
                  <select v-model="subFormEq.tipo_luminaria" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-800 outline-hidden">
                    <option value="LED">LED Full Spectrum / SMD</option>
                    <option value="HPS">Alta Presión de Sodio (HPS)</option>
                    <option value="LEC">Halogenuro Metálico (LEC/CMH)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-slate-500 font-semibold mb-1">Espectro / PPFD</label>
                  <input v-model="subFormEq.espectro" type="text" placeholder="3000K + 5000K + 660nm" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-semibold text-slate-800 outline-hidden" />
                </div>
                <div>
                  <label class="block text-slate-500 font-semibold mb-1">Dimmer (%)</label>
                  <input v-model.number="subFormEq.dimmer_pct" type="number" min="10" max="100" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-800 outline-hidden" />
                </div>
              </div>

              <div v-else-if="subFormTipo === 'CLIMATIZACION'" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs pt-2 border-t border-slate-200/80">
                <div>
                  <label class="block text-slate-500 font-semibold mb-1">Capacidad (BTU)</label>
                  <input v-model.number="subFormEq.btu" type="number" step="1000" class="w-full bg-white border border-slate-200 rounded-xl p-2.5 font-bold text-slate-800 outline-hidden" />
                </div>
                <div class="flex items-center gap-2 pt-5">
                  <input type="checkbox" v-model="subFormEq.es_split" id="split" class="w-4 h-4 text-emerald-600 rounded" />
                  <label for="split" class="font-semibold text-slate-700">Split Inverter</label>
                </div>
                <div class="flex items-center gap-2 pt-5">
                  <input type="checkbox" v-model="subFormEq.funcion_calor" id="calor" class="w-4 h-4 text-emerald-600 rounded" />
                  <label for="calor" class="font-semibold text-slate-700">Frío / Calor</label>
                </div>
              </div>

              <div class="flex items-center justify-end gap-2 pt-2">
                <button
                  v-if="editandoEqId"
                  type="button"
                  @click="editandoEqId = null; subFormEq = { nombre: '', codigo_inventario: '', potencia_w: 680 };"
                  class="px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-200 font-bold text-xs cursor-pointer"
                >
                  Cancelar Edición
                </button>
                <button
                  type="button"
                  @click="agregarOActualizarEquipo"
                  :disabled="!subFormEq.nombre"
                  class="bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white px-4 py-2 rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-xs cursor-pointer"
                >
                  <span>+</span>
                  {{ editandoEqId ? 'Guardar Cambios de Equipo' : 'Agregar Equipo a la Sala' }}
                </button>
              </div>
            </div>

            <!-- Listado de Equipos Agregados -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center justify-between">
                <span>Equipos Registrados en esta Sala ({{ wizardEquipamientos.length }})</span>
                <span class="text-slate-500 font-mono">Potencia Total: {{ potenciaTotalWizard }} W</span>
              </label>

              <div v-if="wizardEquipamientos.length === 0" class="bg-slate-50 border border-dashed border-slate-300 rounded-2xl p-6 text-center text-xs text-slate-400">
                No hay equipamiento agregado aún. Use el subformulario superior para cargar luminarias o climatizadores.
              </div>

              <div v-else class="space-y-2">
                <div
                  v-for="eq in wizardEquipamientos"
                  :key="eq.id"
                  class="bg-white border border-slate-200 rounded-xl p-3 flex items-center justify-between hover:border-slate-300 transition-colors"
                >
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs">
                      {{ eq.tipo.slice(0, 3) }}
                    </div>
                    <div>
                      <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-800 text-xs">{{ eq.nombre }}</span>
                        <span class="text-[10px] font-mono bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded">
                          {{ eq.codigo_inventario }}
                        </span>
                      </div>
                      <p class="text-[11px] text-slate-500">
                        {{ eq.marca_modelo || 'Sin marca' }} • {{ eq.potencia_w }} W {{ eq.tipo_luminaria ? '• ' + eq.tipo_luminaria : '' }}
                      </p>
                    </div>
                  </div>

                  <div class="flex items-center gap-1">
                    <button
                      type="button"
                      @click="editarEquipoWizard(eq)"
                      class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                    >
                      ✏️
                    </button>
                    <button
                      type="button"
                      @click="eliminarEquipoWizard(eq.id)"
                      class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer"
                    >
                      🗑️
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- ========================================================================= -->
          <!-- PASO 3: RESUMEN TÉCNICO Y PAYLOAD                                         -->
          <!-- ========================================================================= -->
          <div v-if="pasoActualSala === 3" class="space-y-5 animate-in fade-in duration-150">
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                  3
                </div>
                <div>
                  <h4 class="text-sm font-bold text-slate-800">Paso 3: Verificación Final y Resumen Técnico</h4>
                  <p class="text-xs text-slate-500">Revise la estructura completa de la sala antes de persistir en base de datos</p>
                </div>
              </div>
              <div class="flex items-center gap-1 text-emerald-600 font-bold text-xs bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                <span>✓</span> Listo para Guardar
              </div>
            </div>

            <!-- Ficha Resumen -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4">
              <div class="flex items-start justify-between">
                <div>
                  <span class="text-[10px] font-mono uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-bold">
                    {{ wizardSalaForm.codigo }}
                  </span>
                  <h3 class="text-base font-black text-slate-900 mt-1">{{ wizardSalaForm.nombre }}</h3>
                  <p class="text-xs text-slate-500">{{ wizardSalaForm.descripcion }}</p>
                </div>
                <span class="text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full">
                  {{ wizardSalaForm.tipo }} • {{ wizardSalaForm.estado }}
                </span>
              </div>

              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-slate-50 p-3.5 rounded-xl text-center">
                <div>
                  <span class="text-[10px] text-slate-400 font-bold uppercase">Superficie</span>
                  <p class="text-sm font-black text-slate-800">{{ wizardSalaForm.area_m2 }} m²</p>
                </div>
                <div>
                  <span class="text-[10px] text-slate-400 font-bold uppercase">Altura</span>
                  <p class="text-sm font-black text-slate-800">{{ wizardSalaForm.altura_m }} m</p>
                </div>
                <div>
                  <span class="text-[10px] text-slate-400 font-bold uppercase">Volumen</span>
                  <p class="text-sm font-black text-emerald-600 font-mono">{{ volumenCalculadoWizard }} m³</p>
                </div>
                <div>
                  <span class="text-[10px] text-slate-400 font-bold uppercase">Potencia Total</span>
                  <p class="text-sm font-black text-slate-800">{{ potenciaTotalWizard }} W</p>
                </div>
              </div>

              <div>
                <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                  Equipamiento Asociado ({{ wizardEquipamientos.length }})
                </h5>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                  <div v-for="eq in wizardEquipamientos" :key="eq.id" class="bg-slate-50 border border-slate-200 rounded-xl p-2.5 flex items-center justify-between">
                    <div>
                      <p class="font-bold text-slate-800">{{ eq.nombre }}</p>
                      <p class="text-[11px] text-slate-500">{{ eq.tipo }} • {{ eq.potencia_w }} W</p>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400">{{ eq.codigo_inventario }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer con Navegación de Pasos -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
          <div>
            <button
              v-if="pasoActualSala > 1"
              type="button"
              @click="pasoActualSala--"
              class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-white font-bold text-xs flex items-center gap-1.5 cursor-pointer transition-colors"
            >
              <span>←</span>
              <span>Paso Anterior</span>
            </button>
            <button
              v-else
              type="button"
              @click="modalSalaOpen = false"
              class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-white font-bold text-xs cursor-pointer transition-colors"
            >
              Cancelar
            </button>
          </div>

          <div class="flex items-center gap-2">
            <button
              v-if="pasoActualSala < 3"
              type="button"
              @click="avanzarPasoSala"
              class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-xs cursor-pointer transition-all"
            >
              <span>Siguiente Paso</span>
              <span>→</span>
            </button>
            <button
              v-else
              type="button"
              @click="finalizarGuardadoSalaWizard"
              class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 shadow-xs cursor-pointer transition-all"
            >
              <span>💾</span>
              <span>Finalizar y Crear Sala</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. MODAL DETALLE DE SALA -->
    <div v-if="modalDetalleSalaOpen && salaSeleccionada" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
      <div class="bg-white w-full max-w-xl rounded-3xl p-6 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <div>
            <span class="px-2 py-0.5 text-[10px] font-black uppercase rounded bg-emerald-100 text-emerald-800">{{ salaSeleccionada.tipo }}</span>
            <h3 class="text-base font-bold text-slate-900 mt-1">{{ salaSeleccionada.nombre }}</h3>
            <p class="text-xs text-slate-400 font-mono">{{ salaSeleccionada.codigo }}</p>
          </div>
          <button @click="modalDetalleSalaOpen = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
          <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
            <div class="text-[10px] text-slate-400 font-bold">VOLUMEN</div>
            <div class="text-sm font-black text-slate-800">{{ salaSeleccionada.volumen_m3 }} m³</div>
          </div>
          <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
            <div class="text-[10px] text-slate-400 font-bold">CAPACIDAD</div>
            <div class="text-sm font-black text-slate-800">{{ salaSeleccionada.capacidad_macetas }} plantas</div>
          </div>
          <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
            <div class="text-[10px] text-slate-400 font-bold">POTENCIA</div>
            <div class="text-sm font-black text-slate-800">{{ salaSeleccionada.potencia_luces_w }} W</div>
          </div>
          <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
            <div class="text-[10px] text-slate-400 font-bold">ESTADO</div>
            <div class="text-sm font-black text-emerald-700">{{ salaSeleccionada.estado }}</div>
          </div>
        </div>

        <div class="space-y-2">
          <div class="text-xs font-bold text-slate-700">Equipamiento Asociado</div>
          <div class="space-y-1 max-h-48 overflow-y-auto">
            <div v-for="eq in (salaSeleccionada.equipamientos || [])" :key="eq.id" class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 text-xs flex justify-between">
              <span class="font-bold text-slate-800">{{ eq.nombre }}</span>
              <span class="font-mono text-slate-600">{{ eq.potencia_w }} W</span>
            </div>
          </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end">
          <button @click="modalDetalleSalaOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">Cerrar</button>
        </div>
      </div>
    </div>

    <!-- 4. MODAL DETALLE DE CULTIVO -->
    <div v-if="modalDetalleCultivoOpen && cultivoSeleccionado" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
      <div class="bg-white w-full max-w-xl rounded-3xl p-6 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <div>
            <span class="px-2 py-0.5 text-[10px] font-black uppercase rounded bg-blue-100 text-blue-800">{{ cultivoSeleccionado.etapa }}</span>
            <h3 class="text-base font-bold text-slate-900 mt-1">{{ cultivoSeleccionado.nombre_lote }}</h3>
            <p class="text-xs text-slate-400 font-mono">{{ cultivoSeleccionado.codigo }} · {{ cultivoSeleccionado.sala_nombre }}</p>
          </div>
          <button @click="modalDetalleCultivoOpen = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
        </div>

        <div class="grid grid-cols-3 gap-3 text-center">
          <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
            <div class="text-[10px] text-slate-400 font-bold">PLANTAS TOTALES</div>
            <div class="text-sm font-black text-slate-800">{{ cultivoSeleccionado.plantas_totales }}</div>
          </div>
          <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
            <div class="text-[10px] text-slate-400 font-bold">FOTOPERÍODO</div>
            <div class="text-sm font-black text-slate-800">{{ cultivoSeleccionado.fotoperiodo }}</div>
          </div>
          <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
            <div class="text-[10px] text-slate-400 font-bold">DÍAS TOTALES</div>
            <div class="text-sm font-black text-slate-800">{{ cultivoSeleccionado.dias_totales_estimados }} días</div>
          </div>
        </div>

        <div class="space-y-2">
          <div class="text-xs font-bold text-slate-700">Variedades Genéticas en este Lote</div>
          <div class="space-y-1.5">
            <div v-for="cv in (cultivoSeleccionado.cultivo_variedades || [])" :key="cv.id" class="p-3 bg-emerald-50/50 rounded-2xl border border-emerald-200 text-xs flex justify-between items-center">
              <div>
                <strong class="text-emerald-950">{{ cv.catalogo_variedad?.nombre }}</strong>
                <div class="text-[10px] text-emerald-700">{{ cv.catalogo_variedad?.banco }} · {{ cv.descripcion }}</div>
              </div>
              <span class="px-2 py-1 bg-emerald-200 text-emerald-900 font-bold rounded-lg">{{ cv.cantidad_plantas }} plantas</span>
            </div>
          </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end">
          <button @click="modalDetalleCultivoOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">Cerrar</button>
        </div>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 4: REGISTRAR RIEGO / ACCIÓN HÍDRICA                                 -->
    <!-- ========================================================================= -->
    <div
      v-if="modalNuevoRiegoOpen"
      class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-100 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <div>
            <h3 class="text-base font-black text-slate-900">
              {{ esHidroponiaActual ? 'Registrar Acción Hidropónica RDWC' : 'Registrar Evento de Riego / Fertirriego' }}
            </h3>
            <p class="text-xs text-slate-400 font-mono">Lote: {{ cultivoHidricoActual?.nombre_lote || cultivoHidricoActual?.nombre }}</p>
          </div>
          <button @click="modalNuevoRiegoOpen = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form @submit.prevent="guardarNuevoRiego" class="space-y-4 text-xs">
          <div>
            <label class="block font-bold text-slate-700 mb-1">Tipo de Acción</label>
            <select v-model="formNuevoRiego.tipo_accion" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-medium">
              <option value="FERTIRRIGACION_NUTRIENTES">Fertirrigación Nutrientes (A+B + Aditivos)</option>
              <option value="RIEGO_AGUA">Riego Solo Agua / Lixiviación</option>
              <option value="REPOSICION_AGUA">Reposición de Agua de Ósmosis (Hidro)</option>
              <option value="CORRECCION_PH">Corrección de pH (Down / Up)</option>
              <option value="LAVADO_RAICES">Lavado de Raíces / Flush Final</option>
            </select>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Volumen Aplicado / Añadido (L)</label>
              <input v-model="formNuevoRiego.volumen_aplicado_l" type="number" step="0.1" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono font-bold" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">Volumen Drenado (L)</label>
              <input v-model="formNuevoRiego.volumen_drenado_l" type="number" step="0.1" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono font-bold" />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">EC Entrada (mS/cm)</label>
              <input v-model="formNuevoRiego.ec_entrada_ms" type="number" step="0.01" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">EC Drenaje (mS/cm)</label>
              <input v-model="formNuevoRiego.ec_drenaje_ms" type="number" step="0.01" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">pH Entrada</label>
              <input v-model="formNuevoRiego.ph_entrada" type="number" step="0.05" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">pH Drenaje</label>
              <input v-model="formNuevoRiego.ph_drenaje" type="number" step="0.05" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
            </div>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Receta de Nutrientes / Productos</label>
            <input v-model="formNuevoRiego.fertilizantes_receta" type="text" placeholder="Ej: Bloom A+B (2ml/L) + PK 13/14 (1.5ml/L)" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500" />
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Observaciones Técnicas</label>
            <textarea v-model="formNuevoRiego.observaciones" rows="2" placeholder="Detalles de homogeneidad, comportamiento de sustrato..." class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500"></textarea>
          </div>

          <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
            <button type="button" @click="modalNuevoRiegoOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold">Cancelar</button>
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-xs">Guardar Evento</button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 5: CONFIGURACIÓN AGRONÓMICA HÍDRICA                                 -->
    <!-- ========================================================================= -->
    <div
      v-if="modalConfigHidricoOpen"
      class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
    >
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-100 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <div>
            <h3 class="text-base font-black text-slate-900">⚙️ Configuración Agronómica Hídrica</h3>
            <p class="text-xs text-slate-400">Parámetros objetivo para {{ cultivoHidricoActual?.nombre_lote }}</p>
          </div>
          <button @click="modalConfigHidricoOpen = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form @submit.prevent="guardarConfiguracionRiego" class="space-y-4 text-xs">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Estrategia de Riego</label>
              <input v-model="configHidricaActual.estrategia" type="text" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-medium" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">Tipo de Sustrato</label>
              <input v-model="configHidricaActual.tipo_sustrato" type="text" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-medium" />
            </div>
          </div>

          <div v-if="!esHidroponiaActual" class="space-y-3">
            <div class="grid grid-cols-3 gap-2">
              <div>
                <label class="block font-bold text-slate-700 mb-1">VWC Mín (%)</label>
                <input v-model="configHidricaActual.vwc_minimo_pct" type="number" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">VWC Obj (%)</label>
                <input v-model="configHidricaActual.vwc_objetivo_pct" type="number" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono font-bold" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">VWC Máx (%)</label>
                <input v-model="configHidricaActual.vwc_maximo_pct" type="number" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block font-bold text-slate-700 mb-1">Drenaje Objetivo (%)</label>
                <input v-model="configHidricaActual.drenaje_objetivo_pct" type="number" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">EC Entrada Obj (mS/cm)</label>
                <input v-model="configHidricaActual.ec_entrada_objetivo_ms" type="number" step="0.05" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block font-bold text-slate-700 mb-1">Pulsos Diarios Sugeridos</label>
                <input v-model="configHidricaActual.pulsos_diarios_sugeridos" type="number" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">Volumen por Pulso (ml)</label>
                <input v-model="configHidricaActual.volumen_por_pulso_ml" type="number" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
              </div>
            </div>
          </div>

          <div v-else class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block font-bold text-slate-700 mb-1">Reservorio Nominal (L)</label>
                <input v-model="configHidricaActual.reservorio_volumen_nominal_l" type="number" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">EC Setpoint (mS/cm)</label>
                <input v-model="configHidricaActual.ec_hidro_objetivo_ms" type="number" step="0.05" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block font-bold text-slate-700 mb-1">pH Objetivo</label>
                <input v-model="configHidricaActual.ph_hidro_objetivo" type="number" step="0.05" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">Temp. Chiller (°C)</label>
                <input v-model="configHidricaActual.temperatura_solucion_actual_c" type="number" step="0.1" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-emerald-500 font-mono" />
              </div>
            </div>
          </div>

          <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
            <button type="button" @click="modalConfigHidricoOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold">Cerrar</button>
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-xs">Guardar Cambios</button>
          </div>
        </form>
      </div>
    </div>

    <!-- TOAST NOTIFICATION FLOATING -->
    <div
      v-if="mensajeToast"
      class="fixed bottom-6 right-6 z-50 px-4 py-3 bg-slate-900 text-white rounded-2xl shadow-2xl border border-slate-700 text-xs font-bold flex items-center gap-2 animate-in slide-in-from-bottom duration-200"
    >
      <span class="text-emerald-400">✓</span>
      <span>{{ mensajeToast }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';

// Safe imports o fallback data integrado
import {
  MOCK_ORGANIZACIONES,
  MOCK_ROLES,
  MOCK_SALAS,
  MOCK_CULTIVOS,
  MOCK_PLANIFICACIONES,
  MOCK_EVENTOS,
  MOCK_REGISTROS,
  DATOS_CLIMA_24H,
  DISTRIBUCION_GENETICAS,
  DATOS_GRAFICA_VWC_24H,
  DATOS_GRAFICA_DRENAJE_EC,
  DATOS_GRAFICA_HIDRO_24H,
  DATOS_GRAFICA_HIDRO_7DIAS,
  MOCK_CONFIGURACIONES_RIEGO,
  MOCK_EVENTOS_RIEGO,
  MOCK_RECOMENDACIONES_RIEGO,
} from '../mocks/mockData';

// Props de Inertia Laravel (con valores mockeados por defecto para que funcione siempre)
const props = withDefaults(
  defineProps<{
    organizaciones?: any[];
    roles?: any[];
    salas?: any[];
    cultivos?: any[];
    planificaciones?: any[];
    eventos?: any[];
    registros?: any[];
    clima24h?: any[];
    distribucionGenetica?: any[];
    vwc24h?: any[];
    drenajeEc?: any[];
    hidro24h?: any[];
    hidro7dias?: any[];
    configuracionesRiego?: Record<string, any>;
    eventosRiego?: any[];
    recomendacionesRiego?: any[];
    user?: any;
  }>(),
  {
    organizaciones: () => MOCK_ORGANIZACIONES,
    roles: () => MOCK_ROLES,
    salas: () => MOCK_SALAS,
    cultivos: () => MOCK_CULTIVOS,
    planificaciones: () => MOCK_PLANIFICACIONES,
    eventos: () => MOCK_EVENTOS,
    registros: () => MOCK_REGISTROS,
    clima24h: () => DATOS_CLIMA_24H,
    distribucionGenetica: () => DISTRIBUCION_GENETICAS,
    vwc24h: () => DATOS_GRAFICA_VWC_24H,
    drenajeEc: () => DATOS_GRAFICA_DRENAJE_EC,
    hidro24h: () => DATOS_GRAFICA_HIDRO_24H,
    hidro7dias: () => DATOS_GRAFICA_HIDRO_7DIAS,
    configuracionesRiego: () => MOCK_CONFIGURACIONES_RIEGO,
    eventosRiego: () => MOCK_EVENTOS_RIEGO,
    recomendacionesRiego: () => MOCK_RECOMENDACIONES_RIEGO,
    user: () => ({
      id: 'usr-01',
      name: 'Ing. Juan Pérez',
      email: 'juan.perez@cultivoos.internal',
      role: 'Master Grower / Director Agrónomo',
    }),
  }
);

// Estado de Navegación y Vistas
export type TipoVista = 'DASHBOARD' | 'PLANIFICACIONES' | 'GESTION_HIDRICA' | 'SALAS';

const vistaActiva = ref<TipoVista>('DASHBOARD');

export interface TabVista {
  id: TipoVista;
  label: string;
  icon: string;
  badge?: string;
}

const tabsVistas: TabVista[] = [
  { id: 'DASHBOARD', label: 'General & Cultivos', icon: '📊' },
  { id: 'PLANIFICACIONES', label: 'TimeLine & Tasks', icon: '📅', badge: '4' },
  { id: 'GESTION_HIDRICA', label: 'Gestión Hídrica', icon: '💧' },
  { id: 'SALAS', label: 'Salas & Equipos', icon: '🏢' },
];

const cambiarVista = (tabId: TipoVista) => {
  vistaActiva.value = tabId;
};

// Estado de Organizaciones y Multi-tenancy
const listaOrganizaciones = ref([...(props.organizaciones || MOCK_ORGANIZACIONES)]);
const listaRoles = ref([...(props.roles || MOCK_ROLES)]);
const orgActiva = ref(listaOrganizaciones.value[0] || MOCK_ORGANIZACIONES[0]);
const rolActivo = ref(listaRoles.value[0] || MOCK_ROLES[0]);
const menuOrgOpen = ref(false);
const modalNuevaOrgOpen = ref(false);

const formOrg = ref({
  nombre: '',
  cuit_rut: '',
  direccion: '',
  plan: 'PRO',
});

const toggleMenuOrg = () => {
  menuOrgOpen.value = !menuOrgOpen.value;
  if (menuOrgOpen.value) menuUsuarioOpen.value = false;
};

const seleccionarOrganizacion = (org: any) => {
  orgActiva.value = org;
  menuOrgOpen.value = false;
  mostrarToast('Organización cambiada a: ' + org.nombre);
};

const abrirModalNuevaOrg = () => {
  menuOrgOpen.value = false;
  modalNuevaOrgOpen.value = true;
};

const guardarNuevaOrganizacion = () => {
  const nueva = {
    id: 'org-' + Date.now(),
    nombre: formOrg.value.nombre,
    slug: formOrg.value.nombre.toLowerCase().replace(/s+/g, '-'),
    cuit_rut: formOrg.value.cuit_rut,
    direccion: formOrg.value.direccion,
    plan: formOrg.value.plan as any,
    activa: true,
  };
  listaOrganizaciones.value.push(nueva);
  orgActiva.value = nueva;
  modalNuevaOrgOpen.value = false;
  formOrg.value = { nombre: '', cuit_rut: '', direccion: '', plan: 'PRO' };
  mostrarToast('Organización "' + nueva.nombre + '" creada y seleccionada');
};

// Estado de Usuario y Perfil
const usuarioActual = ref({ ...(props.user || {}) });
const menuUsuarioOpen = ref(false);

const toggleMenuUsuario = () => {
  menuUsuarioOpen.value = !menuUsuarioOpen.value;
  if (menuUsuarioOpen.value) menuOrgOpen.value = false;
};

const seleccionarRol = (rol: any) => {
  rolActivo.value = rol;
  menuUsuarioOpen.value = false;
  mostrarToast('Rol conmutado a: ' + rol.nombre);
};

// Estados Reactivos Principales
const listaSalas = ref([...(props.salas || MOCK_SALAS)]);
const listaCultivos = ref([...(props.cultivos || MOCK_CULTIVOS)]);
const listaPlanificaciones = ref([...(props.planificaciones || MOCK_PLANIFICACIONES)]);
const listaEventos = ref([...(props.eventos || MOCK_EVENTOS)]);
const listaRegistros = ref([...(props.registros || MOCK_REGISTROS)]);
const datosClima24h = ref([...(props.clima24h || DATOS_CLIMA_24H)]);
const distribucionGenetica = ref([...(props.distribucionGenetica || DISTRIBUCION_GENETICAS)]);

// Estados Reactivos de Gestión Hídrica & Fertirriego
const cultivoHidricoSeleccionadoId = ref('cultivo-01');
const modalConfigHidricoOpen = ref(false);
const modalNuevoRiegoOpen = ref(false);
const filtroTipoEventoRiego = ref('TODOS');

const listaConfiguracionesRiego = ref<Record<string, any>>({ ...(props.configuracionesRiego || MOCK_CONFIGURACIONES_RIEGO) });
const listaEventosRiego = ref([...(props.eventosRiego || MOCK_EVENTOS_RIEGO)]);
const listaRecomendacionesRiego = ref([...(props.recomendacionesRiego || MOCK_RECOMENDACIONES_RIEGO)]);
const datosVwc24h = ref([...(props.vwc24h || DATOS_GRAFICA_VWC_24H)]);
const datosDrenajeEc = ref([...(props.drenajeEc || DATOS_GRAFICA_DRENAJE_EC)]);
const datosHidro24h = ref([...(props.hidro24h || DATOS_GRAFICA_HIDRO_24H)]);
const datosHidro7dias = ref([...(props.hidro7dias || DATOS_GRAFICA_HIDRO_7DIAS)]);

const cultivoHidricoActual = computed(() => {
  return listaCultivos.value.find(c => c.id === cultivoHidricoSeleccionadoId.value) || listaCultivos.value[0];
});

const esHidroponiaActual = computed(() => {
  return cultivoHidricoActual.value?.tipo_sistema_cultivo === 'HIDROPONIA' ||
    listaConfiguracionesRiego.value[cultivoHidricoSeleccionadoId.value]?.tipo_sistema === 'HIDROPONIA';
});

const configHidricaActual = computed(() => {
  return listaConfiguracionesRiego.value[cultivoHidricoSeleccionadoId.value] || listaConfiguracionesRiego.value['cultivo-01'] || {};
});

const eventosRiegoFiltrados = computed(() => {
  const evts = listaEventosRiego.value.filter(e => e.cultivo_id === cultivoHidricoSeleccionadoId.value);
  if (filtroTipoEventoRiego.value === 'TODOS') return evts;
  if (filtroTipoEventoRiego.value === 'FERTIRRIEGO') return evts.filter(e => e.tipo_accion.includes('FERTIRR') || e.tipo_accion.includes('NUTRI'));
  if (filtroTipoEventoRiego.value === 'AGUA / LIXIVIACIÓN') return evts.filter(e => e.tipo_accion.includes('AGUA') || e.tipo_accion.includes('LAVADO') || e.tipo_accion.includes('REPOSICION'));
  if (filtroTipoEventoRiego.value === 'CORRECCIÓN / CALIBRACIÓN') return evts.filter(e => e.tipo_accion.includes('PH') || e.tipo_accion.includes('CALIB') || e.tipo_accion.includes('CORRECC'));
  return evts;
});

const recomendacionesRiegoCultivo = computed(() => {
  return listaRecomendacionesRiego.value.filter(r => r.cultivo_id === cultivoHidricoSeleccionadoId.value);
});

// Gráficos SVG para Gestión Hídrica
const puntosVwcSvg = computed(() => {
  const data = datosVwc24h.value;
  const width = 700;
  const height = 160;
  const padding = 25;
  const minVwc = 40;
  const maxVwc = 80;

  return data.map((d: any, index: number) => {
    const x = padding + (index / (data.length - 1)) * (width - padding * 2);
    const normalized = (d.vwc - minVwc) / (maxVwc - minVwc);
    const y = height - (normalized * (height - padding * 2) + padding);
    return { x, y, vwc: d.vwc, hora: d.hora };
  });
});

const svgPathVwc = computed(() => {
  const pts = puntosVwcSvg.value;
  if (!pts.length) return '';
  return pts.reduce((acc, p, i) => (i === 0 ? ('M ' + p.x + ' ' + p.y) : (acc + ' L ' + p.x + ' ' + p.y)), '');
});

const puntosHidroSvg = computed(() => {
  const data = datosHidro24h.value;
  const width = 700;
  const height = 160;
  const padding = 25;
  const minL = 120;
  const maxL = 160;

  return data.map((d: any, index: number) => {
    const x = padding + (index / (data.length - 1)) * (width - padding * 2);
    const normalized = (d.nivelL - minL) / (maxL - minL);
    const y = height - (normalized * (height - padding * 2) + padding);
    return { x, y, nivelL: d.nivelL, hora: d.hora };
  });
});

const svgPathHidroNivel = computed(() => {
  const pts = puntosHidroSvg.value;
  if (!pts.length) return '';
  return pts.reduce((acc, p, i) => (i === 0 ? ('M ' + p.x + ' ' + p.y) : (acc + ' L ' + p.x + ' ' + p.y)), '');
});

// Formulario Nuevo Riego
const formNuevoRiego = ref({
  tipo_accion: 'FERTIRRIGACION_NUTRIENTES',
  volumen_aplicado_l: 21.6,
  volumen_drenado_l: 3.9,
  ec_entrada_ms: 1.95,
  ec_drenaje_ms: 2.18,
  ph_entrada: 5.95,
  ph_drenaje: 6.10,
  fertilizantes_receta: 'Bloom A+B (2ml/L) + PK 13/14 (1.5ml/L)',
  observaciones: 'Aplicación matutina controlada.',
});

const abrirModalNuevoRiego = () => {
  if (esHidroponiaActual.value) {
    formNuevoRiego.value.tipo_accion = 'REPOSICION_AGUA';
    formNuevoRiego.value.fertilizantes_receta = 'Agua de ósmosis para compensar evapotranspiración';
    formNuevoRiego.value.volumen_aplicado_l = 13.5;
  } else {
    formNuevoRiego.value.tipo_accion = 'FERTIRRIGACION_NUTRIENTES';
    formNuevoRiego.value.volumen_aplicado_l = 21.6;
  }
  modalNuevoRiegoOpen.value = true;
};

const guardarNuevoRiego = () => {
  const pctDrenaje = formNuevoRiego.value.volumen_aplicado_l > 0 && formNuevoRiego.value.volumen_drenado_l
    ? (formNuevoRiego.value.volumen_drenado_l / formNuevoRiego.value.volumen_aplicado_l) * 100
    : 0;

  const nuevoEvento = {
    id: 'ev-riego-' + Date.now(),
    cultivo_id: cultivoHidricoSeleccionadoId.value,
    sistema_tipo: esHidroponiaActual.value ? 'HIDROPONIA' : 'SUSTRATO',
    fecha_hora: new Date().toISOString().replace('T', ' ').slice(0, 19),
    tipo_accion: formNuevoRiego.value.tipo_accion,
    estrategia_utilizada: esHidroponiaActual.value ? 'Manejo Solución RDWC' : 'Micro-pulsos VWC',
    volumen_programado_l: Number(formNuevoRiego.value.volumen_aplicado_l),
    volumen_aplicado_l: Number(formNuevoRiego.value.volumen_aplicado_l),
    volumen_drenado_l: Number(formNuevoRiego.value.volumen_drenado_l) || 0,
    porcentaje_drenaje_calculado: pctDrenaje,
    ec_entrada_ms: Number(formNuevoRiego.value.ec_entrada_ms) || undefined,
    ec_drenaje_ms: Number(formNuevoRiego.value.ec_drenaje_ms) || undefined,
    ph_entrada: Number(formNuevoRiego.value.ph_entrada) || undefined,
    ph_drenaje: Number(formNuevoRiego.value.ph_drenaje) || undefined,
    fertilizantes_receta: formNuevoRiego.value.fertilizantes_receta,
    estado: 'COMPLETADO',
    observaciones: formNuevoRiego.value.observaciones,
  };

  listaEventosRiego.value.unshift(nuevoEvento);
  modalNuevoRiegoOpen.value = false;
  mostrarToast('✓ Evento de riego registrado correctamente');
};

const atenderRecomendacionRiego = (recId: string) => {
  const rec = listaRecomendacionesRiego.value.find(r => r.id === recId);
  if (rec) {
    rec.atendida = !rec.atendida;
    mostrarToast(rec.atendida ? 'Recomendación marcada como revisada' : 'Recomendación desmarcada');
  }
};

const toggleModoRiegoAuto = () => {
  const cfg = configHidricaActual.value;
  if (cfg) {
    cfg.modo_automatico = !cfg.modo_automatico;
    mostrarToast(cfg.modo_automatico ? '⚡ Auto-Riego reactivado' : '⏸ Auto-Riego pausado manualmente');
  }
};

const guardarConfiguracionRiego = () => {
  modalConfigHidricoOpen.value = false;
  mostrarToast('✓ Configuración agronómica hídrica guardada');
};

// Modales y Detalles
const menuNuevoOpen = ref(false);
const modalSalaOpen = ref(false);
const modalDetalleSalaOpen = ref(false);
const modalDetalleCultivoOpen = ref(false);
const salaSeleccionada = ref<any>(null);
const cultivoSeleccionado = ref<any>(null);

// Estado de Wizard Nueva Sala (3 Pasos de Precisión)
const pasoActualSala = ref<1 | 2 | 3>(1);

const wizardSalaForm = ref({
  organizacion_id: orgActiva.value.id,
  nombre: 'Sala Épsilon - Floración #3',
  codigo: 'SALA-FLORA-03',
  tipo: 'FLORACION',
  estado: 'ACTIVA',
  area_m2: 22.0,
  altura_m: 2.8,
  capacidad_macetas: 40,
  posee_co2: true,
  posee_extraccion: true,
  posee_intraccion: true,
  posee_ins_agua: true,
  descripcion: 'Sala optimizada para ciclo de floración con control ambiental de precisión y fertirriego.',
});

const wizardEquipamientos = ref<any[]>([
  {
    id: 'eq-init-1',
    nombre: 'Array LED Samsung LM301H (6x 680W)',
    codigo_inventario: 'EQ-LED-FLORA-03',
    tipo: 'ILUMINACION',
    marca_modelo: 'Gavita Pro 1700e',
    potencia_w: 4080,
    tipo_luminaria: 'LED',
    espectro: 'Full Spectrum + Far Red',
    dimmer_pct: 100,
  },
  {
    id: 'eq-init-2',
    nombre: 'Aire Acondicionado Inverter 24000 BTU',
    codigo_inventario: 'EQ-AC-FLORA-03',
    tipo: 'CLIMATIZACION',
    marca_modelo: 'Carrier XPower Inverter',
    potencia_w: 2200,
    btu: 24000,
    es_split: true,
    funcion_calor: true,
  },
]);

const tiposEquipamientoCatalog = [
  { tipo: 'ILUMINACION', label: 'Luminaria', icon: '⚡' },
  { tipo: 'CLIMATIZACION', label: 'Aire Acond.', icon: '❄️' },
  { tipo: 'INYECCION_CO2', label: 'CO₂', icon: '💨' },
  { tipo: 'VENTILADOR', label: 'Ventilador', icon: '🌀' },
  { tipo: 'HUMIDIFICADOR', label: 'Humidificador', icon: '💧' },
  { tipo: 'DESHUMIDIFICADOR', label: 'Deshumid.', icon: '🌪️' },
  { tipo: 'RIEGO_GOTEO', label: 'Riego Auto', icon: '🚿' },
];

const subFormTipo = ref('ILUMINACION');
const editandoEqId = ref<string | null>(null);
const subFormEq = ref<any>({
  nombre: 'Panel LED Full Spectrum 680W',
  codigo_inventario: 'LUM-EPS-02',
  marca_modelo: 'Fluence SPYDR 2p',
  potencia_w: 680,
  tipo_luminaria: 'LED',
  espectro: '3000K + 5000K + 660nm',
  dimmer_pct: 100,
  btu: 18000,
  es_split: true,
  funcion_calor: true,
});

const volumenCalculadoWizard = computed(() => {
  const area = Number(wizardSalaForm.value.area_m2) || 0;
  const alt = Number(wizardSalaForm.value.altura_m) || 0;
  return (area * alt).toFixed(1);
});

const potenciaTotalWizard = computed(() => {
  return wizardEquipamientos.value.reduce((acc, eq) => acc + (Number(eq.potencia_w) || 0), 0);
});

const agregarOActualizarEquipo = () => {
  if (!subFormEq.value.nombre) return;

  if (editandoEqId.value) {
    const idx = wizardEquipamientos.value.findIndex(e => e.id === editandoEqId.value);
    if (idx !== -1) {
      wizardEquipamientos.value[idx] = {
        ...wizardEquipamientos.value[idx],
        ...subFormEq.value,
        tipo: subFormTipo.value,
      };
    }
    editandoEqId.value = null;
  } else {
    wizardEquipamientos.value.push({
      id: 'eq-' + Date.now(),
      nombre: subFormEq.value.nombre,
      codigo_inventario: subFormEq.value.codigo_inventario || ('EQ-' + (wizardEquipamientos.value.length + 1).toString().padStart(2, '0')),
      tipo: subFormTipo.value,
      marca_modelo: subFormEq.value.marca_modelo || '',
      potencia_w: Number(subFormEq.value.potencia_w) || 0,
      tipo_luminaria: subFormEq.value.tipo_luminaria,
      espectro: subFormEq.value.espectro,
      dimmer_pct: subFormEq.value.dimmer_pct,
      btu: subFormEq.value.btu,
      es_split: subFormEq.value.es_split,
      funcion_calor: subFormEq.value.funcion_calor,
    });
  }

  subFormEq.value = {
    nombre: '',
    codigo_inventario: '',
    marca_modelo: '',
    potencia_w: 680,
  };
  mostrarToast('Equipamiento actualizado en la sala');
};

const editarEquipoWizard = (eq: any) => {
  editandoEqId.value = eq.id;
  subFormTipo.value = eq.tipo;
  subFormEq.value = { ...eq };
};

const eliminarEquipoWizard = (id: string) => {
  wizardEquipamientos.value = wizardEquipamientos.value.filter(e => e.id !== id);
  if (editandoEqId.value === id) {
    editandoEqId.value = null;
  }
  mostrarToast('Equipo removido de la sala');
};

const avanzarPasoSala = () => {
  if (pasoActualSala.value === 1) {
    if (!wizardSalaForm.value.nombre.trim()) {
      mostrarToast('Por favor ingrese un nombre para la sala');
      return;
    }
    pasoActualSala.value = 2;
  } else if (pasoActualSala.value === 2) {
    pasoActualSala.value = 3;
  }
};

const finalizarGuardadoSalaWizard = () => {
  const volumen = parseFloat(volumenCalculadoWizard.value);
  const totalWatts = potenciaTotalWizard.value || 3600;

  const nuevaSala = {
    id: 'sala-' + Date.now(),
    organizacion_id: wizardSalaForm.value.organizacion_id || orgActiva.value.id,
    nombre: wizardSalaForm.value.nombre,
    codigo: wizardSalaForm.value.codigo,
    tipo: wizardSalaForm.value.tipo,
    estado: wizardSalaForm.value.estado,
    area: Number(wizardSalaForm.value.area_m2),
    altura: Number(wizardSalaForm.value.altura_m),
    ancho_m: Math.sqrt(Number(wizardSalaForm.value.area_m2) * 0.75).toFixed(1),
    largo_m: (Number(wizardSalaForm.value.area_m2) / 4.0).toFixed(1),
    volumen_m3: volumen,
    capacidad_macetas: Number(wizardSalaForm.value.capacidad_macetas) || 40,
    potencia_luces_w: totalWatts,
    temperatura_c: 24.2,
    humedad_pct: 52,
    vpd_kpa: 1.22,
    co2_ppm: wizardSalaForm.value.posee_co2 ? 1150 : 450,
    posee_co2: wizardSalaForm.value.posee_co2,
    posee_extraccion: wizardSalaForm.value.posee_extraccion,
    posee_intraccion: wizardSalaForm.value.posee_intraccion,
    posee_ins_agua: wizardSalaForm.value.posee_ins_agua,
    descripcion: wizardSalaForm.value.descripcion,
    equipamientos: [...wizardEquipamientos.value],
  };

  listaSalas.value.unshift(nuevaSala);
  modalSalaOpen.value = false;
  pasoActualSala.value = 1;
  mostrarToast('Sala "' + nuevaSala.nombre + '" creada con éxito');
};

const abrirModal = (tipo: string) => {
  menuNuevoOpen.value = false;
  if (tipo === 'SALA') {
    pasoActualSala.value = 1;
    modalSalaOpen.value = true;
  }
  else if (tipo === 'CULTIVO') mostrarToast('Abriendo formulario de nuevo cultivo');
  else if (tipo === 'PLANIFICACION') mostrarToast('Abriendo gestor de planificaciones');
  else if (tipo === 'TASK') mostrarToast('Nueva tarea añadida');
  else if (tipo === 'EVENTO') mostrarToast('Nuevo evento agronómico');
  else if (tipo === 'REGISTRO') simularTelemetriaIoT();
};

const abrirDetalleSala = (sala: any) => {
  salaSeleccionada.value = sala;
  modalDetalleSalaOpen.value = true;
};

const abrirDetalleCultivo = (cultivo: any) => {
  cultivoSeleccionado.value = cultivo;
  modalDetalleCultivoOpen.value = true;
};

// Toggle de tareas y eventos
const toggleTask = (planId: string, taskId: string) => {
  listaPlanificaciones.value = listaPlanificaciones.value.map(p => {
    if (p.id === planId) {
      const updated = (p.tasks || []).map((t: { id: string; completada: any; }) => t.id === taskId ? { ...t, completada: !t.completada } : t);
      return { ...p, tasks: updated };
    }
    return p;
  });
  mostrarToast('Estado de tarea actualizado');
};

const toggleEstadoEvento = (evt: any) => {
  evt.estado_evento = evt.estado_evento?.nombre === 'REALIZADO' ? { nombre: 'PENDIENTE' } : { nombre: 'REALIZADO' };
  mostrarToast('Evento marcado como ' + evt.estado_evento.nombre);
};

// Simulación de Telemetría IoT en vivo
const simularTelemetriaIoT = () => {
  const tempRandom = +(23.5 + Math.random() * 2.5).toFixed(1);
  const hrRandom = +(48 + Math.random() * 8).toFixed(1);
  const vpdRandom = +(1.15 + Math.random() * 0.25).toFixed(2);
  const co2Random = Math.round(1000 + Math.random() * 300);

  const nuevoRegistro = {
    id: 'reg-' + Date.now(),
    nombre: 'Telemetría IoT en Vivo (' + new Date().toLocaleTimeString().slice(0, 5) + ')',
    fecha_registro: new Date().toISOString().replace('T', ' ').slice(0, 16),
    descripcion: 'Lectura automatizada por estación de sondas ambientales.',
    temperatura: tempRandom,
    humedad: hrRandom,
    vpd: vpdRandom,
    co2: co2Random,
  };
  listaRegistros.value.unshift(nuevoRegistro);
  mostrarToast('⚡ Telemetría IoT recibida: VPD ' + vpdRandom + ' kPa, CO2 ' + co2Random + ' ppm');
};

// Toast
const mensajeToast = ref<string | null>(null);
const mostrarToast = (msg: string) => {
  mensajeToast.value = msg;
  setTimeout(() => {
    mensajeToast.value = null;
  }, 3500);
};

// Métricas Computadas
const totalSalasActivas = computed(() => listaSalas.value.filter(s => s.estado === 'ACTIVA').length);
const totalPlantas = computed(() => listaCultivos.value.reduce((acc, c) => acc + (c.plantas_totales || 0), 0));
const promedioVPD = computed(() => {
  if (!listaSalas.value.length) return '1.25';
  const sum = listaSalas.value.reduce((acc, s) => acc + (s.vpd_kpa || 1.2), 0);
  return (sum / listaSalas.value.length).toFixed(2);
});
const tareasPendientesCount = computed(() => {
  return listaPlanificaciones.value.flatMap(p => p.tasks || []).filter(t => !t.completada).length;
});

// Telemetría 24h & Generador SVG
const metricaActiva = ref<'temp' | 'hum' | 'vpd' | 'co2'>('temp');

const configMetricas = {
  temp: { color: '#10b981', unidad: '°C', min: 18, max: 30, key: 'temp' },
  hum: { color: '#3b82f6', unidad: '%', min: 30, max: 80, key: 'hum' },
  vpd: { color: '#f59e0b', unidad: ' kPa', min: 0.8, max: 1.8, key: 'vpd' },
  co2: { color: '#8b5cf6', unidad: ' ppm', min: 400, max: 1500, key: 'co2' },
};

const configMetricaActual = computed(() => configMetricas[metricaActiva.value]);

const puntosSvg = computed(() => {
  const data = datosClima24h.value;
  const cfg = configMetricaActual.value;
  const width = 700;
  const height = 180;
  const padding = 20;

  return data.map((d, index) => {
    const x = padding + (index / (data.length - 1)) * (width - padding * 2);
    const val = (d as any)[cfg.key] || 0;
    const normalized = (val - cfg.min) / (cfg.max - cfg.min);
    const y = height - (normalized * (height - padding * 2) + padding);
    return { x, y, valor: val, hora: d.hora };
  });
});

const svgLinePoints = computed(() => {
  return puntosSvg.value.map(p => (p.x + ',' + p.y)).join(' ');
});

const svgAreaPoints = computed(() => {
  const pts = puntosSvg.value;
  if (!pts.length) return '';
  const firstX = pts[0].x;
  const lastX = pts[pts.length - 1].x;
  const bottomY = 180;
  return firstX + ',' + bottomY + ' ' + svgLinePoints.value + ' ' + lastX + ',' + bottomY;
});

const valorMinimoMetrica = computed(() => {
  const vals = datosClima24h.value.map(d => (d as any)[configMetricaActual.value.key]);
  return Math.min(...vals);
});

const valorMaximoMetrica = computed(() => {
  const vals = datosClima24h.value.map(d => (d as any)[configMetricaActual.value.key]);
  return Math.max(...vals);
});

const valorPromedioMetrica = computed(() => {
  const vals = datosClima24h.value.map(d => (d as any)[configMetricaActual.value.key]);
  const sum = vals.reduce((a, b) => a + b, 0);
  return (sum / vals.length).toFixed(1);
});
</script>
