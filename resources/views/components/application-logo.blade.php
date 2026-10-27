{{-- FocusPro wordmark: a focus ring with a "running" arc, plus the name. --}}
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }}>
    <svg class="h-7 w-7 shrink-0" viewBox="0 0 28 28" fill="none" aria-hidden="true">
        <rect width="28" height="28" rx="8" class="fill-brand-700" />
        <circle cx="14" cy="14" r="7" stroke="white" stroke-opacity=".35" stroke-width="2.25" />
        <path d="M14 7a7 7 0 0 1 7 7" stroke="white" stroke-width="2.25" stroke-linecap="round" />
        <circle cx="14" cy="14" r="1.75" fill="white" />
    </svg>
    <span class="text-[1.05rem] font-semibold tracking-tight text-ink-900">Focus<span class="text-brand-700">Pro</span></span>
</span>
