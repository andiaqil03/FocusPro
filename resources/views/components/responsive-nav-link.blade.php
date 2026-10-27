@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex w-full items-center gap-3 rounded-xl bg-ink-100 px-3 py-2.5 text-base font-medium text-ink-900 transition'
            : 'flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-base font-medium text-ink-600 transition hover:bg-ink-50 hover:text-ink-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if ($active ?? false) aria-current="page" @endif>
    {{ $slot }}
</a>
