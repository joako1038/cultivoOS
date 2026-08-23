<!-- =========================================================================
     CULTIVOOS - GESTIÓN MULTI-TENANCY DE ORGANIZACIONES Y ROLES
     Archivo: resources/js/Pages/Organizaciones/Index.vue
     Compatibilidad: Vue 3 (Script Setup TS) + Inertia.js + Tailwind CSS
     Eloquent Models: App\Models\Organizacion, App\Models\Rol, App\Models\UsuarioOrganizacion
========================================================================= -->
<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

// --- Interfaces TypeScript (Espejo de Models Eloquent Laravel) ---
export interface Rol {
  id: string;
  nombre: string;
  descripcion: string;
}

export interface Usuario {
  id: string;
  nombre: string;
  name: string;
  email: string;
}

export interface UsuarioOrganizacion {
  id: string;
  usuario_id: string;
  organizacion_id: string;
  rol_id: string;
  es_activo?: boolean;
  usuario?: Usuario;
  rol?: Rol;
}

export interface Sala {
  id: string;
  nombre: string;
  codigo: string;
  tipo: string;
  estado: string;
  area?: number;
  volumen?: number;
}

export interface Organizacion {
  id: string;
  nombre: string;
  descripcion: string;
  localizacion: string;
  created_at?: string;
  salas?: Sala[];
  usuarios_organizaciones?: UsuarioOrganizacion[];
}

// --- Props de Inertia enviados por OrganizacionController@index ---
const props = defineProps<{
  organizaciones: Organizacion[];
  organizacionActivaId?: string;
  roles: Rol[];
  usuarioActual: Usuario;
  flash?: {
    success?: string;
    error?: string;
  };
}>();

// --- Estados Reactivos Locales ---
const modalCrearOpen = ref(false);
const orgSeleccionadaParaDetalle = ref<Organizacion | null>(null);
const filtroBusqueda = ref('');

// --- Formulario Inertia para Crear Nueva Organización ---
const form = useForm({
  nombre: '',
  descripcion: '',
  localizacion: '',
  rol_id: props.roles[0]?.id || 'rol-01',
});

// Presets de plantilla rápida para testing agronómico
const presets = [
  {
    nombre: 'CultivoOS Master Facility Pilar',
    descripcion: 'Instalación central de floración en interior y laboratorio biotecnológico.',
    localizacion: 'Parque Industrial Tecnológico Nave 4, Buenos Aires',
  },
  {
    nombre: 'Andes Research & Breeding Center',
    descripcion: 'Centro de preservación de genéticas puras y cultivo experimental.',
    localizacion: 'Valle de Uco, Mendoza, Argentina',
  },
  {
    nombre: 'Green Valley Light-Dep Greenhouse',
    descripcion: 'Invernadero automatizado con privación lumínica y suplemento LED.',
    localizacion: 'Sector Agrícola Ruta 8, Santa Fe',
  },
];

const aplicarPreset = (p: typeof presets[0]) => {
  form.nombre = p.nombre;
  form.descripcion = p.descripcion;
  form.localizacion = p.localizacion;
};

// Guardar nueva Organización (POST /organizaciones)
const submitCrearOrganizacion = () => {
  form.post(route('organizaciones.store'), {
    preserveScroll: true,
    onSuccess: () => {
      modalCrearOpen.value = false;
      form.reset();
    },
  });
};

// Cambiar la Organización Activa en la sesión (POST /organizaciones/switch-activa)
const switchOrganizacionActiva = (orgId: string) => {
  router.post(
    route('organizaciones.switch-activa'),
    { organizacion_id: orgId },
    {
      preserveScroll: true,
      preserveState: true,
    }
  );
};

// Lista filtrada
const organizacionesFiltradas = computed(() => {
  if (!filtroBusqueda.value.trim()) return props.organizaciones;
  const q = filtroBusqueda.value.toLowerCase();
  return props.organizaciones.filter(
    (o) =>
      o.nombre.toLowerCase().includes(q) ||
      o.localizacion.toLowerCase().includes(q) ||
      o.descripcion.toLowerCase().includes(q)
  );
});
</script>

