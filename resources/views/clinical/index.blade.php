@extends('layouts.app')

@section('title', 'Historias Clínicas - ' . config('app.name'))
@section('mobileTitle', 'Hist. Clínicas')

@section('content')
<div class="flex h-full flex-1 flex-col gap-6 p-4" x-data="clinicalPage()">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
                <span>Historias Clínicas</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Historias Clínicas</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                <span x-text="pagination.total + ' paciente' + (pagination.total !== 1 ? 's' : '') + ' con registros'"></span>
            </p>
        </div>
        @can('create', App\Models\ClinicalRecord::class)
        <button @click="openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nueva Entrada
        </button>
        @endcan
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Paciente</label>
                <input x-model="filters.search" type="text"
                       placeholder="Nombre o DNI..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Profesional</label>
                <select x-model="filters.professional_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todos</option>
                    @foreach($professionals as $prof)
                        <option value="{{ $prof->id }}">{{ $prof->last_name }}, {{ $prof->first_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Desde</label>
                    <input x-model="filters.date_from" type="date"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Hasta</label>
                    <input x-model="filters.date_to" type="date"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
        </div>
        <div class="mt-3">
            <button x-show="hasActiveFilters" @click="clearFilters()"
                    class="text-sm text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 font-medium">
                Limpiar filtros
            </button>
        </div>
    </div>

    <!-- Loading -->
    <div x-show="loading" class="flex justify-center py-12">
        <svg class="animate-spin h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
    </div>

    <!-- Lista de pacientes -->
    <div x-show="!loading">

        <!-- Sin resultados -->
        <div x-show="patientGroups.length === 0"
             class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-10 text-center text-gray-500 dark:text-gray-400">
            No se encontraron historias clínicas.
        </div>

        <!-- Pacientes -->
        <div x-show="patientGroups.length > 0"
             class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm divide-y divide-gray-100 dark:divide-gray-700">

            <template x-for="group in patientGroups" :key="group.patient.id">
                <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">

                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                            <span class="text-sm font-semibold text-indigo-700 dark:text-indigo-400"
                                  x-text="group.patient.last_name.charAt(0).toUpperCase()"></span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"
                               x-text="group.patient.last_name + ', ' + group.patient.first_name"></p>
                            <div class="flex items-center gap-3 mt-0.5">
                                <span class="text-xs text-gray-500 dark:text-gray-400"
                                      x-text="'DNI ' + group.patient.dni"></span>
                                <template x-if="group.patient.health_insurance">
                                    <span class="text-xs text-gray-400 dark:text-gray-500"
                                          x-text="'· ' + group.patient.health_insurance"></span>
                                </template>
                                <span class="text-xs text-gray-400 dark:text-gray-500">·</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400"
                                      x-text="'Última: ' + formatDate(group.records[group.records.length - 1]?.date)"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 px-2 py-0.5 rounded-full"
                              x-text="group.records.length + (group.records.length === 1 ? ' atención' : ' atenciones')"></span>
                        <button @click="openHC(group)"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-md transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            Ver HC
                        </button>
                    </div>

                </div>
            </template>
        </div>

        <!-- Paginación -->
        <div x-show="pagination.last_page > 1" class="flex items-center justify-between mt-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Página <span x-text="pagination.current_page"></span> de <span x-text="pagination.last_page"></span>
            </div>
            <div class="flex gap-2">
                <button @click="goToPage(pagination.current_page - 1)"
                        :disabled="pagination.current_page <= 1"
                        :class="pagination.current_page <= 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-100 dark:hover:bg-gray-700'"
                        class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">
                    Anterior
                </button>
                <button @click="goToPage(pagination.current_page + 1)"
                        :disabled="pagination.current_page >= pagination.last_page"
                        :class="pagination.current_page >= pagination.last_page ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-100 dark:hover:bg-gray-700'"
                        class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">
                    Siguiente
                </button>
            </div>
        </div>

    </div>

    <!-- Modal HC del paciente -->
    <div x-show="hcModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-start justify-center bg-black/50 p-4 pt-10 overflow-y-auto">
        <div @click.outside="hcModalOpen = false"
             class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-4xl mb-8">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 rounded-t-xl z-10">
                <div x-show="selectedGroup">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white"
                        x-text="selectedGroup?.patient.last_name + ', ' + selectedGroup?.patient.first_name"></h2>
                    <div class="flex items-center gap-3 mt-0.5">
                        <span class="text-xs text-gray-500 dark:text-gray-400"
                              x-text="'DNI ' + selectedGroup?.patient.dni"></span>
                        <template x-if="selectedGroup?.patient.health_insurance">
                            <span class="text-xs text-gray-400 dark:text-gray-500"
                                  x-text="'· ' + selectedGroup?.patient.health_insurance"></span>
                        </template>
                        <span class="text-xs bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 px-2 py-0.5 rounded-full"
                              x-text="selectedGroup?.records.length + (selectedGroup?.records.length === 1 ? ' atención' : ' atenciones')"></span>
                    </div>
                </div>
                <button @click="hcModalOpen = false"
                        class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 ml-4 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Registros -->
            <div x-show="selectedGroup" class="divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden rounded-b-xl">
                <template x-for="record in selectedGroup?.records" :key="record.id">
                    <div class="flex divide-x divide-gray-100 dark:divide-gray-700">

                        <!-- Fecha + profesional -->
                        <div class="w-36 shrink-0 flex flex-col items-center justify-start pt-3 pb-3 px-2 bg-gray-50/60 dark:bg-gray-700/20 text-center">
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300 tabular-nums leading-tight"
                                  x-text="formatDateDM(record.date)"></span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 tabular-nums"
                                  x-text="formatYear(record.date)"></span>
                            <span class="mt-1.5 text-[10px] text-emerald-700 dark:text-emerald-400 leading-tight max-w-full truncate px-1 font-medium"
                                  x-text="record.professional ? record.professional.last_name : ''"></span>
                            <template x-if="record.appointment">
                                <span class="mt-0.5 inline-flex items-center gap-0.5 text-[10px] text-blue-400 dark:text-blue-500">
                                    <svg class="w-2.5 h-2.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                    </svg>
                                    Turno
                                </span>
                            </template>
                        </div>

                        <!-- Contenido -->
                        <div class="flex-1 min-w-0 px-4 py-3 space-y-1.5">
                            <div x-show="record.diagnosis || record.treatment" class="flex flex-wrap items-center gap-2">
                                <template x-if="record.diagnosis">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/40 px-2 py-0.5 rounded">
                                        <span class="font-normal text-blue-400 dark:text-blue-500">Dx</span>
                                        <span x-text="record.diagnosis"></span>
                                    </span>
                                </template>
                                <template x-if="record.treatment">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/40 px-2 py-0.5 rounded">
                                        <span class="font-normal text-emerald-400 dark:text-emerald-500">Tto</span>
                                        <span x-text="truncate(record.treatment, 80)"></span>
                                    </span>
                                </template>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-snug"
                               x-text="record.content"></p>
                        </div>

                        <!-- Acción eliminar -->
                        <div class="w-10 shrink-0 flex items-center justify-center">
                            @if(Auth::user()->canAccessModule('configuration'))
                            <button @click="deleteRecord(record, selectedGroup)"
                                    class="p-1.5 text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                            @endif
                        </div>

                    </div>
                </template>
            </div>

        </div>
    </div>

    @include('clinical.modal')
</div>

@push('scripts')
<script>
function clinicalPage() {
    return {
        patientGroups: @json($patientGroups),
        professionals: @json($professionals),
        pagination: {
            current_page: {{ $patients->currentPage() }},
            last_page:    {{ $patients->lastPage() }},
            per_page:     {{ $patients->perPage() }},
            total:        {{ $patients->total() }},
        },

        loading: false,
        hcModalOpen: false,
        selectedGroup: null,
        modalOpen: false,
        viewMode: false,
        viewingRecord: null,
        formErrors: {},

        filters: {
            search: '',
            professional_id: '',
            date_from: '',
            date_to: '',
        },
        searchTimeout: null,
        currentPage: 1,

        form: {
            patient_id: '',
            professional_id: '',
            appointment_id: '',
            date: new Date().toISOString().split('T')[0],
            content: '',
            diagnosis: '',
            treatment: '',
        },

        patientSearch: '',
        patientResults: [],
        patientSearchTimeout: null,
        selectedPatient: null,

        get hasActiveFilters() {
            return this.filters.search !== '' ||
                   this.filters.professional_id !== '' ||
                   this.filters.date_from !== '' ||
                   this.filters.date_to !== '';
        },

        init() {
            this.$watch('filters.search', () => {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => { this.currentPage = 1; this.fetchRecords(); }, 500);
            });
            this.$watch('filters.professional_id', () => { this.currentPage = 1; this.fetchRecords(); });
            this.$watch('filters.date_from',       () => { this.currentPage = 1; this.fetchRecords(); });
            this.$watch('filters.date_to',         () => { this.currentPage = 1; this.fetchRecords(); });
        },

        async fetchRecords() {
            this.loading = true;
            try {
                const params = new URLSearchParams();
                if (this.filters.search)          params.append('search', this.filters.search);
                if (this.filters.professional_id) params.append('professional_id', this.filters.professional_id);
                if (this.filters.date_from)       params.append('date_from', this.filters.date_from);
                if (this.filters.date_to)         params.append('date_to', this.filters.date_to);
                if (this.currentPage > 1)         params.append('page', this.currentPage);

                const response = await fetch(`/clinical?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();
                this.patientGroups = data.patientGroups;
                this.pagination    = data.pagination;
            } catch (error) {
                console.error('Error fetching records:', error);
            } finally {
                this.loading = false;
            }
        },

        goToPage(page) {
            if (page < 1 || page > this.pagination.last_page) return;
            this.currentPage = page;
            this.fetchRecords();
        },

        clearFilters() {
            this.filters = { search: '', professional_id: '', date_from: '', date_to: '' };
            this.currentPage = 1;
        },

        openHC(group) {
            this.selectedGroup = group;
            this.hcModalOpen = true;
        },

        openCreateModal() {
            this.viewMode = false;
            this.viewingRecord = null;
            this.resetForm();
            this.clearAllErrors();
            this.patientSearch = '';
            this.patientResults = [];
            this.selectedPatient = null;
            this.modalOpen = true;
        },

        viewRecord(record) {
            this.viewingRecord = record;
            this.viewMode = true;
            this.modalOpen = true;
        },

        resetForm() {
            this.form = {
                patient_id: '', professional_id: '', appointment_id: '',
                date: new Date().toISOString().split('T')[0],
                content: '', diagnosis: '', treatment: '',
            };
        },

        async searchPatients() {
            clearTimeout(this.patientSearchTimeout);
            if (this.patientSearch.length < 2) { this.patientResults = []; return; }
            this.patientSearchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`/patients?search=${encodeURIComponent(this.patientSearch)}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    });
                    const data = await response.json();
                    this.patientResults = data.patients || [];
                } catch (e) { this.patientResults = []; }
            }, 300);
        },

        selectPatient(patient) {
            this.selectedPatient = patient;
            this.form.patient_id = patient.id;
            this.patientSearch = patient.last_name + ', ' + patient.first_name + (patient.dni ? ' (' + patient.dni + ')' : '');
            this.patientResults = [];
        },

        async submitForm() {
            if (!this.form.patient_id)      this.formErrors.patient_id     = ['Debe seleccionar un paciente.'];
            if (!this.form.professional_id) this.formErrors.professional_id = ['Debe seleccionar un profesional.'];
            if (this.formErrors.patient_id || this.formErrors.professional_id) return;

            this.loading = true;
            try {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('patient_id',      this.form.patient_id);
                formData.append('professional_id', this.form.professional_id);
                formData.append('date',            this.form.date);
                if (this.form.appointment_id) formData.append('appointment_id', this.form.appointment_id);
                if (this.form.content)   formData.append('content',   this.form.content);
                if (this.form.diagnosis) formData.append('diagnosis', this.form.diagnosis);
                if (this.form.treatment) formData.append('treatment', this.form.treatment);

                const response = await fetch('/clinical', {
                    method: 'POST', body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();

                if (response.ok && result.success) {
                    this.modalOpen = false;
                    this.fetchRecords();
                    window.showToast(result.message, 'success');
                } else {
                    this.setErrors(result.errors || {});
                    window.showToast(result.message || 'Error al guardar.', 'error');
                }
            } catch (e) {
                window.showToast('Error de conexión.', 'error');
            } finally {
                this.loading = false;
            }
        },

        async deleteRecord(record, group) {
            const confirmed = await SystemModal.confirm(
                'Eliminar registro clínico',
                'Esta acción no se puede deshacer. ¿Confirma la eliminación?',
                'Eliminar', 'Cancelar'
            );
            if (!confirmed) return;

            try {
                const response = await fetch(`/clinical/${record.id}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        '_method': 'DELETE',
                        '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    })
                });
                const result = await response.json();
                if (result.success) {
                    group.records = group.records.filter(r => r.id !== record.id);
                    if (group.records.length === 0) {
                        this.patientGroups = this.patientGroups.filter(g => g.patient.id !== group.patient.id);
                        this.pagination.total = Math.max(0, this.pagination.total - 1);
                        this.hcModalOpen = false;
                        this.selectedGroup = null;
                    }
                    window.showToast(result.message, 'success');
                } else {
                    window.showToast('No se pudo eliminar el registro.', 'error');
                }
            } catch (e) {
                window.showToast('Error de conexión.', 'error');
            }
        },

        formatDateDM(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr + 'T00:00:00');
            return d.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit' });
        },

        formatYear(dateStr) {
            if (!dateStr) return '';
            return new Date(dateStr + 'T00:00:00').getFullYear();
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr + 'T00:00:00');
            return d.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit', year: 'numeric' });
        },

        truncate(str, length) {
            if (!str) return '';
            return str.length > length ? str.substring(0, length) + '…' : str;
        },

        hasError(field)   { return !!(this.formErrors[field]?.length); },
        getError(field)   { return this.formErrors[field]?.[0] ?? ''; },
        clearError(field) { delete this.formErrors[field]; },
        setErrors(errors) { this.formErrors = errors; },
        clearAllErrors()  { this.formErrors = {}; },
    };
}
</script>
@endpush
@endsection
