@props([
    'type' => 'submit',
    'as' => 'button',
    'outline' => false,
])

@php
    $classes = $outline
        ? 'text-primary-500 bg-white border border-primary-500 rounded-lg text-sm'
        : 'bg-primary-500 border border-primary-500 text-white rounded-lg text-sm';
@endphp

@if ($as === 'button')
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@else
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@endif
