@extends('layouts.app')

@section('title', 'Historia Clínica - ' . config('app.name'))
@section('mobileTitle', 'Historia Clínica')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 p-4">

    <!-- Header -->
    <div>
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <a href="{{ route('professional.clinical') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Mis HCs</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span>Ver HC</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Historia Clínica</h1>
    </div>

    <!-- Tarjeta de HC -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm divide-y divide-gray-100 dark:divide-gray-700">

        <!-- Paciente y fecha -->
        <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Paciente</p>
                <p class="font-semibold text-gray-900 dark:text-white text-base">{{ $record->patient->full_name ?? 'Sin paciente' }}</p>
                @if($record->patient?->dni)
                    <p class="text-xs text-gray-500 dark:text-gray-400">DNI {{ $record->patient->dni }}</p>
                @endif
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Fecha de consulta</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $record->date->format('d/m/Y') }}</p>
            </div>
            @if($record->appointment)
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Turno asociado</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $record->appointment->appointment_date->format('d/m/Y H:i') }}</p>
                </div>
            @endif
        </div>

        <!-- Contenido -->
        <div class="p-5">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Contenido</p>
            <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed">{{ $record->content }}</p>
        </div>

        @if($record->diagnosis)
        <!-- Diagnóstico -->
        <div class="p-5">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Diagnóstico</p>
            <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $record->diagnosis }}</p>
        </div>
        @endif

        @if($record->treatment)
        <!-- Tratamiento -->
        <div class="p-5">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Tratamiento</p>
            <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $record->treatment }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="px-5 py-3 bg-gray-50 dark:bg-gray-800/50 flex items-center justify-between">
            <p class="text-xs text-gray-400 dark:text-gray-500">
                Creada el {{ $record->created_at->format('d/m/Y H:i') }}
            </p>
            <a href="{{ route('professional.clinical') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Volver a Mis HCs
            </a>
        </div>
    </div>

</div>
@endsection
