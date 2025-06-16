<footer class="bg-white/50 backdrop-blur-sm border-t border-gray-200 text-center py-4 {{ Auth::check() ? 'lg:ml-64' : '' }}">
    <p class="text-sm text-gray-600">
        © {{ date('Y') }} {{ config('app.name', 'Koperasi Management System') }}. All rights reserved.
    </p>
</footer>