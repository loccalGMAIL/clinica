@extends('layouts.app')

@section('title', 'HC — ' . $patient->full_name . ' - ' . config('app.name'))
@section('mobileTitle', 'Historia Clínica')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 p-4" x-data="clinicalPatientPage()">

    <!-- Header -->
    <div class="flex items-start justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
                <a href="{{ route('professional.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                <a href="{{ route('professional.clinical') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Mis HCs</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                <span>{{ $patient->full_name }}</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $patient->full_name }}</h1>
            <div class="flex flex-wrap items-center gap-3 mt-1 text-sm text-gray-500 dark:text-gray-400">
                <span>DNI {{ $patient->dni }}</span>
                @if($patient->health_insurance)
                    <span class="text-gray-300 dark:text-gray-600">·</span>
                    <span>{{ $patient->health_insurance }}</span>
                @endif
                <span class="text-gray-300 dark:text-gray-600">·</span>
                <span>{{ $records->count() }} {{ $records->count() == 1 ? 'atención registrada' : 'atenciones registradas' }}</span>
            </div>
        </div>
        <button @click="modalOpen = true"
                class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span class="hidden sm:inline">Nueva entrada</span>
            <span class="sm:hidden">Nueva</span>
        </button>
    </div>

    <!-- Lista de atenciones -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        @forelse($records as $record)
            <div class="flex gap-0 divide-x divide-gray-100 dark:divide-gray-700 {{ !$loop->first ? 'border-t border-gray-100 dark:border-gray-700' : '' }}">

                <!-- Columna fecha -->
                <div class="w-28 shrink-0 flex flex-col items-center justify-start pt-3 pb-3 px-2 bg-gray-50/60 dark:bg-gray-700/20 text-center">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 tabular-nums leading-tight">
                        {{ $record->date->format('d/m') }}
                    </span>
                    <span class="text-xs text-gray-400 dark:text-gray-500 tabular-nums">
                        {{ $record->date->format('Y') }}
                    </span>
                    @if($record->appointment)
                        <span class="mt-1.5 inline-flex items-center gap-0.5 text-[10px] text-blue-500 dark:text-blue-400 leading-tight" title="Turno {{ $record->appointment->appointment_date->format('d/m/Y H:i') }}">
                            <svg class="w-2.5 h-2.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                            </svg>
                            {{ $record->appointment->appointment_date->format('H:i') }}
                        </span>
                    @endif
                </div>

                <!-- Columna contenido -->
                <div class="flex-1 min-w-0 px-4 py-3 space-y-1.5">
                    <!-- Badges: diagnóstico + tratamiento en línea -->
                    @if($record->diagnosis || $record->treatment)
                        <div class="flex flex-wrap items-center gap-2">
                            @if($record->diagnosis)
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/40 px-2 py-0.5 rounded">
                                    <span class="text-blue-400 dark:text-blue-500 font-normal">Dx</span>
                                    {{ $record->diagnosis }}
                                </span>
                            @endif
                            @if($record->treatment)
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/40 px-2 py-0.5 rounded">
                                    <span class="text-emerald-400 dark:text-emerald-500 font-normal">Tto</span>
                                    {{ Str::limit($record->treatment, 80) }}
                                </span>
                            @endif
                        </div>
                    @endif

                    <!-- Contenido de la consulta -->
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-snug">{{ $record->content }}</p>
                </div>

            </div>
        @empty
            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                No hay atenciones registradas.
            </div>
        @endforelse
    </div>

    <!-- Modal Nueva Entrada -->
    <div x-show="modalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.outside="modalOpen = false"
             class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Nueva entrada</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $patient->full_name }}</p>
                </div>
                <button @click="modalOpen = false" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-5 space-y-3">
                <input type="hidden" x-model="form.patient_id">

                <!-- Fila 1: Fecha + Diagnóstico -->
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Fecha *</label>
                        <input x-model="form.date" type="date"
                               :class="errors.date ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'"
                               class="w-full px-3 py-2 border rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                        <p x-show="errors.date" x-text="errors.date" class="mt-1 text-xs text-red-600"></p>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Diagnóstico</label>
                        <input x-model="form.diagnosis" type="text"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="Diagnóstico (opcional)">
                    </div>
                </div>

                <!-- Fila 2: Contenido + Tratamiento -->
                <div class="grid grid-cols-5 gap-3">
                    <div class="col-span-3">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Consulta *</label>
                        <textarea x-model="form.content" rows="6"
                                  :class="errors.content ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'"
                                  class="w-full px-3 py-2 border rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 resize-none"
                                  placeholder="Descripción de la consulta..."></textarea>
                        <p x-show="errors.content" x-text="errors.content" class="mt-1 text-xs text-red-600"></p>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tratamiento</label>
                        <textarea x-model="form.treatment" rows="6"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 resize-none"
                                  placeholder="Tratamiento (opcional)"></textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex gap-3 pt-1">
                    <button @click="modalOpen = false"
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700">Cancelar</button>
                    <button @click="save()"
                            :disabled="saving"
                            class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white rounded-lg text-sm font-medium">
                        <span x-show="!saving">Guardar entrada</span>
                        <span x-show="saving">Guardando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function clinicalPatientPage() {
    return {
        modalOpen: false,
        saving: false,
        errors: {},
        form: {
            patient_id: {{ $patient->id }},
            appointment_id: null,
            date: new Date().toISOString().split('T')[0],
            content: '',
            diagnosis: '',
            treatment: '',
        },

        init() {
            const params = new URLSearchParams(window.location.search);
            if (params.get('new') === '1') {
                const apptId = params.get('appointment_id');
                if (apptId) this.form.appointment_id = parseInt(apptId);
                this.modalOpen = true;
                window.history.replaceState({}, '', window.location.pathname);
            }
        },

        async save() {
            this.saving = true;
            this.errors = {};

            const formData = new FormData();
            formData.append('patient_id', this.form.patient_id);
            formData.append('date', this.form.date);
            formData.append('content', this.form.content);
            if (this.form.diagnosis) formData.append('diagnosis', this.form.diagnosis);
            if (this.form.treatment) formData.append('treatment', this.form.treatment);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            try {
                const resp = await fetch('{{ route("professional.clinical.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                const result = await resp.json();

                if (resp.ok && result.success) {
                    window.showToast(result.message, 'success');
                    this.modalOpen = false;
                    setTimeout(() => window.location.reload(), 600);
                } else if (resp.status === 422 && result.errors) {
                    Object.keys(result.errors).forEach(k => { this.errors[k] = result.errors[k][0]; });
                } else {
                    window.showToast(result.message || 'Error al guardar', 'error');
                }
            } catch (e) {
                window.showToast('Error al guardar', 'error');
            } finally {
                this.saving = false;
            }
        },
    };
}
</script>
@endsection
