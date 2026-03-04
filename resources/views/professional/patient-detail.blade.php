@extends('layouts.app')

@section('title', $patient->full_name . ' - ' . config('app.name'))
@section('mobileTitle', 'Detalle Paciente')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 p-4" x-data="patientDetailPage()">

    <!-- Header -->
    <div>
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <a href="{{ route('professional.patients') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Mis Pacientes</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span>{{ $patient->full_name }}</span>
        </nav>
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $patient->full_name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">DNI {{ $patient->dni }}</p>
            </div>
            <button @click="openClinicalModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nueva HC
            </button>
        </div>
    </div>

    <!-- Datos del paciente (read-only) -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Teléfono</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $patient->phone ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $patient->email ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Fecha nac.</p>
                <p class="font-medium text-gray-900 dark:text-white">
                    {{ $patient->birthday ? $patient->birthday->format('d/m/Y') : '-' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Obra social</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $patient->health_insurance ?: '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex gap-6">
            <button @click="tab = 'appointments'"
                    :class="tab === 'appointments' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-gray-500 dark:text-gray-400'"
                    class="py-3 text-sm font-medium border-b-2 transition-colors">
                Turnos ({{ $appointments->count() }})
            </button>
            <button @click="tab = 'clinical'"
                    :class="tab === 'clinical' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-gray-500 dark:text-gray-400'"
                    class="py-3 text-sm font-medium border-b-2 transition-colors">
                Historias Clínicas ({{ $clinicalRecords->count() }})
            </button>
        </nav>
    </div>

    <!-- Tab: Turnos -->
    <div x-show="tab === 'appointments'">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            @if($appointments->isEmpty())
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">Sin turnos registrados.</div>
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Hora</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($appointments as $appt)
                            @php
                                $sc = ['scheduled'=>'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400','attended'=>'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400','absent'=>'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400','cancelled'=>'bg-gray-100 text-gray-500'];
                                $sl = ['scheduled'=>'Programado','attended'=>'Atendido','absent'=>'Ausente','cancelled'=>'Cancelado'];
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $appt->appointment_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm font-mono text-gray-600 dark:text-gray-400">{{ $appt->appointment_date->format('H:i') }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $sc[$appt->status] ?? '' }}">{{ $sl[$appt->status] ?? $appt->status }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                                    @if($appt->final_amount) ${{ number_format($appt->final_amount, 2) }} @else - @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Tab: Historias Clínicas -->
    <div x-show="tab === 'clinical'">
        <div class="space-y-3">
            @forelse($clinicalRecords as $record)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $record->date->format('d/m/Y') }}
                        </span>
                        @if($record->diagnosis)
                            <span class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 px-2 py-0.5 rounded">
                                {{ Str::limit($record->diagnosis, 50) }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ Str::limit($record->content, 250) }}</p>
                    @if($record->treatment)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><strong>Tratamiento:</strong> {{ Str::limit($record->treatment, 100) }}</p>
                    @endif
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8 text-center text-gray-500 dark:text-gray-400">
                    No hay historias clínicas para este paciente.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Nueva HC -->
    <div x-show="clinicalModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.outside="clinicalModalOpen = false"
             class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Nueva Historia Clínica</h2>
                <button @click="clinicalModalOpen = false" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paciente</label>
                    <input type="text" value="{{ $patient->full_name }}" disabled
                           class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-md text-sm bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha *</label>
                    <input x-model="form.date" type="date"
                           :class="errors.date ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'"
                           class="w-full px-3 py-2 border rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                    <p x-show="errors.date" x-text="errors.date" class="mt-1 text-xs text-red-600"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contenido *</label>
                    <textarea x-model="form.content" rows="4"
                              :class="errors.content ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'"
                              class="w-full px-3 py-2 border rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                              placeholder="Descripción de la consulta..."></textarea>
                    <p x-show="errors.content" x-text="errors.content" class="mt-1 text-xs text-red-600"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diagnóstico</label>
                    <input x-model="form.diagnosis" type="text"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                           placeholder="Diagnóstico (opcional)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tratamiento</label>
                    <textarea x-model="form.treatment" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                              placeholder="Tratamiento indicado (opcional)"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button @click="clinicalModalOpen = false"
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button @click="saveClinical()"
                            :disabled="saving"
                            class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white rounded-lg text-sm font-medium">
                        <span x-show="!saving">Guardar HC</span>
                        <span x-show="saving">Guardando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function patientDetailPage() {
    return {
        tab: 'appointments',
        clinicalModalOpen: false,
        saving: false,
        errors: {},
        form: {
            patient_id: {{ $patient->id }},
            date: '{{ now()->toDateString() }}',
            content: '',
            diagnosis: '',
            treatment: '',
        },

        openClinicalModal() {
            this.errors = {};
            this.form.content = '';
            this.form.diagnosis = '';
            this.form.treatment = '';
            this.form.date = new Date().toISOString().split('T')[0];
            this.clinicalModalOpen = true;
        },

        async saveClinical() {
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
                    method: 'POST', body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await resp.json();

                if (resp.ok && result.success) {
                    window.showToast(result.message, 'success');
                    this.clinicalModalOpen = false;
                    setTimeout(() => window.location.reload(), 600);
                } else if (resp.status === 422 && result.errors) {
                    Object.keys(result.errors).forEach(k => { this.errors[k] = result.errors[k][0]; });
                } else {
                    window.showToast(result.message || 'Error al guardar', 'error');
                }
            } catch (e) {
                window.showToast('Error al guardar la historia clínica', 'error');
            } finally {
                this.saving = false;
            }
        },
    };
}
</script>
@endsection
