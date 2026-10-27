@extends('layouts.guest1')

@section('content')
@php
    $features = [
        ['icon' => 'timer', 'title' => 'Focus sessions', 'text' => 'Pomodoro cycles with adjustable work and break lengths. Every finished session is saved to your history.'],
        ['icon' => 'ban', 'title' => 'Distraction blocking', 'text' => 'The companion browser extension blocks the sites you list, but only while a session is running.'],
        ['icon' => 'list-checks', 'title' => 'Tasks that stay organised', 'text' => 'Priorities, categories and due dates, with overdue work flagged so nothing slips.'],
        ['icon' => 'chart', 'title' => 'Progress you can see', 'text' => 'Daily session trends and completion rates, exportable as a PDF report.'],
    ];
    // Hero demo ring: r=88 → circumference ≈ 552.9; 74% elapsed.
    $circumference = 2 * M_PI * 88;
@endphp

{{-- Hero --}}
<section class="relative overflow-hidden">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[32rem] bg-gradient-to-b from-brand-50/70 to-transparent" aria-hidden="true"></div>

    <div class="mx-auto grid max-w-6xl items-center gap-14 px-4 pb-20 pt-16 sm:px-6 lg:grid-cols-2 lg:px-8 lg:pb-28 lg:pt-24">
        <div>
            <p class="eyebrow" data-reveal>Pomodoro · Tasks · Site blocking</p>
            <h1 class="mt-4 text-4xl font-semibold tracking-tight text-ink-900 sm:text-5xl lg:text-[3.4rem] lg:leading-[1.05]" data-reveal="0.05">
                Deep work,<br>without the distractions.
            </h1>
            <p class="mt-5 max-w-lg text-lg leading-relaxed text-ink-600" data-reveal="0.1">
                FocusPro brings your tasks, a focus timer and a distraction blocker into one calm workspace, so you spend less time switching and more time doing.
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-3" data-reveal="0.15">
                <a href="{{ route('register') }}" class="btn-primary btn-lg">Start focusing — it's free <x-icon name="arrow-right" class="h-4 w-4" /></a>
                <a href="{{ route('login') }}" class="btn-secondary btn-lg">I have an account</a>
            </div>
        </div>

        {{-- Product preview --}}
        <div class="relative mx-auto w-full max-w-md" data-reveal="0.1" aria-hidden="true">
            <div class="card p-6 shadow-lift sm:p-8">
                <div class="flex items-center justify-between">
                    <span class="chip-brand"><span class="h-1.5 w-1.5 rounded-full bg-brand-600"></span> Focus</span>
                    <span class="text-xs text-ink-400">Cycle 3 of 4</span>
                </div>

                <div class="relative mx-auto my-6 h-52 w-52">
                    <svg viewBox="0 0 200 200" class="h-full w-full -rotate-90">
                        <circle cx="100" cy="100" r="88" fill="none" class="stroke-ink-100" stroke-width="8" />
                        <circle id="hero-ring" cx="100" cy="100" r="88" fill="none" class="stroke-brand-600" stroke-width="8" stroke-linecap="round"
                                stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $circumference * 0.26 }}" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-semibold tabular-nums tracking-tight text-ink-900">18:42</span>
                        <span class="mt-1 text-xs text-ink-500">remaining</span>
                    </div>
                </div>

                <div class="space-y-2.5">
                    <div class="flex items-center gap-2 text-xs text-ink-500">
                        <x-icon name="ban" class="h-3.5 w-3.5" /> Blocking
                        <span class="chip-neutral">youtube.com</span>
                        <span class="chip-neutral">x.com</span>
                    </div>
                    <div class="flex items-center gap-3 rounded-xl border border-ink-100 bg-ink-50/60 px-3.5 py-2.5">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full border-2 border-ink-300"></span>
                        <span class="text-sm font-medium text-ink-800">Finish chapter 4 draft</span>
                        <span class="chip-red ml-auto">P1</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Features --}}
<section class="border-t border-ink-100 bg-ink-50/50">
    <div class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="max-w-2xl" data-reveal>
            <p class="eyebrow">Everything in one place</p>
            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-ink-900">Built around how focus actually works</h2>
            <p class="mt-3 text-ink-600">Decide what matters, protect the time to do it, then look back at what you got done.</p>
        </div>

        <div class="mt-12 grid gap-4 sm:grid-cols-2" data-stagger>
            @foreach ($features as $feature)
                <div class="card card-hover card-body">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-700">
                        <x-icon :name="$feature['icon']" />
                    </span>
                    <h3 class="mt-4 font-semibold text-ink-900">{{ $feature['title'] }}</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-ink-600">{{ $feature['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="rounded-3xl bg-ink-900 px-6 py-14 text-center sm:px-12" data-reveal>
        <h2 class="text-2xl font-semibold tracking-tight text-white sm:text-3xl">Ready for your first focus session?</h2>
        <p class="mx-auto mt-3 max-w-md text-ink-300">Create an account and start a 25-minute session in under a minute.</p>
        <a href="{{ route('register') }}" class="btn-lg btn mt-8 bg-white text-ink-900 hover:bg-ink-100">Get started <x-icon name="arrow-right" class="h-4 w-4" /></a>
    </div>
</section>

<script>
    // Draw the preview ring in on load.
    document.addEventListener('DOMContentLoaded', () => {
        const ring = document.getElementById('hero-ring');
        if (!ring || !window.motion || window.motion.reducedMotion) return;
        const full = {{ $circumference }};
        window.motion.animate(ring, { strokeDashoffset: [full, full * 0.26] }, { duration: 1.6, ease: [0.16, 1, 0.3, 1], delay: 0.3 });
    });
</script>
@endsection
