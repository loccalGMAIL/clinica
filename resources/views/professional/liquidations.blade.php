@extends('layouts.app')

@section('title', 'Mis Liquidaciones - ' . config('app.name'))
@section('mobileTitle', 'Liquidaciones')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 p-4">

    <!-- Header -->
    <div>
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span>Mis Liquidaciones</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Mis Liquidaciones</h1>
    </div>

    <!-- Filtros -->
    <form method="GET" action="{{ route('professional.liquidations') }}" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm rounded-lg">Filtrar</button>
                @if(request()->hasAny(['date_from','date_to']))
                    <a href="{{ route('professional.liquidations') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Limpiar</a>
                @endif
            </div>
        </div>
    </form>

    <!-- Tabla -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

        <!-- Mobile -->
        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($liquidations as $liq)
                @php
                    $statusClass = $liq->isPaid()
                        ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400'
                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                @endphp
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $liq->liquidation_date->format('d/m/Y') }}
                        </span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $statusClass }}">
                            {{ $liq->isPaid() ? 'Pagado' : 'Pendiente' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Recaudado:</span>
                            <span class="font-medium text-gray-900 dark:text-white ml-1">${{ number_format($liq->total_collected, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Comisión:</span>
                            <span class="font-medium text-emerald-600 dark:text-emerald-400 ml-1">${{ number_format($liq->professional_commission, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Turnos:</span>
                            <span class="font-medium text-gray-900 dark:text-white ml-1">{{ $liq->appointments_attended }} at. / {{ $liq->appointments_absent }} aus.</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">No hay liquidaciones.</div>
            @endforelse
        </div>

        <!-- Desktop -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Turnos</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Recaudado</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Mi comisión</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Clínica</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($liquidations as $liq)
                        @php
                            $statusClass = $liq->isPaid()
                                ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400'
                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $liq->liquidation_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">
                                {{ $liq->appointments_attended }}at / {{ $liq->appointments_absent }}aus
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-medium text-gray-900 dark:text-white">
                                ${{ number_format($liq->total_collected, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-emerald-600 dark:text-emerald-400">
                                ${{ number_format($liq->professional_commission, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">
                                ${{ number_format($liq->clinic_amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $statusClass }}">
                                    {{ $liq->isPaid() ? 'Pagado' : 'Pendiente' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">No hay liquidaciones.</td></tr>
                    @endforelse
                </tbody>
                @if($liquidations->isNotEmpty())
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Total filtrado</td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900 dark:text-white">
                                ${{ number_format($totals['total_collected'], 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-emerald-600 dark:text-emerald-400">
                                ${{ number_format($totals['professional_commission'], 2) }}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        @if($liquidations->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $liquidations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
