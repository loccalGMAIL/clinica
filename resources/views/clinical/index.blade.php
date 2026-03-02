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
            <p class="text-gray-600 dark:text-gray-400">Registros clínicos de consultas</p>
        </div>
        @can('create', App\Models\ClinicalRecord::class)
        <button @click="openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nueva Entrada
        </button>
        @endcan
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-emerald-200/50 dark:border-emerald-800/30 shadow-sm">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v5.721c0 .926-.492 1.784-1.285 2.246l-.686.343a1.125 1.125 0 01-1.462-.396l-.423-.618a1.125 1.125 0 01-.194-.682v-5.938a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Filtros
                </h3>
                <button x-show="hasActiveFilters" @click="clearFilters()"
                        class="text-sm text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 font-medium">
                    Limpiar filtros
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Búsqueda paciente -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paciente</label>
                    <input x-model="filters.search" type="text"
                           placeholder="Nombre o DNI..."
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <!-- Profesional -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Profesional</label>
                    <select x-model="filters.professional_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">Todos los profesionales</option>
                        @foreach($professionals as $prof)
                            <option value="{{ $prof->id }}">{{ $prof->last_name }}, {{ $prof->first_name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Fecha desde -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Desde</label>
                    <input x-model="filters.date_from" type="date"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <!-- Fecha hasta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hasta</label>
                    <input x-model="filters.date_to" type="date"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm">
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-emerald-200/50 dark:border-emerald-800/30 shadow-sm">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
                Registros
                <span x-show="pagination.total > 0" class="text-sm font-normal text-gray-500 dark:text-gray-400"
                      x-text="'(' + pagination.total + ' en total)'"></span>
            </h3>

            <!-- Loading indicator -->
            <div x-show="loading" class="flex justify-center py-8">
                <svg class="animate-spin h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </div>

            <!-- Mobile Cards -->
            <div x-show="!loading" class="md:hidden space-y-3">
                <div x-show="records.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                    No se encontraron registros
                </div>
                <template x-for="record in records" :key="'m-'+record.id">
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="font-semibold text-sm text-gray-900 dark:text-white"
                                     x-text="record.patient ? record.patient.last_name + ', ' + record.patient.first_name : '-'"></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400"
                                     x-text="record.professional ? record.professional.last_name + ', ' + record.professional.first_name : '-'"></div>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-mono"
                                  x-text="formatDate(record.date)"></span>
                        </div>
                        <div x-show="record.diagnosis" class="text-xs text-gray-700 dark:text-gray-300 mb-1">
                            <span class="font-medium">Dx:</span>
                            <span x-text="truncate(record.diagnosis, 80)"></span>
                        </div>
                        <div x-show="record.content" class="text-xs text-gray-600 dark:text-gray-400 mb-2"
                             x-text="truncate(record.content, 80)"></div>
                        <div class="flex justify-end gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <button @click="viewRecord(record)"
                                    class="p-2 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg" title="Ver detalle">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            @if(Auth::user()->canAccessModule('configuration'))
                            <button @click="deleteRecord(record)"
                                    class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                </template>
            </div>

            <!-- Desktop Table -->
            <div x-show="!loading" class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-emerald-200/50 dark:divide-emerald-800/30">
                    <thead class="bg-emerald-50/50 dark:bg-emerald-950/20">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Paciente</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Profesional</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Diagnóstico</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Notas</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-emerald-200/30 dark:divide-emerald-800/30">
                        <tr x-show="records.length === 0">
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400">No se encontraron registros</p>
                                </div>
                            </td>
                        </tr>
                        <template x-for="record in records" :key="record.id">
                            <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20 transition-colors duration-200">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm font-mono text-gray-900 dark:text-white" x-text="formatDate(record.date)"></span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white"
                                          x-text="record.patient ? record.patient.last_name + ', ' + record.patient.first_name : '-'"></span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm text-gray-700 dark:text-gray-300"
                                          x-text="record.professional ? record.professional.last_name + ', ' + record.professional.first_name : '-'"></span>
                                </td>
                                <td class="px-4 py-3 max-w-xs">
                                    <span class="text-sm text-gray-600 dark:text-gray-400" x-text="truncate(record.diagnosis, 80)"></span>
                                </td>
                                <td class="px-4 py-3 max-w-xs">
                                    <span class="text-sm text-gray-600 dark:text-gray-400" x-text="truncate(record.content, 80)"></span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <div class="flex justify-end gap-1">
                                        <button @click="viewRecord(record)"
                                                class="p-1.5 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg" title="Ver detalle">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                        @if(Auth::user()->canAccessModule('configuration'))
                                        <button @click="deleteRecord(record)"
                                                class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div x-show="!loading" class="mt-4">
                <div x-show="pagination.last_page > 1" class="flex items-center justify-between">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
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
        </div>
    </div>

    @include('clinical.modal')
</div>

@push('scripts')
<script>
function clinicalPage() {
    return {
        records: @json($records->items()),
        professionals: @json($professionals),
        pagination: {
            current_page: {{ $records->currentPage() }},
            last_page:    {{ $records->lastPage() }},
            per_page:     {{ $records->perPage() }},
            total:        {{ $records->total() }},
        },

        // UI state
        loading: false,
        modalOpen: false,
        viewMode: false,
        viewingRecord: null,
        formErrors: {},

        // Filtros
        filters: {
            search: '',
            professional_id: '',
            date_from: '',
            date_to: '',
        },
        searchTimeout: null,
        currentPage: 1,

        // Formulario de creación
        form: {
            patient_id: '',
            professional_id: '',
            appointment_id: '',
            date: new Date().toISOString().split('T')[0],
            content: '',
            diagnosis: '',
            treatment: '',
        },

        // Búsqueda de paciente en el modal
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
                this.searchTimeout = setTimeout(() => {
                    this.currentPage = 1;
                    this.fetchRecords();
                }, 500);
            });
            this.$watch('filters.professional_id', () => { this.currentPage = 1; this.fetchRecords(); });
            this.$watch('filters.date_from', () => { this.currentPage = 1; this.fetchRecords(); });
            this.$watch('filters.date_to', () => { this.currentPage = 1; this.fetchRecords(); });
        },

        async fetchRecords() {
            this.loading = true;
            try {
                const params = new URLSearchParams();
                if (this.filters.search)         params.append('search', this.filters.search);
                if (this.filters.professional_id) params.append('professional_id', this.filters.professional_id);
                if (this.filters.date_from)       params.append('date_from', this.filters.date_from);
                if (this.filters.date_to)         params.append('date_to', this.filters.date_to);
                if (this.currentPage > 1)         params.append('page', this.currentPage);

                const response = await fetch(`/clinical?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();
                this.records = data.records;
                this.pagination = data.pagination;
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
                patient_id: '',
                professional_id: '',
                appointment_id: '',
                date: new Date().toISOString().split('T')[0],
                content: '',
                diagnosis: '',
                treatment: '',
            };
        },

        // Búsqueda de pacientes en el modal
        async searchPatients() {
            clearTimeout(this.patientSearchTimeout);
            if (this.patientSearch.length < 2) {
                this.patientResults = [];
                return;
            }
            this.patientSearchTimeout = setTimeout(async () => {
                try {
                    const params = new URLSearchParams({ search: this.patientSearch });
                    const response = await fetch(`/patients?${params.toString()}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    });
                    const data = await response.json();
                    this.patientResults = data.patients || [];
                } catch (error) {
                    this.patientResults = [];
                }
            }, 300);
        },

        selectPatient(patient) {
            this.selectedPatient = patient;
            this.form.patient_id = patient.id;
            this.patientSearch = patient.last_name + ', ' + patient.first_name + (patient.dni ? ' (' + patient.dni + ')' : '');
            this.patientResults = [];
        },

        async submitForm() {
            if (!this.form.patient_id || !this.form.professional_id) {
                if (!this.form.patient_id) this.formErrors.patient_id = ['Debe seleccionar un paciente.'];
                if (!this.form.professional_id) this.formErrors.professional_id = ['Debe seleccionar un profesional.'];
                return;
            }

            this.loading = true;
            try {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('patient_id', this.form.patient_id);
                formData.append('professional_id', this.form.professional_id);
                formData.append('date', this.form.date);
                if (this.form.appointment_id) formData.append('appointment_id', this.form.appointment_id);
                if (this.form.content)   formData.append('content',   this.form.content);
                if (this.form.diagnosis) formData.append('diagnosis', this.form.diagnosis);
                if (this.form.treatment) formData.append('treatment', this.form.treatment);

                const response = await fetch('/clinical', {
                    method: 'POST',
                    body: formData,
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
            } catch (error) {
                window.showToast('Error de conexión.', 'error');
            } finally {
                this.loading = false;
            }
        },

        async deleteRecord(record) {
            const confirmed = await SystemModal.confirm(
                'Eliminar registro clínico',
                'Esta acción no se puede deshacer. ¿Confirma la eliminación?',
                'Eliminar',
                'Cancelar'
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
                    this.fetchRecords();
                    window.showToast(result.message, 'success');
                } else {
                    window.showToast('No se pudo eliminar el registro.', 'error');
                }
            } catch (error) {
                window.showToast('Error de conexión.', 'error');
            }
        },

        // Helpers
        formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr + 'T00:00:00');
            return d.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit', year: 'numeric' });
        },

        truncate(str, length) {
            if (!str) return '';
            return str.length > length ? str.substring(0, length) + '…' : str;
        },

        // Error handling
        hasError(field) {
            return this.formErrors[field] && this.formErrors[field].length > 0;
        },

        getError(field) {
            return this.formErrors[field] ? this.formErrors[field][0] : '';
        },

        clearError(field) {
            delete this.formErrors[field];
        },

        setErrors(errors) {
            this.formErrors = errors;
        },

        clearAllErrors() {
            this.formErrors = {};
        },
    };
}
</script>
@endpush
@endsection
