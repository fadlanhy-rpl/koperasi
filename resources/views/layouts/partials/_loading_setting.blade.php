{{-- resources/views/layouts/partials/_loading.blade.php --}}
<div id="loading-screen" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center transition-opacity duration-500">
    <div class="text-center">
        <div class="relative">
            <div class="w-20 h-20 border-4 border-primary-200 dark:border-primary-800 border-t-primary-500 rounded-full animate-spin mx-auto mb-6"></div>
            <div class="absolute inset-0 w-20 h-20 border-4 border-transparent border-t-purple-500 rounded-full animate-spin mx-auto" style="animation-delay: -0.15s; animation-duration: 1.5s;"></div>
        </div>
        <div class="space-y-2">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 animate-pulse">Memuat Pengaturan</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm animate-pulse">Mohon tunggu sebentar...</p>
        </div>
    </div>
</div>
