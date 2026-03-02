@extends('layouts.app')

@section('title', 'Centro - ' . config('app.name'))
@section('mobileTitle', 'Centro')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 p-4"
     x-data="{
        logoPreview: null,
        loginBgPreview: null,
        setPreview(field, event) {
            const file = event.target.files[0];
            if (!file) return;
            if (field === 'logo') {
                this.logoPreview = URL.createObjectURL(file);
            } else {
                this.loginBgPreview = URL.createObjectURL(file);
            }
        }
     }">

    <!-- Header -->
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                    <span>Sistema</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                    <span>Centro</span>
                </nav>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Configuración del Centro
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Identidad e imágenes del centro médico
                </p>
            </div>
        </div>
    </div>

    <!-- Banner de éxito -->
    @if(session('success'))
    <div x-data x-init="window.showToast && window.showToast('{{ session('success') }}', 'success')"
         class="rounded-lg bg-emerald-50 dark:bg-emerald-900/30 p-4 border border-emerald-200 dark:border-emerald-800">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="rounded-lg bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('settings.center.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Sección: Identidad del Centro -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Identidad del Centro</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Datos que aparecen en el login y en los recibos impresos</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label for="center_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre del Centro
                    </label>
                    <input type="text" id="center_name" name="center_name"
                           value="{{ old('center_name', $settings['center_name'] ?? '') }}"
                           maxlength="100"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('center_name') border-red-500 @enderror"
                           placeholder="Centro de Atención Médica">
                    @error('center_name')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subtítulo -->
                <div>
                    <label for="center_subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Subtítulo / Descripción
                    </label>
                    <input type="text" id="center_subtitle" name="center_subtitle"
                           value="{{ old('center_subtitle', $settings['center_subtitle'] ?? '') }}"
                           maxlength="100"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('center_subtitle') border-red-500 @enderror"
                           placeholder="Sistema de Gestión Médica">
                    @error('center_subtitle')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label for="center_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Dirección
                    </label>
                    <input type="text" id="center_address" name="center_address"
                           value="{{ old('center_address', $settings['center_address'] ?? '') }}"
                           maxlength="200"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('center_address') border-red-500 @enderror"
                           placeholder="Av. Ejemplo 123, Ciudad">
                    @error('center_address')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="center_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Teléfono
                    </label>
                    <input type="text" id="center_phone" name="center_phone"
                           value="{{ old('center_phone', $settings['center_phone'] ?? '') }}"
                           maxlength="50"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('center_phone') border-red-500 @enderror"
                           placeholder="(0351) 000-0000">
                    @error('center_phone')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label for="center_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Email de Contacto
                    </label>
                    <input type="email" id="center_email" name="center_email"
                           value="{{ old('center_email', $settings['center_email'] ?? '') }}"
                           maxlength="100"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('center_email') border-red-500 @enderror"
                           placeholder="contacto@ejemplo.com">
                    @error('center_email')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sección: Imágenes del Centro -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Imágenes del Centro</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Logo para la barra de navegación y recibos; fondo para la pantalla de inicio de sesión</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Logo -->
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Logo del Centro</p>

                    <!-- Preview actual / nuevo -->
                    <div class="mb-3 flex items-center justify-center h-32 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <template x-if="logoPreview">
                            <img :src="logoPreview" alt="Nuevo logo" class="max-h-28 max-w-full object-contain">
                        </template>
                        <template x-if="!logoPreview">
                            <img src="{{ center_image('logo', 'logo.png') }}" alt="Logo actual"
                                 class="max-h-28 max-w-full object-contain"
                                 onerror="this.style.display='none'">
                        </template>
                    </div>

                    <!-- Input file con botón estilizado -->
                    <label class="flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        Seleccionar imagen
                        <input type="file" name="logo" class="hidden" accept=".png,.jpg,.jpeg,.webp,.svg"
                               @change="setPreview('logo', $event)">
                    </label>
                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, WEBP o SVG — máx. 2 MB</p>
                    @error('logo')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fondo de login -->
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Fondo de Pantalla de Login</p>

                    <!-- Preview actual / nuevo -->
                    <div class="mb-3 flex items-center justify-center h-32 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <template x-if="loginBgPreview">
                            <img :src="loginBgPreview" alt="Nuevo fondo" class="h-32 w-full object-cover">
                        </template>
                        <template x-if="!loginBgPreview">
                            <img src="{{ center_image('login_bg', 'back_login.png') }}" alt="Fondo actual"
                                 class="h-32 w-full object-cover"
                                 onerror="this.style.display='none'">
                        </template>
                    </div>

                    <!-- Input file con botón estilizado -->
                    <label class="flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        Seleccionar imagen
                        <input type="file" name="login_bg" class="hidden" accept=".png,.jpg,.jpeg,.webp"
                               @change="setPreview('login_bg', $event)">
                    </label>
                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">PNG, JPG o WEBP — máx. 5 MB</p>
                    @error('login_bg')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        <!-- Botón guardar -->
        <div class="flex justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Guardar Configuración
            </button>
        </div>

    </form>

</div>
@endsection
