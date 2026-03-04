@extends('layouts.app')

@section('title', 'Mi Horario - ' . config('app.name'))
@section('mobileTitle', 'Mi Horario')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 p-4">

    <!-- Header -->
    <div>
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span>Mi Horario</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Mi Horario</h1>
        <p class="text-gray-600 dark:text-gray-400">Configuración semanal de atención (solo lectura)</p>
    </div>

    @if($setting)
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Configuración de turnos</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                @if($setting->default_duration_minutes)
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Duración de turno</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $setting->default_duration_minutes }} minutos</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Horario semanal -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Horario semanal</h2>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($scheduleByDay as $dayNum => $day)
                <div class="flex items-center px-4 py-3 {{ empty($day['slots']->toArray()) ? 'opacity-50' : '' }}">
                    <div class="w-28 shrink-0">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $day['name'] }}</span>
                    </div>
                    <div class="flex-1">
                        @if($day['slots']->isEmpty())
                            <span class="text-sm text-gray-400 dark:text-gray-500">Sin horario</span>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach($day['slots'] as $slot)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400 text-xs rounded">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}
                                        @if($slot->office) · {{ $slot->office->name }} @endif
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
