{{-- resources/views/components/forms/input.blade.php --}}
@props([
    'type' => 'text',
    'name',
    'id' => null,
    'label' => null,
    'placeholder' => '',
    'required' => false,
    'value' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'readonly' => false,
    'disabled' => false,
    'helpText' => null,
])

@php
    $inputId = $id ?? $name;
    // Ambil old value, jika tidak ada, ambil value yang di-pass, jika tidak ada juga, string kosong
    $inputValue = old($name, $value ?? ''); 
    $commonClasses = 'w-full py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 placeholder-gray-400 disabled:bg-gray-100 disabled:cursor-not-allowed';
    $paddingClasses = ($icon && $iconPosition === 'left') ? 'pl-10 pr-4' : (($icon && $iconPosition === 'right') ? 'pr-10 pl-4' : 'px-4');
    $inputClasses = $commonClasses . ' ' . $paddingClasses;
@endphp

<div>
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative rounded-xl shadow-sm">
        @if($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-{{ $icon }} text-gray-400"></i>
            </div>
        @endif

        <input 
            type="{{ $type }}" 
            id="{{ $inputId }}" 
            name="{{ $name }}" 
            value="{{ $inputValue }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->except(['class', 'value', 'type', 'id', 'name', 'placeholder', 'required', 'readonly', 'disabled'])->merge(['class' => $inputClasses]) }}
        >

        @if($icon && $iconPosition === 'right')
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center"> 
                <i class="fas fa-{{ $icon }} text-gray-400"></i>
            </div>
        @endif
    </div>
    
    @if($helpText)
        <p class="mt-1.5 text-xs text-gray-500">{{ $helpText }}</p>
    @endif

    @error($name)
        <p class="text-red-500 text-sm mt-1.5 animate-bounce-in">{{ $message }}</p>
    @enderror
</div>