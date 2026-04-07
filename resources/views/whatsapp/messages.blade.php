@extends('layouts.app')

@section('title', 'WhatsApp - Mensajes Enviados - ' . config('app.name'))

@section('mobileTitle', 'Mensajes WA')

@section('content')
<div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

    {{-- Header --}}
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mensajes Enviados</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Historial de comunicaciones con pacientes</p>
    </div>

    {{-- Two-column chat layout --}}
    <div class="flex flex-col md:flex-row gap-4" style="min-height: 560px;">

        {{-- Left: Contacts list --}}
        <div class="w-full md:w-1/3 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            {{-- Search --}}
            <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0016.803 15.803z" />
                    </svg>
                    <input type="text" placeholder="Buscar paciente..."
                        class="w-full pl-9 pr-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>

            {{-- Contact list --}}
            <div class="flex-1 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-700/50">
                @php
                $contacts = [
                    ['initials' => 'LG', 'color' => 'bg-purple-500', 'name' => 'Laura Gómez',   'snippet' => 'Tu turno está confirmado para...', 'time' => '10:30', 'active' => true],
                    ['initials' => 'MS', 'color' => 'bg-blue-500',   'name' => 'Martín Sosa',   'snippet' => 'Recordatorio: mañana a las 9:00',  'time' => '09:15', 'active' => false],
                    ['initials' => 'AP', 'color' => 'bg-emerald-500','name' => 'Ana Pérez',     'snippet' => 'Hola, quisiera reprogramar...',    'time' => 'Ayer',  'active' => false],
                    ['initials' => 'CR', 'color' => 'bg-amber-500',  'name' => 'Carlos Ruiz',   'snippet' => 'Tu turno fue cancelado.',          'time' => 'Ayer',  'active' => false],
                    ['initials' => 'VL', 'color' => 'bg-rose-500',   'name' => 'Valeria López', 'snippet' => 'Gracias por confirmar, te esperamos', 'time' => 'Lun', 'active' => false],
                    ['initials' => 'JF', 'color' => 'bg-cyan-500',   'name' => 'Jorge Flores',  'snippet' => 'Recordatorio: turno el miércoles','time' => 'Dom',  'active' => false],
                ];
                @endphp
                @foreach($contacts as $c)
                <div class="flex items-center gap-3 px-4 py-3 cursor-pointer transition-colors
                    {{ $c['active'] ? 'bg-green-50 dark:bg-green-900/20' : 'hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full {{ $c['color'] }} flex items-center justify-center">
                        <span class="text-xs font-bold text-white">{{ $c['initials'] }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $c['name'] }}</p>
                            <span class="text-xs text-gray-400 dark:text-gray-500 ml-2 flex-shrink-0">{{ $c['time'] }}</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ $c['snippet'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Right: Conversation --}}
        <div class="w-full md:w-2/3 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            {{-- Header --}}
            <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="w-9 h-9 rounded-full bg-purple-500 flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-bold text-white">LG</span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Laura Gómez</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">+54 9 11 4512-3456</p>
                </div>
                <div class="ml-auto">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Entregado
                    </span>
                </div>
            </div>

            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-3 bg-gray-50 dark:bg-gray-900/30">
                @php
                $messages = [
                    ['sent' => true,  'text' => '🗓️ Recordatorio: Tiene un turno con la Dra. Fernández mañana martes 8 de abril a las 10:30 hs. Clínica Pez, Av. Corrientes 1234.', 'time' => '10:28'],
                    ['sent' => false, 'text' => 'Muchas gracias por avisarme! Confirmo asistencia.', 'time' => '10:29'],
                    ['sent' => true,  'text' => '✅ Turno confirmado. Te esperamos mañana a las 10:30 hs. Por favor llegá 10 minutos antes.', 'time' => '10:30'],
                    ['sent' => false, 'text' => 'Perfecto, estaré puntual. ¿Debo traer estudios previos?', 'time' => '10:31'],
                    ['sent' => true,  'text' => 'Sí, si tenés análisis de sangre recientes o estudios relacionados, traelos. También tu documento de identidad.', 'time' => '10:31'],
                    ['sent' => false, 'text' => 'Ok entendido, muchas gracias!', 'time' => '10:33'],
                    ['sent' => true,  'text' => '🗓️ Recordatorio final: Su turno es HOY a las 10:30 hs con la Dra. Fernández. Dirección: Av. Corrientes 1234, 3° piso.', 'time' => '09:00'],
                    ['sent' => false, 'text' => '👍', 'time' => '09:05'],
                ];
                @endphp
                @foreach($messages as $msg)
                <div class="flex {{ $msg['sent'] ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] px-4 py-2.5 rounded-2xl text-sm shadow-sm
                        {{ $msg['sent']
                            ? 'bg-green-600 text-white rounded-br-sm'
                            : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-100 dark:border-gray-600 rounded-bl-sm' }}">
                        <p>{{ $msg['text'] }}</p>
                        <p class="text-xs mt-1 {{ $msg['sent'] ? 'text-green-100' : 'text-gray-400 dark:text-gray-500' }} text-right">{{ $msg['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Read-only notice --}}
            <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <p class="text-xs text-gray-400 dark:text-gray-500 text-center">
                    <svg class="w-3.5 h-3.5 inline-block mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Este es un historial de solo lectura. Los mensajes son enviados automáticamente por el sistema.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
