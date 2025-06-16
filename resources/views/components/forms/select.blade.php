@props([
    'name',
    'id' => null,
    'label' => null,
    'options' => [], // Array asosiatif [value => label] atau collection
    'required' => false,
    'value' => null,
    'placeholder' => 'Pilih salah satu...',
    'disabled' => false,
    'isSelect2' => false, // Tambahkan ini jika ingin styling khusus untuk Select2
    'helpText' => null,
    'multiple' => false, // Untuk select multiple
])

@php
    $selectId = $id ?? $name;
    $value = old($name, $value);
    $baseClasses = 'w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed';
    if ($isSelect2) {
        // Select2 akan menghandle stylingnya sendiri, class ini hanya untuk identifikasi
        $baseClasses .= ' select2-basic-hook'; // Hook untuk JS Select2
    }
@endphp

<div>
    @if($label)
        <label for="{{ $selectId }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if($required && !$multiple) {{-- Tanda bintang tidak umum untuk multiple select --}}
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select 
        id="{{ $selectId }}" 
        name="{{ $name }}{{ $multiple ? '[]' : '' }}" {{-- Tambah [] untuk nama jika multiple --}}
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes->merge(['class' => $baseClasses]) }}
    >
        @if(!$multiple && $placeholder)
            <option value="" {{ $value === null || $value === '' ? 'selected' : '' }} disabled>{{ $placeholder }}</option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" 
                    {{-- Untuk multiple, old() akan array. Untuk single, bisa string/int --}}
                    @if($multiple)
                        {{ in_array($optionValue, (array)$value) ? 'selected' : '' }}
                    @else
                        {{ (string)$value == (string)$optionValue ? 'selected' : '' }}
                    @endif
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    
    @if($helpText)
        <p class="mt-1.5 text-xs text-gray-500">{{ $helpText }}</p>
    @endif

    @error($name) {{-- Untuk multiple, error mungkin pada $name tanpa [] --}}
        <p class="text-red-500 text-sm mt-1.5 animate-bounce-in">{{ $message }}</p>
    @enderror
    @if($multiple && $errors->has(str_replace('[]', '', $name) . '.*'))
         <p class="text-red-500 text-sm mt-1.5 animate-bounce-in">{{ $errors->first(str_replace('[]', '', $name) . '.*') }}</p>
    @endif
</div>