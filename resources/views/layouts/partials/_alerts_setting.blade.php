{{-- resources/views/layouts/partials/_alerts.blade.php --}}
@if(session('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-2xl p-4 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-green-800 dark:text-green-200">Berhasil!</h4>
                    <p class="text-green-700 dark:text-green-300 text-sm">{{ session('success') }}</p>
                </div>
            </div>
            <button @click="show = false" class="text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-red-800 dark:text-red-200">Terjadi Kesalahan!</h4>
                    <p class="text-red-700 dark:text-red-300 text-sm">{{ session('error') }}</p>
                </div>
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('warning'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="mb-6 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-800 rounded-2xl p-4 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-yellow-500 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-circle text-white"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-yellow-800 dark:text-yellow-200">Peringatan!</h4>
                    <p class="text-yellow-700 dark:text-yellow-300 text-sm">{{ session('warning') }}</p>
                </div>
            </div>
            <button @click="show = false" class="text-yellow-500 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('info'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-4 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-blue-800 dark:text-blue-200">Informasi</h4>
                    <p class="text-blue-700 dark:text-blue-300 text-sm">{{ session('info') }}</p>
                </div>
            </div>
            <button @click="show = false" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif
