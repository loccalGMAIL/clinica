<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ setting('center_name') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gradient-to-br from-emerald-50 to-green-100 min-h-screen">
    <div class="min-h-screen flex">

        <!-- Imagen del lado izquierdo -->
        <div class="hidden lg:flex lg:w-1/2 xl:w-3/5">
            <div
                class="relative w-full bg-gradient-to-br from-emerald-600 via-green-600 to-teal-700 flex items-center justify-center overflow-hidden">
                <!-- Imagen de fondo difuminada -->
                <img src="{{ center_image('login_bg', 'back_login.png') }}" class="absolute inset-0 w-full h-full object-cover"
                    style="filter: blur(4px);" alt="Background"
                    onerror="this.style.display='none';">

                <!-- Overlay para difuminar y oscurecer -->
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/60 via-green-600/50 to-teal-700/60">
                </div>

                <!-- Contenido central -->
                <div class="relative z-10 text-center text-white px-8"
                    style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                    <p class="text-white leading-relaxed max-w-md mx-auto"
                        style="text-shadow: 1px 1px 3px rgba(0,0,0,0.7);">
                        Plataforma integral para la gestión de consultorios médicos,
                        control de citas, pacientes y profesionales de la salud.
                    </p>

                    <!-- Características destacadas -->
                    <div class="mt-12 space-y-4 text-left max-w-md mx-auto">
                        <div class="flex items-center text-white" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
                            <svg class="w-5 h-5 mr-3 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" style="filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.8));">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Gestión completa de turnos médicos
                        </div>
                        <div class="flex items-center text-white" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
                            <svg class="w-5 h-5 mr-3 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" style="filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.8));">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Control de pagos y facturación
                        </div>
                        <div class="flex items-center text-white" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
                            <svg class="w-5 h-5 mr-3 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" style="filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.8));">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Reportes y estadísticas en tiempo real
                        </div>
                        <div class="flex items-center text-white" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
                            <svg class="w-5 h-5 mr-3 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" style="filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.8));">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Recordatorios automáticos a pacientes por Whatsapp
                        </div>
                        <div class="flex items-center text-white" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
                            <svg class="w-5 h-5 mr-3 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" style="filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.8));">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Historial médico digital y gestión de pacientes
                        </div>
                        <div class="flex items-center text-white" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
                            <svg class="w-5 h-5 mr-3 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" style="filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.8));">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Acceso desde cualquier dispositivo con conexión a internet
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de login -->
        <div class="w-full lg:w-1/2 xl:w-2/5 flex items-center justify-center px-4">
            <div class="w-full max-w-md">
                <div class="bg-white shadow-2xl rounded-2xl p-8">

                    <!-- Logo y título -->
                    <div class="text-center mb-6">
                        <div style="display: flex; justify-content: center; align-items: center; width: 100%;">
                            <img src="{{ center_image('logo', 'logo.png') }}" alt="Logo"
                                style="max-width:200px;max-height:200px;" />
                        </div>
                        <p class="text-gray-600 mt-2">{{ setting('center_subtitle', 'Sistema de Gestión Médica') }}</p>
                    </div>

                    @if(request('reset'))
                    <div class="mb-5 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">
                        Datos de demo reiniciados correctamente.
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="mb-5 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(!app()->environment('production') && count($demoUsers))

                    {{-- Modo demo: cards primero, formulario plegado --}}
                    <div x-data="{ formOpen: {{ $errors->any() ? 'true' : 'false' }} }">

                        <!-- Cards de acceso rápido -->
                        <p class="text-xs text-gray-400 text-center mb-3 font-medium uppercase tracking-wide">Acceso rápido — demo</p>
                        <div class="grid grid-cols-3 gap-2 mb-5">
                            @foreach($demoUsers as $demoUser)
                            <form method="POST" action="{{ route('demo.login') }}">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $demoUser->id }}">
                                <button type="submit"
                                    class="w-full flex flex-col items-center gap-1 p-3 rounded-xl border border-gray-200 hover:border-emerald-400 hover:bg-emerald-50 transition-all group">
                                    <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold text-sm group-hover:bg-emerald-200">
                                        {{ strtoupper(substr($demoUser->name, 0, 2)) }}
                                    </div>
                                    <span class="text-xs text-gray-600 font-medium leading-tight text-center">{{ $demoUser->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $demoUser->profile?->name ?? 'Sin perfil' }}</span>
                                </button>
                            </form>
                            @endforeach
                        </div>

                        <!-- Toggle formulario -->
                        <button type="button" @click="formOpen = !formOpen"
                            class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors text-sm text-gray-600">
                            <span>Ingresar con usuario y contraseña</span>
                            <svg :class="formOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <!-- Formulario plegado -->
                        <div x-show="formOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="mt-4">
                            @include('auth._login-form')
                        </div>

                    </div>

                    @else

                    {{-- Sin demo: formulario directo --}}
                    @include('auth._login-form')

                    @endif

                    <!-- Footer -->
                    <div class="mt-6 text-center">
                        <p class="text-xs text-gray-500">
                            {{ setting('center_name') }} v{{ config('app.version') }} - &copy; {{ date('Y') }}
                            - Designed by <a target="_blank" href="https://pez.com.ar">Pez</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
