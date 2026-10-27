@props(['label', 'value', 'icon', 'suffix' => null, 'decimals' => 0])

<div {{ $attributes->merge(['class' => 'card card-body']) }}>
    <div class="flex items-center justify-between">
        <p class="text-sm font-medium text-ink-500">{{ $label }}</p>
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-ink-50 text-ink-500">
            <x-icon :name="$icon" class="h-4 w-4" />
        </span>
    </div>
    <p class="mt-3 text-3xl font-semibold tracking-tight text-ink-900 tabular-nums">
        <span data-count="{{ $value }}" data-decimals="{{ $decimals }}">{{ number_format($value, $decimals) }}</span>@if ($suffix)<span class="ml-0.5 text-lg font-medium text-ink-400">{{ $suffix }}</span>@endif
    </p>
    @isset($hint)
        <p class="mt-1 text-xs text-ink-500">{{ $hint }}</p>
    @endisset
</div>
