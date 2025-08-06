@props([
    'name' => 'currency',
    'value' => null,
    'required' => false,
    'class' => '',
    'id' => null,
    'placeholder' => 'اختر العملة'
])

@php
    $currencies = [
        'IQD' => 'دينار عراقي (IQD)',
        'USD' => 'دولار أمريكي (USD)'
    ];
    $selectedValue = old($name, $value ?? 'IQD');
@endphp

<select 
    name="{{ $name }}" 
    id="{{ $id ?? $name }}"
    class="form-control {{ $class }}"
    {{ $required ? 'required' : '' }}
    {{ $attributes }}
>
    @if(!$required)
        <option value="">{{ $placeholder }}</option>
    @endif
    
    @foreach($currencies as $code => $label)
        <option value="{{ $code }}" {{ $selectedValue == $code ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>