<template>
  <AppLayout title="Organizaciones & Multi-Tenancy">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
      
      <!-- HEADER PRINCIPAL -->
      <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div className="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-600 mb-1">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Multi-Tenancy & Jerarquía Agrícola
          </div>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
            <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Organizaciones & Instalaciones
          </h1>
          <p class="text-xs text-slate-500 mt-1">
            Gestione sus instalaciones físicas, salas asociadas y miembros con control de acceso basado en roles (RBAC).
          </p>
        </div>

        <div class="flex items-center gap-3">
          <button
            type="button"
            @click="modalCrearOpen = true"
            class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-2 shadow-sm shadow-emerald-600/30 transition-all cursor-pointer"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            + Nueva Organización
          </button>
        </div>
      </div>

      <!-- BUSCADOR Y ESTADÍSTICAS RÁPIDAS -->
      <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="relative w-full sm:w-80">
          <input
            v-model="filtroBusqueda"
            type="text"
            placeholder="Buscar por nombre o localización..."
            class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-xs"
          />
          <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>

        <div class="text-xs text-slate-500 font-semibold flex items-center gap-2">
          <span>Mostrando <strong>{{ organizacionesFiltradas.length }}</strong> organizaciones registradas</span>
        </div>
      </div>

      <!-- GRID DE ORGANIZACIONES -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div
          v-for="org in organizacionesFiltradas"
          :key="org.id"
          :class="[
            'bg-white rounded-3xl p-5 border transition-all flex flex-col justify-between group relative shadow-xs hover:shadow-md',
            org.id === props.organizacionActivaId
              ? 'border-emerald-500 ring-2 ring-emerald-500/20'
              : 'border-slate-200 hover:border-slate-300'
          ]"
        >
          <!-- Badge de Organización Activa -->
          <div v-if="org.id === props.organizacionActivaId" class="absolute -top-3 right-6 bg-emerald-600 text-white text-[10px] font-bold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
            Entorno Activo
          </div>

          <div>
            <div class="flex items-start gap-3">
              <div class="w-11 h-11 rounded-2xl bg-slate-900 text-emerald-400 flex items-center justify-center font-bold shrink-0 shadow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>

              <div class="min-w-0">
                <h3 class="text-base font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors truncate">
                  {{ org.nombre }}
                </h3>
                <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5 truncate">
                  <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  {{ org.localizacion }}
                </p>
              </div>
            </div>

            <p class="text-xs text-slate-600 mt-3 line-clamp-2 leading-relaxed bg-slate-50 p-2.5 rounded-xl border border-slate-100">
              {{ org.descripcion }}
            </p>

            <!-- Métricas Asociadas (Salas y Personal) -->
            <div class="grid grid-cols-2 gap-2 mt-3 pt-3 border-t border-slate-100">
              <div class="bg-slate-50/70 p-2 rounded-xl text-center">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Salas Climatizadas</span>
                <span class="text-sm font-extrabold text-slate-800">{{ org.salas ? org.salas.length : 0 }}</span>
              </div>
              <div class="bg-slate-50/70 p-2 rounded-xl text-center">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Miembros / Roles</span>
                <span class="text-sm font-extrabold text-emerald-700">
                  {{ org.usuarios_organizaciones ? org.usuarios_organizaciones.length : 1 }}
                </span>
              </div>
            </div>
          </div>

          <!-- BOTONES DE ACCIÓN -->
          <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
            <button
              v-if="org.id !== props.organizacionActivaId"
              type="button"
              @click="switchOrganizacionActiva(org.id)"
              class="w-full py-2 px-3 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5"
            >
              <span>Seleccionar como Activa</span>
            </button>
            <div
              v-else
              class="w-full py-2 px-3 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold text-center"
            >
              Organización en Uso
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL CREAR ORGANIZACIÓN (App\Models\Organizacion) -->
      <div
        v-if="modalCrearOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in"
      >
        <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
          <!-- Modal Header -->
          <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-xs">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <div>
                <h3 class="text-sm font-extrabold text-slate-100">Crear Nueva Organización</h3>
                <p class="text-[11px] text-slate-400">Entidad Eloquent raíz para agrupar salas, cultivos y equipos</p>
              </div>
            </div>
            <button @click="modalCrearOpen = false" class="text-slate-400 hover:text-white p-1.5 rounded-lg">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Modal Body Form -->
          <form @submit.prevent="submitCrearOrganizacion" class="p-6 overflow-y-auto space-y-4">
            <!-- Plantillas rápidas -->
            <div>
              <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5">
                Plantillas Sugeridas
              </label>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                <button
                  v-for="(p, i) in presets"
                  :key="i"
                  type="button"
                  @click="aplicarPreset(p)"
                  class="p-2 text-left rounded-xl border border-slate-200 hover:border-emerald-400 hover:bg-emerald-50/50 transition-all text-xs"
                >
                  <span class="font-bold text-slate-800 block truncate">{{ p.nombre }}</span>
                  <span class="text-[10px] text-slate-500 block truncate">{{ p.localizacion }}</span>
                </button>
              </div>
            </div>

            <!-- Nombre -->
            <div>
              <label class="text-xs font-bold text-slate-700 uppercase tracking-wide block mb-1">
                Nombre de la Organización <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="form.nombre"
                type="text"
                required
                placeholder="Ej: CultivoOS Facility Pilar #1"
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
              />
              <span v-if="form.errors.nombre" class="text-xs text-rose-500 mt-1 block">{{ form.errors.nombre }}</span>
            </div>

            <!-- Localización -->
            <div>
              <label class="text-xs font-bold text-slate-700 uppercase tracking-wide block mb-1">
                Localización / Domicilio Físico <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="form.localizacion"
                type="text"
                required
                placeholder="Ej: Parque Industrial Tecnológico, Nave 4, Buenos Aires"
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
              />
              <span v-if="form.errors.localizacion" class="text-xs text-rose-500 mt-1 block">{{ form.errors.localizacion }}</span>
            </div>

            <!-- Descripción -->
            <div>
              <label class="text-xs font-bold text-slate-700 uppercase tracking-wide block mb-1">
                Descripción & Objetivos Operativos
              </label>
              <textarea
                v-model="form.descripcion"
                rows="2"
                placeholder="Detalle los objetivos agronómicos y bioseguridad..."
                class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
              ></textarea>
            </div>

            <!-- Rol Asignado -->
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
              <label class="text-xs font-bold text-slate-800 uppercase tracking-wide block">
                Tu Rol en esta Organización
              </label>
              <p class="text-[11px] text-slate-500">
                Se registrará tu usuario en la tabla pivot <code>usuario_organizacions</code> con este rol:
              </p>
              <select
                v-model="form.rol_id"
                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none"
              >
                <option v-for="r in props.roles" :key="r.id" :value="r.id">
                  {{ r.nombre }} — {{ r.descripcion }}
                </option>
              </select>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-3">
              <button
                type="button"
                @click="modalCrearOpen = false"
                class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-100"
              >
                Cancelar
              </button>
              <button
                type="submit"
                :disabled="form.processing"
                class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs flex items-center gap-1.5"
              >
                <span>Crear Organización</span>
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </AppLayout>
</template>
