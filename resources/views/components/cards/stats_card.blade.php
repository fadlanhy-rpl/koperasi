@props([
    'title',
    'value',
    'icon',
    'color' => 'blue', // Default color
    'trend' => null,
    'trendDirection' => 'up', // 'up' or 'down'
    'progress' => null, // Percentage 0-100
    'delay' => '0s',
    'targetValue' => null, // Untuk animasi counter jika value adalah string (misal Rp 2.5M)
    'isCurrency' => false // Tandai jika value adalah mata uang untuk formatting di JS counter
])

<div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 animate-bounce-in" style="animation-delay: {{ $delay }};">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600 mb-1">{{ $title }}</p>
            <p class="text-3xl font-bold stat-number" 
               data-target="{{ $targetValue ?? (is_numeric(str_replace(['.', ','], '', $value)) ? str_replace(['.', ','], '', $value) : 0) }}"
               data-is-currency="{{ $isCurrency ? 'true' : 'false' }}">
                {{-- Tampilkan nilai awal sebelum JS counter, atau nilai jika bukan angka --}}
                {{ $isCurrency ? 'Rp 0' : (is_numeric(str_replace(['.', ','], '', $value)) ? '0' : $value) }}
            </p>
            @if($trend)
                <div class="flex items-center mt-2">
                    <i class="fas fa-arrow-{{ $trendDirection }} text-{{ $trendDirection == 'up' ? 'green' : 'red' }}-500 text-xs mr-1"></i>
                    <span class="text-sm text-{{ $trendDirection == 'up' ? 'green' : 'red' }}-600 font-medium">{{ $trend }}</span>
                </div>
            @endif
        </div>
        <div class="w-16 h-16 bg-gradient-to-br from-{{ $color }}-400 to-{{ $color }}-600 rounded-2xl flex items-center justify-center shadow-md">
            <i class="fas fa-{{ $icon }} text-white text-2xl"></i>
        </div>
    </div>
    @if($progress !== null)
        <div class="mt-4 bg-gray-200 rounded-full h-2.5 overflow-hidden">
            <div class="h-full bg-gradient-to-r from-{{ $color }}-400 to-{{ $color }}-600 rounded-full progress-bar-manual" style="width: {{ $progress }}%;"></div>
        </div>
    @endif
</div>