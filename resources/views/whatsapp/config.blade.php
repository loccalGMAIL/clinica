@extends('layouts.app')

@section('title', 'WhatsApp - Configuración - ' . config('app.name'))

@section('mobileTitle', 'WhatsApp')

@section('content')
<div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configuración de WhatsApp</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gestiona la conexión con WhatsApp Business</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
            Desconectado
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- QR Code Card (spans 2 cols) --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Conectar dispositivo</h2>

            {{-- QR Placeholder --}}
            <div class="flex flex-col items-center gap-4">
                <div class="w-56 h-56 p-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900">
                    <svg viewBox="0 0 200 200" class="w-full h-full text-gray-900 dark:text-white" fill="currentColor">
                        {{-- Top-left finder pattern --}}
                        <rect x="10" y="10" width="60" height="60" rx="4" fill="none" stroke="currentColor" stroke-width="8"/>
                        <rect x="26" y="26" width="28" height="28" rx="2"/>
                        {{-- Top-right finder pattern --}}
                        <rect x="130" y="10" width="60" height="60" rx="4" fill="none" stroke="currentColor" stroke-width="8"/>
                        <rect x="146" y="26" width="28" height="28" rx="2"/>
                        {{-- Bottom-left finder pattern --}}
                        <rect x="10" y="130" width="60" height="60" rx="4" fill="none" stroke="currentColor" stroke-width="8"/>
                        <rect x="26" y="146" width="28" height="28" rx="2"/>
                        {{-- Data modules --}}
                        <rect x="86" y="10" width="10" height="10"/>
                        <rect x="100" y="10" width="10" height="10"/>
                        <rect x="86" y="24" width="10" height="10"/>
                        <rect x="114" y="24" width="10" height="10"/>
                        <rect x="100" y="38" width="10" height="10"/>
                        <rect x="86" y="52" width="10" height="10"/>
                        <rect x="114" y="52" width="10" height="10"/>
                        <rect x="86" y="86" width="10" height="10"/>
                        <rect x="100" y="86" width="10" height="10"/>
                        <rect x="114" y="86" width="10" height="10"/>
                        <rect x="86" y="100" width="10" height="10"/>
                        <rect x="114" y="100" width="10" height="10"/>
                        <rect x="86" y="114" width="10" height="10"/>
                        <rect x="100" y="114" width="10" height="10"/>
                        <rect x="130" y="86" width="10" height="10"/>
                        <rect x="144" y="86" width="10" height="10"/>
                        <rect x="158" y="86" width="10" height="10"/>
                        <rect x="130" y="100" width="10" height="10"/>
                        <rect x="172" y="100" width="10" height="10"/>
                        <rect x="144" y="114" width="10" height="10"/>
                        <rect x="158" y="114" width="10" height="10"/>
                        <rect x="130" y="130" width="10" height="10"/>
                        <rect x="144" y="144" width="10" height="10"/>
                        <rect x="158" y="130" width="10" height="10"/>
                        <rect x="172" y="144" width="10" height="10"/>
                        <rect x="130" y="158" width="10" height="10"/>
                        <rect x="144" y="172" width="10" height="10"/>
                        <rect x="158" y="158" width="10" height="10"/>
                        <rect x="172" y="172" width="10" height="10"/>
                        <rect x="10" y="86" width="10" height="10"/>
                        <rect x="24" y="100" width="10" height="10"/>
                        <rect x="38" y="86" width="10" height="10"/>
                        <rect x="52" y="100" width="10" height="10"/>
                        <rect x="10" y="114" width="10" height="10"/>
                        <rect x="52" y="114" width="10" height="10"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                    Escanea con WhatsApp Business<br>
                    <span class="text-xs">Abre WhatsApp → Dispositivos vinculados → Vincular dispositivo</span>
                </p>
            </div>

            {{-- Device Info --}}
            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Número vinculado</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1 italic">No conectado</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Última conexión</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1 italic">Nunca</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex items-center gap-3">
                <button type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-red-600 text-white opacity-50 cursor-not-allowed"
                    disabled>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    Desconectar
                </button>
                <button type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Actualizar QR
                </button>
            </div>
        </div>

        {{-- Sidebar Cards --}}
        <div class="flex flex-col gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Sobre este módulo</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Este módulo utiliza <strong class="text-gray-700 dark:text-gray-300">Evolution API</strong> para la conexión con WhatsApp Business. Permite enviar recordatorios y confirmaciones de turnos automáticamente.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Estado del servicio</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Evolution API</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Desconectado</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Cola de mensajes</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">Inactiva</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Mensajes hoy</span>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">0</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
