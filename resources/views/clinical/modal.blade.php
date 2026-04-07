<!-- Modal Historias Clínicas -->
<div x-show="modalOpen"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto">

    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="modalOpen = false"></div>

    <!-- Modal content -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"
                        x-text="viewMode ? 'Detalle del Registro' : 'Nueva Entrada Clínica'"></h3>
                </div>
                <button @click="modalOpen = false"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Vista de Detalle (read-only) -->
            <div x-show="viewMode && viewingRecord" class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Paciente -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Paciente</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white"
                           x-text="viewingRecord && viewingRecord.patient ? viewingRecord.patient.last_name + ', ' + viewingRecord.patient.first_name : '-'"></p>
                    </div>
                    <!-- Profesional -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Profesional</p>
                        <p class="text-sm text-gray-900 dark:text-white"
                           x-text="viewingRecord && viewingRecord.professional ? viewingRecord.professional.last_name + ', ' + viewingRecord.professional.first_name : '-'"></p>
                    </div>
                    <!-- Fecha -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Fecha</p>
                        <p class="text-sm font-mono text-gray-900 dark:text-white"
                           x-text="viewingRecord ? formatDate(viewingRecord.date) : '-'"></p>
                    </div>
                </div>

                <!-- Diagnóstico -->
                <div x-show="viewingRecord && viewingRecord.diagnosis">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Diagnóstico</p>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap"
                         x-text="viewingRecord ? viewingRecord.diagnosis : ''"></div>
                </div>

                <!-- Tratamiento -->
                <div x-show="viewingRecord && viewingRecord.treatment">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tratamiento indicado</p>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap"
                         x-text="viewingRecord ? viewingRecord.treatment : ''"></div>
                </div>

                <!-- Notas -->
                <div x-show="viewingRecord && viewingRecord.content">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Notas</p>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap"
                         x-text="viewingRecord ? viewingRecord.content : ''"></div>
                </div>

                <!-- Sin contenido -->
                <div x-show="viewingRecord && !viewingRecord.diagnosis && !viewingRecord.treatment && !viewingRecord.content"
                     class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                    Sin contenido registrado.
                </div>

                <!-- Registrado por -->
                <div class="pt-2 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-500">
                    <span x-show="viewingRecord && viewingRecord.creator">
                        Registrado por <span x-text="viewingRecord && viewingRecord.creator ? viewingRecord.creator.name : ''"></span>
                    </span>
                </div>
            </div>

            <!-- Formulario de Creación -->
            <form x-show="!viewMode" @submit.prevent="submitForm()">
                <div class="px-6 py-5 space-y-4">

                    <!-- Paciente (con búsqueda) -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Paciente <span class="text-red-500">*</span>
                        </label>
                        <input x-model="patientSearch"
                               @input="searchPatients()"
                               @blur="setTimeout(() => { patientResults = [] }, 200)"
                               type="text"
                               placeholder="Buscar por nombre o DNI..."
                               autocomplete="off"
                               :class="hasError('patient_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 dark:border-gray-600 focus:ring-emerald-500 focus:border-emerald-500'"
                               class="w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white text-sm">
                        <p x-show="hasError('patient_id')" x-text="getError('patient_id')" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>

                        <!-- Resultados de búsqueda -->
                        <div x-show="patientResults.length > 0"
                             class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            <template x-for="patient in patientResults" :key="patient.id">
                                <button type="button"
                                        @click="selectPatient(patient); clearError('patient_id')"
                                        class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 last:border-0">
                                    <span class="font-medium" x-text="patient.last_name + ', ' + patient.first_name"></span>
                                    <span x-show="patient.dni" class="text-gray-500 dark:text-gray-400 ml-2 font-mono text-xs" x-text="'DNI: ' + patient.dni"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Profesional -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Profesional <span class="text-red-500">*</span>
                        </label>
                        <select x-model="form.professional_id"
                                @change="clearError('professional_id')"
                                :class="hasError('professional_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 dark:border-gray-600 focus:ring-emerald-500 focus:border-emerald-500'"
                                class="w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white text-sm">
                            <option value="">Seleccionar profesional...</option>
                            @foreach($professionals as $prof)
                                <option value="{{ $prof->id }}">{{ $prof->last_name }}, {{ $prof->first_name }}</option>
                            @endforeach
                        </select>
                        <p x-show="hasError('professional_id')" x-text="getError('professional_id')" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha</label>
                        <input x-model="form.date" type="date"
                               :class="hasError('date') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 dark:border-gray-600 focus:ring-emerald-500 focus:border-emerald-500'"
                               class="w-full px-3 py-2 border rounded-md shadow-sm dark:bg-gray-700 dark:text-white text-sm">
                        <p x-show="hasError('date')" x-text="getError('date')" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                    </div>

                    <!-- Diagnóstico -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diagnóstico</label>
                        <textarea x-model="form.diagnosis" rows="3"
                                  placeholder="Diagnóstico de la consulta..."
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm resize-none"></textarea>
                    </div>

                    <!-- Tratamiento -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tratamiento indicado</label>
                        <textarea x-model="form.treatment" rows="3"
                                  placeholder="Tratamiento o indicaciones..."
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm resize-none"></textarea>
                    </div>

                    <!-- Notas libres -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas</label>
                        <textarea x-model="form.content" rows="3"
                                  placeholder="Observaciones y notas adicionales..."
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm resize-none"></textarea>
                    </div>

                    <!-- Aviso de inmutabilidad -->
                    <div class="flex items-start gap-2 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 rounded-lg">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <p class="text-xs text-amber-700 dark:text-amber-300">
                            Este registro no podrá editarse una vez guardado.
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" @click="modalOpen = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" :disabled="loading"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg x-show="loading" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Guardar Registro
                    </button>
                </div>
            </form>

            <!-- Footer view mode -->
            <div x-show="viewMode" class="flex justify-end px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" @click="modalOpen = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 rounded-lg transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
