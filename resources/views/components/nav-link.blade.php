@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 rounded-lg bg-ink-100 px-3 py-2 text-sm font-medium text-ink-900 transition'
            : 'inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-ink-500 transition hover:bg-ink-50 hover:text-ink-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if ($active ?? false) aria-current="page" @endif>
    {{ $slot }}
</a>
