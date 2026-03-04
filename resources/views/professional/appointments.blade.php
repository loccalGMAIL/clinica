@extends('layouts.app')

@section('title', 'Mis Turnos - ' . config('app.name'))
@section('mobileTitle', 'Mis Turnos')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 p-4">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
                <a href="{{ route('professional.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                <span>Mis Turnos</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Mis Turnos</h1>
        </div>
    </div>

    <!-- Filtros rápidos -->
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('professional.appointments', ['date_from' => now()->toDateString(), 'date_to' => now()->toDateString()]) }}"
           class="px-3 py-1.5 text-sm rounded-lg border {{ request()->date_from === now()->toDateString() && request()->date_to === now()->toDateString() ? 'bg-emerald-600 text-white border-emerald-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
            Hoy
        </a>
        <a href="{{ route('professional.appointments', ['date_from' => now()->toDateString(), 'date_to' => now()->addDays(7)->toDateString()]) }}"
           class="px-3 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
            Próximos 7 días
        </a>
        <a href="{{ route('professional.appointments', ['date_to' => now()->subDay()->toDateString()]) }}"
           class="px-3 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
            Anteriores
        </a>
    </div>

    <!-- Filtros -->
    <form method="GET" action="{{ route('professional.appointments') }}" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Desde</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Hasta</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="all" @selected(!request('status') || request('status') === 'all')>Todos</option>
                    <option value="scheduled" @selected(request('status') === 'scheduled')>Programado</option>
                    <option value="attended" @selected(request('status') === 'attended')>Atendido</option>
                    <option value="absent" @selected(request('status') === 'absent')>Ausente</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelado</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm rounded-lg">Filtrar</button>
                <a href="{{ route('professional.appointments') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Limpiar</a>
            </div>
        </div>
    </form>

    <!-- Tabla -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">

        <!-- Mobile cards -->
        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($appointments as $appt)
                @php
                    $statusClasses = ['scheduled'=>'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400','attended'=>'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400','absent'=>'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400','cancelled'=>'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'];
                    $statusLabels = ['scheduled'=>'Programado','attended'=>'Atendido','absent'=>'Ausente','cancelled'=>'Cancelado'];
                @endphp
                <div class="p-4">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">
                            {{ $appt->patient->full_name ?? 'Sin paciente' }}
                        </span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $statusClasses[$appt->status] ?? '' }}">
                            {{ $statusLabels[$appt->status] ?? $appt->status }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $appt->appointment_date->format('d/m/Y H:i') }}
                        @if($appt->office) · {{ $appt->office->name }}@endif
                        @if($appt->final_amount) · ${{ number_format($appt->final_amount, 2) }}@endif
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('professional.patient-detail', $appt->patient_id) }}"
                           class="text-xs text-emerald-600 hover:text-emerald-800 dark:text-emerald-400">Ver paciente →</a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">No se encontraron turnos.</div>
            @endforelse
        </div>

        <!-- Desktop table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Hora</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Paciente</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Consultorio</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Monto</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($appointments as $appt)
                        @php
                            $statusClasses = ['scheduled'=>'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400','attended'=>'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400','absent'=>'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400','cancelled'=>'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'];
                            $statusLabels = ['scheduled'=>'Programado','attended'=>'Atendido','absent'=>'Ausente','cancelled'=>'Cancelado'];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $appt->appointment_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-white">{{ $appt->appointment_date->format('H:i') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $appt->patient->full_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $appt->office->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $statusClasses[$appt->status] ?? '' }}">
                                    {{ $statusLabels[$appt->status] ?? $appt->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                                @if($appt->final_amount) ${{ number_format($appt->final_amount, 2) }} @else - @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('professional.patient-detail', $appt->patient_id) }}"
                                   class="text-xs text-emerald-600 hover:text-emerald-800 dark:text-emerald-400">Ver paciente</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">No se encontraron turnos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appointments->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
