<script setup lang="ts">
import { ref } from 'vue'

import { useForm } from '@inertiajs/vue3';
const pasoActual = ref(1)

interface EstadoSala {
    id: string
    nombre: string
}
interface TipoSala {
    id: string
    nombre: string
}

const props = defineProps<{
    estadosSala: EstadoSala[],
    tiposSala:TipoSala[],
}>()

const form = useForm({
    nombre: '',
    descripcion: '',
    posee_co2:false,
    posee_extraccion:false,
    posee_intraccion:false,
    posee_ins_agua:false,
    tipo_sala_id:null,
    estado_sala_id:null,
    area:null,
    altura:null,
    volumen:null,

});
const pasos = [
    { id: 1, nombre: 'Datos generales' },
    { id: 2, nombre: 'Configuración' },
    { id: 3, nombre: 'Variedad' },
    { id: 4, nombre: 'Resumen' },
]

function siguiente() {
    if (pasoActual.value < pasos.length) {
        pasoActual.value++
    }
}

function anterior() {
    if (pasoActual.value > 1) {
        pasoActual.value--
    }
}
const submit = () => {
    form.post(route('salas.store'));
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-lg">

            <!-- Encabezado -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    Crear Sala
                </h1>

                <p class="mt-2 text-gray-500">
                    Configurá tu Sala de trabajo en CultivoOS.
                </p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">

                <form @submit.prevent="submit" class="space-y-6">

                    <!-- Nombre -->
                    <div>
                        <label
                            for="nombre"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Nombre
                        </label>

                        <input
                            id="nombre"
                            v-model="form.nombre"
                            type="text"
                            placeholder="Ej. Mi organización"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3
                                   text-gray-900 outline-none
                                   focus:border-green-500 focus:ring-2
                                   focus:ring-green-500/20"
                        />

                        <p
                            v-if="form.errors.nombre"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ form.errors.nombre }}
                        </p>
                    </div>

                    <!--area-->
                     <div>
                        <label
                            for="area"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Area
                        </label>

                        <input
                            id="area"
                            v-model="form.area"
                            type="number"
                            placeholder="0"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3
                                   text-gray-900 outline-none
                                   focus:border-green-500 focus:ring-2
                                   focus:ring-green-500/20"
                        />

                        <p
                            v-if="form.errors.nombre"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ form.errors.nombre }}
                        </p>
                    </div>

                    <!--Volumen-->
                     <div>
                        <label
                            for="volumen"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Area
                        </label>

                        <input
                            id="volumen"
                            v-model="form.volumen"
                            type="number"
                            placeholder="0"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3
                                   text-gray-900 outline-none
                                   focus:border-green-500 focus:ring-2
                                   focus:ring-green-500/20"
                        />

                        <p
                            v-if="form.errors.nombre"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ form.errors.nombre }}
                        </p>
                    </div>

                    <!--Altura-->
                     <div>
                        <label
                            for="altura"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Area
                        </label>

                        <input
                            id="altura"
                            v-model="form.altura"
                            type="number"
                            placeholder="0"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3
                                   text-gray-900 outline-none
                                   focus:border-green-500 focus:ring-2
                                   focus:ring-green-500/20"
                        />

                        <p
                            v-if="form.errors.nombre"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ form.errors.nombre }}
                        </p>
                    </div>
<!-- estado sala-->
                     <div>
                        <label
                            for="estado"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Estado de sala
                        </label>

                       <select v-model="form.estado_sala_id">
    <option :value="null" disabled>
        Seleccione un estado
    </option>

    <option
        v-for="estado in estadosSala"
        :key="estado.id"
        :value="estado.id"
    >
        {{ estado.nombre }}
    </option>
</select>

                        <p
                            v-if="form.errors.nombre"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ form.errors.nombre }}
                        </p>
                    </div>
<!--Tipo sala-->
 <div>
                        <label
                            for="estado"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Tipo de sala
                        </label>

                       <select v-model="form.tipo_sala_id">
    <option :value="null" disabled>
        Seleccione un Tipo
    </option>

    <option
        v-for="tipo in tiposSala"
        :key="tipo.id"
        :value="tipo.id"
    >
        {{ tipo.nombre }}
    </option>
</select>

                        <p
                            v-if="form.errors.nombre"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ form.errors.nombre }}
                        </p>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label
                            for="descripcion"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Descripción
                        </label>

                        <textarea
                            id="descripcion"
                            v-model="form.descripcion"
                            rows="4"
                            placeholder="Contanos brevemente sobre esta organización..."
                            class="w-full rounded-xl border border-gray-300 px-4 py-3
                                   text-gray-900 outline-none resize-none
                                   focus:border-green-500 focus:ring-2
                                   focus:ring-green-500/20"
                        />

                        <p
                            v-if="form.errors.descripcion"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ form.errors.descripcion }}
                        </p>
                    </div>
                    <!--para booleans co2 -->
                    <div>
                        <label
                            for="posee_co2"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Posee Co2 en la Sala
                        </label>

                      <select v-model="form.posee_co2">
                            <option :value="true">Sí</option>
                            <option :value="false">No</option>
                        </select>

                        <p
                            v-if="form.errors.descripcion"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ form.errors.descripcion }}
                        </p>
                    </div>

                        <!--para booleans co2 -->
                    <div>
                        <label
                            for="posee_intraccion"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Posee Intracción de aire en la Sala
                        </label>

                      <select v-model="form.posee_intraccion">
                            <option :value="true">Sí</option>
                            <option :value="false">No</option>
                        </select>

                        <p
                            v-if="form.errors.descripcion"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ form.errors.descripcion }}
                        </p>
                    </div>

                                            <!--para booleans co2 -->
                    <div>
                        <label
                            for="posee_extraccion"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Posee Extracción de aire en la Sala
                        </label>

                      <select v-model="form.posee_extraccion">
                            <option :value="true">Sí</option>
                            <option :value="false">No</option>
                        </select>

                        <p
                            v-if="form.errors.descripcion"
                            class="mt-2 text-sm text-red-500"
                        >
                            {{ form.errors.descripcion }}
                        </p>
                    </div>


                    <!-- Botón -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-xl bg-gray-900 px-4 py-3
                               font-medium text-white transition
                               hover:bg-gray-800
                               disabled:opacity-50"
                    >
                        {{ form.processing ? 'Creando...' : 'Crear organización' }}
                    </button>

                </form>

            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                Podrás configurar más opciones posteriormente.
            </p>

        </div>
    </div>
</template>