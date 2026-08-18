<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    nombre: '',
    descripcion: '',
});

const submit = () => {
    form.post(route('organizaciones.store'));
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-lg">

            <!-- Encabezado -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    Crear organización
                </h1>

                <p class="mt-2 text-gray-500">
                    Configurá tu espacio de trabajo en CultivoOS.
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