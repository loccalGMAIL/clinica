{{-- Modal de gestión de acceso (cuenta de usuario) de un profesional --}}
<div x-show="accountModalOpen"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">

    <div @click.outside="accountModalOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">

        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Gestionar Acceso</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="accountData.professional?.name"></p>
            </div>
            <button @click="accountModalOpen = false"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Loading -->
        <div x-show="accountLoading" class="p-8 flex justify-center">
            <svg class="animate-spin w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>

        <!-- Sin cuenta: formulario crear -->
        <div x-show="!accountLoading && !accountData.has_account" class="p-6 space-y-4">
            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                <span>Este profesional no tiene cuenta de acceso al portal.</span>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email de acceso</label>
                <input x-model="accountForm.email"
                       type="email"
                       placeholder="email@ejemplo.com"
                       :class="accountErrors.email ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'"
                       class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm">
                <p x-show="accountErrors.email" x-text="accountErrors.email" class="mt-1 text-xs text-red-600"></p>
            </div>

            <!-- Contraseña -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña</label>
                <input x-model="accountForm.password"
                       type="password"
                       placeholder="Mínimo 8 caracteres"
                       :class="accountErrors.password ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'"
                       class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm">
                <p x-show="accountErrors.password" x-text="accountErrors.password" class="mt-1 text-xs text-red-600"></p>
            </div>

            <!-- Confirmar contraseña -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar contraseña</label>
                <input x-model="accountForm.password_confirmation"
                       type="password"
                       placeholder="Repetir contraseña"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm">
            </div>

            <div class="flex gap-3 pt-2">
                <button @click="accountModalOpen = false"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancelar
                </button>
                <button @click="saveAccount()"
                        :disabled="accountSaving"
                        class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white rounded-lg text-sm font-medium">
                    <span x-show="!accountSaving">Crear cuenta</span>
                    <span x-show="accountSaving">Guardando...</span>
                </button>
            </div>
        </div>

        <!-- Con cuenta: gestión -->
        <div x-show="!accountLoading && accountData.has_account" class="p-6 space-y-4">
            <!-- Estado actual -->
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">Email de acceso</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="accountData.account?.email"></p>
                </div>
                <span :class="accountData.account?.is_active
                              ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400'
                              : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'"
                      class="text-xs font-medium px-2 py-1 rounded-full"
                      x-text="accountData.account?.is_active ? 'Activo' : 'Inactivo'">
                </span>
            </div>

            <!-- Toggle activo/inactivo -->
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700 dark:text-gray-300">Acceso habilitado</span>
                <button @click="toggleAccountActive()"
                        :class="accountData.account?.is_active
                                ? 'bg-emerald-600'
                                : 'bg-gray-300 dark:bg-gray-600'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <span :class="accountData.account?.is_active ? 'translate-x-6' : 'translate-x-1'"
                          class="inline-block h-4 w-4 transform bg-white rounded-full transition-transform"></span>
                </button>
            </div>

            <!-- Cambiar contraseña -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Cambiar contraseña</p>
                <div class="space-y-3">
                    <input x-model="accountForm.password"
                           type="password"
                           placeholder="Nueva contraseña (mínimo 8 caracteres)"
                           :class="accountErrors.password ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'"
                           class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm">
                    <p x-show="accountErrors.password" x-text="accountErrors.password" class="text-xs text-red-600"></p>
                    <input x-model="accountForm.password_confirmation"
                           type="password"
                           placeholder="Confirmar nueva contraseña"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white text-sm">
                    <button @click="saveAccount()"
                            :disabled="accountSaving || !accountForm.password"
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white rounded-lg text-sm font-medium">
                        <span x-show="!accountSaving">Actualizar contraseña</span>
                        <span x-show="accountSaving">Guardando...</span>
                    </button>
                </div>
            </div>

            <!-- Desvincular -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between items-center">
                <button @click="accountModalOpen = false"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cerrar
                </button>
                <button @click="unlinkAccount()"
                        :disabled="accountSaving"
                        class="px-4 py-2 border border-red-300 dark:border-red-700 text-red-600 dark:text-red-400 rounded-lg text-sm hover:bg-red-50 dark:hover:bg-red-900/20">
                    Desvincular cuenta
                </button>
            </div>
        </div>
    </div>
</div>
