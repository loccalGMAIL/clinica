@extends('layouts.app')

@section('title', 'Mis Historias Clínicas - ' . config('app.name'))
@section('mobileTitle', 'Mis HCs')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 p-4">

    <!-- Header -->
    <div>
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span>Mis Historias Clínicas</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Mis Historias Clínicas</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pacientes con registros clínicos</p>
    </div>

    <!-- Filtro -->
    <form method="GET" action="{{ route('professional.clinical') }}" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
        <div class="flex gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Buscar por nombre o DNI..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm rounded-lg">Buscar</button>
            @if(request('search'))
                <a href="{{ route('professional.clinical') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Limpiar</a>
            @endif
        </div>
    </form>

    <!-- Lista de pacientes -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($patients as $patient)
                @php $stat = $hcStats[$patient->id] ?? null; @endphp
                <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex items-center gap-4 min-w-0">
                        <!-- Avatar inicial -->
                        <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                            <span class="text-sm font-semibold text-indigo-700 dark:text-indigo-400">
                                {{ mb_strtoupper(mb_substr($patient->last_name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $patient->full_name }}
                            </p>
                            <div class="flex items-center gap-3 mt-0.5">
                                <span class="text-xs text-gray-500 dark:text-gray-400">DNI {{ $patient->dni }}</span>
                                @if($stat)
                                    <span class="text-xs text-gray-400 dark:text-gray-500">·</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Última: {{ \Carbon\Carbon::parse($stat->last_date)->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        @if($stat)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                {{ $stat->total }} {{ $stat->total == 1 ? 'atención' : 'atenciones' }}
                            </span>
                        @endif
                        <a href="{{ route('professional.clinical.patient', $patient->id) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-md transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            Ver HC
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                    @if(request('search'))
                        No se encontraron pacientes con ese criterio.
                    @else
                        Aún no tiene historias clínicas registradas.
                    @endif
                </div>
            @endforelse
        </div>

        @if($patients->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $patients->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
