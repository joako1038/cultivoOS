<script setup lang="ts">
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// Alternar visibilidad de contraseñas
const mostrarPassword = ref(false);
const mostrarConfirmPassword = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Registro de Operador - CultivoOS" />

        <!-- Tarjeta Principal de Registro CultivoOS -->
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
                        Alta de Cuenta
                    </span>
                </div>

                <p class="text-xs font-semibold text-slate-500 mt-1">
                    Crea tu cuenta de operador para acceder al panel agronómico
                </p>
            </div>

            <!-- Formulario de Registro -->
            <form @submit.prevent="submit" class="space-y-4">
                <!-- Campo: Nombre y Apellido -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <InputLabel
                            for="name"
                            value="Nombre Completo"
                            class="text-xs font-bold uppercase tracking-wider text-slate-700"
                        />
                        <span class="text-[10px] text-slate-400 font-mono">Identificación</span>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>

                        <TextInput
                            id="name"
                            type="text"
                            class="w-full bg-slate-50/70 border border-slate-200 rounded-2xl pl-10 pr-3.5 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Ing. Agrónomo / Nombre del Técnico"
                        />
                    </div>

                    <InputError class="mt-1.5 text-xs font-semibold text-rose-600" :message="form.errors.name" />
                </div>

                <!-- Campo: Correo Electrónico -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <InputLabel
                            for="email"
                            value="Correo Electrónico"
                            class="text-xs font-bold uppercase tracking-wider text-slate-700"
                        />
                        <span class="text-[10px] text-slate-400 font-mono">Email Corporativo</span>
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
                            autocomplete="username"
                            placeholder="operador@cultivoos.com"
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
                        <span class="text-[10px] text-slate-400 font-mono">Mín. 8 caracteres</span>
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
                            autocomplete="new-password"
                            placeholder="••••••••••••"
                        />

                        <!-- Toggle mostrar/ocultar contraseña -->
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

                <!-- Campo: Confirmar Contraseña -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <InputLabel
                            for="password_confirmation"
                            value="Confirmar Contraseña"
                            class="text-xs font-bold uppercase tracking-wider text-slate-700"
                        />
                        <span class="text-[10px] text-slate-400 font-mono">Verificación</span>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                        </div>

                        <TextInput
                            id="password_confirmation"
                            :type="mostrarConfirmPassword ? 'text' : 'password'"
                            class="w-full bg-slate-50/70 border border-slate-200 rounded-2xl pl-10 pr-10 py-2.5 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••••••"
                        />

                        <!-- Toggle mostrar/ocultar confirmación -->
                        <button
                            type="button"
                            @click="mostrarConfirmPassword = !mostrarConfirmPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                            tabindex="-1"
                        >
                            <svg v-if="!mostrarConfirmPassword" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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

                    <InputError
                        class="mt-1.5 text-xs font-semibold text-rose-600"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <!-- Enlace a Iniciar Sesión -->
                <div class="pt-1 flex items-center justify-between">
                    <span class="text-xs text-slate-500">¿Ya tienes una cuenta activa?</span>
                    <Link
                        :href="route('login')"
                        class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors hover:underline"
                    >
                        Iniciar Sesión
                    </Link>
                </div>

                <!-- Botón de Registro Primario -->
                <div class="pt-2">
                    <PrimaryButton
                        class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-black py-3 px-4 rounded-2xl shadow-md shadow-emerald-600/20 hover:shadow-lg hover:shadow-emerald-600/30 transition-all cursor-pointer border-0 disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="{ 'opacity-50 pointer-events-none': form.processing }"
                        :disabled="form.processing"
                    >
                        <!-- Spinner durante el submit -->
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

                        <!-- Icono de Usuario / Registro -->
                        <svg v-else class="w-4 h-4 text-emerald-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>

                        <span>{{ form.processing ? 'Registrando operador...' : 'Crear Cuenta en CultivoOS' }}</span>
                    </PrimaryButton>
                </div>
            </form>

            <!-- Pie de Seguridad y Auditoría -->
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
