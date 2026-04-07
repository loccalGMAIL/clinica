@extends('layouts.app')

@section('title', 'Mis Pacientes - ' . config('app.name'))
@section('mobileTitle', 'Mis Pacientes')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 p-4">

    <!-- Header -->
    <div>
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span>Mis Pacientes</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Mis Pacientes</h1>
        <p class="text-gray-600 dark:text-gray-400">Pacientes que han tenido turnos con vos</p>
    </div>

    <!-- Búsqueda -->
    <form method="GET" action="{{ route('professional.patients') }}" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
        <div class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar por nombre o DNI..."
                   class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm rounded-lg">Buscar</button>
            @if(request('search'))
                <a href="{{ route('professional.patients') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Limpiar</a>
            @endif
        </div>
    </form>

    <!-- Lista -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">

        <!-- Mobile -->
        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($patients as $patient)
                @php $counts = $appointCounts[$patient->id] ?? null; @endphp
                <a href="{{ route('professional.patient-detail', $patient->id) }}"
                   class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">{{ $patient->full_name }}</span>
                        @if($counts)
                            <span class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 px-2 py-0.5 rounded-full">
                                {{ $counts->total }} turnos
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        DNI {{ $patient->dni }}
                        @if($counts?->last_appointment)
                            · Última: {{ \Carbon\Carbon::parse($counts->last_appointment)->format('d/m/Y') }}
                        @endif
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">No se encontraron pacientes.</div>
            @endforelse
        </div>

        <!-- Desktop table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Paciente</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">DNI</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Turnos</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Última consulta</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($patients as $patient)
                        @php $counts = $appointCounts[$patient->id] ?? null; @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $patient->full_name }}</td>
                            <td class="px-4 py-3 text-sm font-mono text-gray-600 dark:text-gray-400">{{ $patient->dni }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 px-2 py-0.5 rounded-full">
                                    {{ $counts->total ?? 0 }} turnos
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $counts?->last_appointment ? \Carbon\Carbon::parse($counts->last_appointment)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('professional.patient-detail', $patient->id) }}"
                                   class="text-xs text-emerald-600 hover:text-emerald-800 dark:text-emerald-400">Ver detalle →</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">No se encontraron pacientes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($patients->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $patients->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
