<script setup lang="ts">
import { ref } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

// Alternar visibilidad de contraseña
const mostrarPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Iniciar Sesión - CultivoOS" />

        <!-- Tarjeta Principal de Autenticación CultivoOS -->
        <div class="w-full sm:max-w-md bg-white border border-slate-200/90 shadow-xl shadow-slate-200/40 rounded-3xl p-6 sm:p-8">
            <!-- Encabezado de Marca CultivoOS -->
            <div class="text-center mb-6">
                <!-- Icono de Planta / Brote Tecnológico -->
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white shadow-lg shadow-emerald-500/25 mb-3 ring-4 ring-emerald-50">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 20h10" />
                        <path d="M10 20c5.5-2.5.8-6.4 3-10" />
                        <path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8z" />
                        <path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.1-4 1-4.9 2z" />
                    </svg>
                </div>

                <div class="flex items-center justify-center gap-1.5">
                    <span class="text-xl font-black tracking-tight text-slate-900">cultivo</span>
                    <span class="text-xl font-black tracking-tight text-emerald-600">OS</span>
                    <span class="ml-1 text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md">
                        v2.4
                    </span>
                </div>

                <p class="text-xs font-semibold text-slate-500 mt-1">
                    Control Agronómico de Precisión & Telemetría IoT
                </p>
            </div>

            <!-- Banner de Estado de Sesión -->
            <div
                v-if="status"
                class="mb-5 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200/90 text-xs font-bold text-emerald-800 flex items-center gap-2.5"
            >
                <svg class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ status }}</span>
            </div>

            <!-- Formulario de Acceso -->
            <form @submit.prevent="submit" class="space-y-4">
                <!-- Campo: Correo Electrónico -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <InputLabel
                            for="email"
                            value="Correo Electrónico"
                            class="text-xs font-bold uppercase tracking-wider text-slate-700"
                        />
                        <span class="text-[10px] text-slate-400 font-mono">Técnico / Operador</span>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect width="20" height="16" x="2" y="4" rx="2" />
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                            </svg>
                        </div>

                        <TextInput
                            id="email"
                            type="email"
                            class="w-full bg-slate-50/70 border border-slate-200 rounded-2xl pl-10 pr-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="agronomo@cultivoos.com"
                        />
                    </div>

                    <InputError class="mt-1.5 text-xs font-semibold text-rose-600" :message="form.errors.email" />
                </div>

                <!-- Campo: Contraseña -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <InputLabel
                            for="password"
                            value="Contraseña"
                            class="text-xs font-bold uppercase tracking-wider text-slate-700"
                        />
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 transition-colors hover:underline"
                        >
                            ¿Olvidaste tu contraseña?
                        </Link>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </div>

                        <TextInput
                            id="password"
                            :type="mostrarPassword ? 'text' : 'password'"
                            class="w-full bg-slate-50/70 border border-slate-200 rounded-2xl pl-10 pr-10 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••••••"
                        />

                        <!-- Botón para mostrar / ocultar contraseña -->
                        <button
                            type="button"
                            @click="mostrarPassword = !mostrarPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                            tabindex="-1"
                        >
                            <svg v-if="!mostrarPassword" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg v-else class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                <line x1="2" x2="22" y1="2" y2="22" />
                            </svg>
                        </button>
                    </div>

                    <InputError class="mt-1.5 text-xs font-semibold text-rose-600" :message="form.errors.password" />
                </div>

                <!-- Recordarme (Checkbox) -->
                <div class="pt-1 flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <Checkbox
                            name="remember"
                            v-model:checked="form.remember"
                            class="rounded-lg border-slate-300 text-emerald-600 focus:ring-emerald-500/20 w-4 h-4 transition-all"
                        />
                        <span class="text-xs font-bold text-slate-600">
                            Recordar credenciales en esta estación
                        </span>
                    </label>
                </div>

                <!-- Botón de Ingreso CultivoOS -->
                <div class="pt-2">
                    <PrimaryButton
                        class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-black py-3 px-4 rounded-2xl shadow-md shadow-emerald-600/20 hover:shadow-lg hover:shadow-emerald-600/30 transition-all cursor-pointer border-0 disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="{ 'opacity-50 pointer-events-none': form.processing }"
                        :disabled="form.processing"
                    >
                        <!-- Spinner cuando está procesando -->
                        <svg
                            v-if="form.processing"
                            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <!-- Icono de Acceso Seguro -->
                        <svg v-else class="w-4 h-4 text-emerald-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>

                        <span>{{ form.processing ? 'Verificando credenciales...' : 'Iniciar Sesión en CultivoOS' }}</span>
                    </PrimaryButton>
                </div>
            </form>

            <!-- Pie de Seguridad y Soporte Agronómico -->
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-bold text-slate-600">Servidor IoT En Línea</span>
                </div>
                <span class="font-mono text-[10px] text-slate-400">SSL 256-bit Encrypted</span>
            </div>
        </div>
    </GuestLayout>
</template>
