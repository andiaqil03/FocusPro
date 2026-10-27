@props(['priority'])

@php
    $style = [
        'P1' => ['chip-red', 'Urgent'],
        'P2' => ['chip-amber', 'High'],
        'P3' => ['chip-blue', 'Medium'],
        'P4' => ['chip-neutral', 'Low'],
    ][$priority] ?? ['chip-neutral', $priority];
@endphp

<span {{ $attributes->merge(['class' => $style[0]]) }} title="Priority {{ $priority }}">
    <x-icon name="flag" class="h-3 w-3" />
    {{ $priority }} <span class="hidden sm:inline">· {{ $style[1] }}</span>
</span>
