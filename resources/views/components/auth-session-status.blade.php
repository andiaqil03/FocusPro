@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-start gap-2 rounded-xl border border-brand-200 bg-brand-50 px-3.5 py-2.5 text-sm text-brand-800']) }} role="status">
        <x-icon name="circle-check" class="mt-px h-4 w-4" />
        <span>{{ $status }}</span>
    </div>
@endif
