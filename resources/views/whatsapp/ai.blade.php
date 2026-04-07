@extends('layouts.app')

@section('title', 'WhatsApp - Agente AI - ' . config('app.name'))

@section('mobileTitle', 'WhatsApp AI')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">

    {{-- Header --}}
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Agente AI</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configura el asistente inteligente de WhatsApp</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Section 1: Knowledge Base --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Base de Conocimiento</h2>

            {{-- Upload area --}}
            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center hover:border-green-400 dark:hover:border-green-500 transition-colors cursor-pointer">
                <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                </svg>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Arrastrá archivos aquí</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">PDF, DOCX, TXT — máx. 10 MB</p>
            </div>

            {{-- Uploaded documents --}}
            <div class="mt-4 space-y-2">
                @php
                $docs = [
                    ['name' => 'protocolo-turnos.pdf',      'size' => '124 KB', 'color' => 'text-red-500'],
                    ['name' => 'preguntas-frecuentes.docx', 'size' => '87 KB',  'color' => 'text-blue-500'],
                    ['name' => 'info-obra-social.txt',      'size' => '12 KB',  'color' => 'text-gray-500'],
                ];
                @endphp
                @foreach($docs as $doc)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ $doc['color'] }} flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $doc['name'] }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $doc['size'] }}</p>
                        </div>
                    </div>
                    <button type="button" class="text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>

            <button type="button"
                class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Agregar documento
            </button>
        </div>

        {{-- Section 2: Agent Config --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Configuración del Agente</h2>
            <div class="space-y-5">

                {{-- Agent name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del agente</label>
                    <input type="text" value="Asistente Clínica Pez"
                        class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                {{-- Tone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tono de respuesta</label>
                    <select class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option>Formal</option>
                        <option selected>Amigable</option>
                        <option>Neutro</option>
                    </select>
                </div>

                {{-- Max length --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Longitud máxima de respuesta</label>
                        <span class="text-sm font-medium text-green-600 dark:text-green-400">250 palabras</span>
                    </div>
                    <input type="range" min="50" max="500" value="250"
                        class="w-full h-2 bg-gray-200 dark:bg-gray-600 rounded-lg appearance-none cursor-pointer accent-green-600">
                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>50</span><span>500</span>
                    </div>
                </div>

                {{-- Off-hours toggle --}}
                <div x-data="{ enabled: true }" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Responder fuera de horario</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">El agente responde también cuando la clínica está cerrada</p>
                    </div>
                    <button @click="enabled = !enabled" type="button"
                        :class="enabled ? 'bg-green-600' : 'bg-gray-300 dark:bg-gray-600'"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <span :class="enabled ? 'translate-x-5' : 'translate-x-0'"
                              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                    </button>
                </div>

            </div>
        </div>

    </div>

    {{-- Section 3: Automatic Responses --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Respuestas Automáticas</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
            $automations = [
                ['label' => 'Recordatorio de turno',  'desc' => 'Se envía 24 hs antes del turno',         'enabled' => true],
                ['label' => 'Confirmación de turno',  'desc' => 'Tras confirmar un turno nuevo',           'enabled' => true],
                ['label' => 'Turno cancelado',        'desc' => 'Notificación de cancelación al paciente', 'enabled' => false],
                ['label' => 'Información general',    'desc' => 'Responde preguntas sobre la clínica',     'enabled' => true],
            ];
            @endphp
            @foreach($automations as $auto)
            <div x-data="{ on: {{ $auto['enabled'] ? 'true' : 'false' }} }"
                 :class="on ? 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/10' : 'border-gray-200 dark:border-gray-700'"
                 class="p-4 rounded-xl border-2 transition-colors">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $auto['label'] }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $auto['desc'] }}</p>
                    </div>
                    <button @click="on = !on" type="button"
                        :class="on ? 'bg-green-600' : 'bg-gray-300 dark:bg-gray-600'"
                        class="relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none">
                        <span :class="on ? 'translate-x-5' : 'translate-x-0'"
                              class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                    </button>
                </div>
                <div class="mt-3">
                    <span :class="on ? 'text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/30' : 'text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400'"
                          class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full" :class="on ? 'bg-green-500' : 'bg-gray-400'"></span>
                        <span x-text="on ? 'Activo' : 'Inactivo'"></span>
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Save Button --}}
    <div class="flex justify-end pt-2">
        <button type="button"
            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-sm font-semibold bg-green-600 hover:bg-green-700 text-white shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            Guardar configuración
        </button>
    </div>

</div>
@endsection
