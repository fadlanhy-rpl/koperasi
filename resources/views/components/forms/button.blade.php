@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, success, neutral
    'size' => 'md', // sm, md, lg
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'disabled' => false,
    'pill' => false, // Untuk rounded-full
    'outlined' => false // Untuk tombol outline
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 ease-in-out transform focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variantClasses = '';
    if ($outlined) {
        switch ($variant) {
            case 'primary': $variantClasses = 'border-2 border-blue-500 text-blue-600 hover:bg-blue-500 hover:text-white focus:ring-blue-400'; break;
            case 'secondary': $variantClasses = 'border-2 border-gray-300 text-gray-700 hover:bg-gray-100 focus:ring-gray-400'; break;
            case 'danger': $variantClasses = 'border-2 border-red-500 text-red-600 hover:bg-red-500 hover:text-white focus:ring-red-400'; break;
            case 'success': $variantClasses = 'border-2 border-green-500 text-green-600 hover:bg-green-500 hover:text-white focus:ring-green-400'; break;
            default: $variantClasses = 'border-2 border-gray-500 text-gray-600 hover:bg-gray-500 hover:text-white focus:ring-gray-400'; // neutral outline
        }
    } else {
        switch ($variant) {
            case 'primary': $variantClasses = 'bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:from-blue-600 hover:to-purple-700 focus:ring-purple-400 shadow-md hover:shadow-lg'; break;
            case 'secondary': $variantClasses = 'bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-gray-400 shadow-sm hover:shadow-md'; break;
            case 'danger': $variantClasses = 'bg-gradient-to-r from-red-500 to-pink-600 text-white hover:from-red-600 hover:to-pink-700 focus:ring-pink-400 shadow-md hover:shadow-lg'; break;
            case 'success': $variantClasses = 'bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 focus:ring-emerald-400 shadow-md hover:shadow-lg'; break;
            default: $variantClasses = 'bg-gray-500 text-white hover:bg-gray-600 focus:ring-gray-400 shadow-sm hover:shadow-md'; // neutral
        }
    }
    
    $sizeClasses = '';
    switch ($size) {
        case 'sm': $sizeClasses = 'px-3 py-1.5 text-xs'; break;
        case 'md': $sizeClasses = 'px-5 py-2.5 text-sm'; break; // Disesuaikan dengan desain form
        case 'lg': $sizeClasses = 'px-7 py-3 text-base'; break;
    }

    $roundedClasses = $pill ? 'rounded-full' : 'rounded-xl';
    
    $classes = trim($baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses . ' ' . $roundedClasses);
    if (!$disabled) {
        $classes .= ' hover:scale-105'; // Hanya tambahkan hover:scale jika tidak disabled
    }
@endphp

<button 
    type="{{ $type }}" 
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($icon && $iconPosition === 'left')
        <i class="fas fa-{{ $icon }} {{ $slot->isNotEmpty() ? 'mr-2' : '' }} -ml-0.5 h-5 w-5"></i>
    @endif

    {{ $slot }}

    @if($icon && $iconPosition === 'right')
        <i class="fas fa-{{ $icon }} {{ $slot->isNotEmpty() ? 'ml-2' : '' }} -mr-0.5 h-5 w-5"></i>
    @endif
</button>