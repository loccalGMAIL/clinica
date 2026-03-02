@extends('layouts.app')

@section('title', 'Mi Dashboard - ' . config('app.name'))
@section('mobileTitle', 'Mi Dashboard')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 p-4">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
            Bienvenido, {{ $professional->first_name }}
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            {{ $professional->specialty->name ?? '' }} — {{ now()->isoFormat('dddd D [de] MMMM') }}
        </p>
    </div>

    <!-- Todas las cards en una sola fila -->
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Hoy total</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['today_total'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Atendidos</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['today_attended'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Pendientes</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['today_pending'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Ausentes</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['today_absent'] }}</p>
        </div>
        <a href="{{ route('professional.patients') }}"
           class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3 shadow-sm hover:border-emerald-300 transition-colors">
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Pacientes</p>
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['patients_total'] }}</p>
        </a>
        <a href="{{ route('professional.clinical') }}"
           class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3 shadow-sm hover:border-emerald-300 transition-colors">
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Mis HCs</p>
            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['clinical_total'] }}</p>
        </a>
    </div>

    <!-- Accesos rápidos -->
    <div class="grid gap-3 grid-cols-3">
        <a href="{{ route('professional.appointments') }}"
           class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-emerald-300 transition-colors">
            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mis Turnos</span>
        </a>
        <a href="{{ route('professional.patients') }}"
           class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-emerald-300 transition-colors">
            <svg class="w-5 h-5 text-purple-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mis Pacientes</span>
        </a>
        <a href="{{ route('professional.clinical') }}"
           class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-emerald-300 transition-colors">
            <svg class="w-5 h-5 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Historias Clínicas</span>
        </a>
    </div>

    <!-- Turnos del día -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-emerald-200/50 dark:border-emerald-800/30 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Turnos de hoy</h2>
            <a href="{{ route('professional.appointments') }}" class="text-sm text-emerald-600 hover:text-emerald-800 dark:text-emerald-400">Ver todos →</a>
        </div>
        @if($todayAppointments->isEmpty())
            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                No hay turnos programados para hoy.
            </div>
        @else
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($todayAppointments as $appt)
                    @php
                        $statusClasses = [
                            'scheduled' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                            'attended'  => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                            'absent'    => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                            'cancelled' => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                        ];
                        $statusLabels = [
                            'scheduled' => 'Programado',
                            'attended'  => 'Atendido',
                            'absent'    => 'Ausente',
                            'cancelled' => 'Cancelado',
                        ];
                        $hc = $todayClinical[$appt->id] ?? null;
                    @endphp
                    <div class="flex items-center justify-between px-4 py-3 gap-3">
                        <!-- Hora + Paciente -->
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="text-sm font-mono text-gray-500 dark:text-gray-400 w-11 shrink-0">
                                {{ $appt->appointment_date->format('H:i') }}
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $appt->patient->full_name ?? 'Sin paciente' }}
                                </p>
                                @if($appt->office)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $appt->office->name }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Acciones + Estado -->
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $statusClasses[$appt->status] ?? '' }}">
                                {{ $statusLabels[$appt->status] ?? $appt->status }}
                            </span>

                            @if($appt->status !== 'cancelled')
                                @if($hc)
                                    {{-- Ver HC completa del paciente --}}
                                    <a href="{{ route('professional.clinical.patient', $appt->patient_id) }}"
                                       class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-md transition-colors"
                                       title="Ver historia clínica">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="hidden sm:inline">Ver HC</span>
                                    </a>
                                @else
                                    {{-- Crear nueva HC para este turno --}}
                                    <a href="{{ route('professional.clinical') }}?new=1&patient_id={{ $appt->patient_id }}&appointment_id={{ $appt->id }}"
                                       class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 rounded-md transition-colors"
                                       title="Crear historia clínica">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        <span class="hidden sm:inline">Nueva HC</span>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
