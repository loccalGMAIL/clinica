@extends('layouts.app')

@section('title', 'Reiniciar Demo')

@section('content')
<div class="p-6 max-w-lg mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <h1 class="text-xl font-semibold text-gray-900">Reiniciar Demo</h1>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <p class="text-sm text-red-800 font-medium mb-1">Esta acción no se puede deshacer</p>
            <p class="text-sm text-red-700">Se borrarán <strong>todos los datos</strong> del sistema y se restaurará el estado inicial de la demo. Todos los usuarios serán desconectados.</p>
        </div>

        <p class="text-sm text-gray-600 mb-6">
            Se ejecutará <code class="bg-gray-100 px-1 rounded text-xs font-mono">migrate:fresh --seed</code> y serás redirigido al login.
        </p>

        <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('demo.reset.execute') }}">
                @csrf
                <button type="submit"
                    class="px-5 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    Confirmar reset
                </button>
            </form>
            <a href="{{ route('dashboard') }}"
                class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                Cancelar
            </a>
        </div>
    </div>
</div>
@endsection
