@extends('layouts.app')

@section('title', 'Sin acceso - ' . config('app.name'))
@section('mobileTitle', 'Sin acceso')

@section('content')
<div class="flex items-center justify-center min-h-[60vh] p-6">
    <div class="max-w-md w-full text-center">
        <!-- Icono -->
        <div class="flex justify-center mb-6">
            <div class="flex items-center justify-center w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full">
                <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
        </div>

        <!-- Título -->
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">403</h1>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-3">Acceso denegado</h2>

        <!-- Mensaje -->
        <p class="text-gray-500 dark:text-gray-400 mb-8">
            {{ $exception->getMessage() ?: 'No tiene permisos para acceder a esta sección.' }}
        </p>

        <!-- Acciones -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center justify-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Ir al Dashboard
            </a>
            <button onclick="history.back()"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
                Volver
            </button>
        </div>
    </div>
</div>
@endsection
