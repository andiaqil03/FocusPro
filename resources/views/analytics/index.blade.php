@extends('layouts.app')

@section('title', 'Analytics')

@section('content')
@php
    $days = $dateRange === '30_days' ? 30 : 7;
    $focusHours = round($totalWorkTime / 60, 1);
@endphp

<div class="page">
    <div class="page-header" data-reveal>
        <div>
            <h1 class="page-title">Analytics</h1>
            <p class="page-subtitle">How your focus time and tasks are trending.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="inline-flex rounded-xl border border-ink-200 bg-white p-1 shadow-sm" role="group" aria-label="Date range">
                @foreach (['7_days' => '7 days', '30_days' => '30 days'] as $value => $label)
                    <a href="{{ route('analytics', ['date_range' => $value]) }}"
                       @class(['rounded-lg px-3 py-1.5 text-sm font-medium transition', 'bg-ink-900 text-white' => $dateRange === $value, 'text-ink-500 hover:text-ink-900' => $dateRange !== $value])
                       @if ($dateRange === $value) aria-current="true" @endif>{{ $label }}</a>
                @endforeach
            </div>
            <a href="{{ route('analytics.download', ['date_range' => $dateRange]) }}" class="btn-secondary">
                <x-icon name="download" class="h-4 w-4" /> PDF report
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4" data-stagger>
        <x-stat-card label="Focus sessions" :value="$totalSessions" icon="timer" />
        <x-stat-card label="Cycles completed" :value="$totalCycles" icon="refresh" />
        <x-stat-card label="Focus time" :value="$focusHours" suffix="h" :decimals="1" icon="clock" />
        <x-stat-card label="Avg. session" :value="round($averageSessionDuration)" suffix="min" icon="trending-up" />
    </div>

    <section class="card card-body mt-6" data-reveal="0.1">
        <div class="flex items-baseline justify-between">
            <h2 class="section-title">Sessions per day</h2>
            <span class="text-xs text-ink-500">Last {{ $days }} days</span>
        </div>
        <div class="relative mt-6 h-64">
            <canvas id="dailySessionsChart" aria-label="Bar chart of focus sessions per day over the last {{ $days }} days" role="img"></canvas>
        </div>
    </section>

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <section class="card card-body" data-reveal="0.12">
            <h2 class="section-title">Focus vs. break</h2>
            <p class="mt-1 text-sm text-ink-500">Based on 25 min focus and 5 min break per cycle.</p>
            <div class="mt-6 flex items-center gap-6">
                <div class="relative h-36 w-36 shrink-0">
                    <canvas id="workBreakChart" aria-label="Doughnut chart of focus time versus break time" role="img"></canvas>
                </div>
                <dl class="space-y-3 text-sm">
                    <div class="flex items-center gap-2.5"><span class="h-2.5 w-2.5 rounded-full bg-brand-600"></span><dt class="text-ink-500">Focus</dt><dd class="ml-auto whitespace-nowrap pl-4 font-semibold tabular-nums text-ink-900">{{ number_format($totalWorkTime) }} min</dd></div>
                    <div class="flex items-center gap-2.5"><span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span><dt class="text-ink-500">Break</dt><dd class="ml-auto whitespace-nowrap pl-4 font-semibold tabular-nums text-ink-900">{{ number_format($totalBreakTime) }} min</dd></div>
                </dl>
            </div>
        </section>

        <section class="card card-body" data-reveal="0.14">
            <h2 class="section-title">Task completion</h2>
            <p class="mt-1 text-sm text-ink-500">{{ $completedTasks }} of {{ $totalTasks }} tasks completed.</p>
            <div class="mt-6 flex items-center gap-6">
                <div class="relative h-36 w-36 shrink-0">
                    <canvas id="taskCompletionChart" aria-label="Doughnut chart of completed versus open tasks" role="img"></canvas>
                    <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-semibold tabular-nums text-ink-900">{{ round($completionRate) }}%</span>
                    </div>
                </div>
                <dl class="space-y-3 text-sm">
                    <div class="flex items-center gap-2.5"><span class="h-2.5 w-2.5 rounded-full bg-brand-600"></span><dt class="text-ink-500">Completed</dt><dd class="ml-auto pl-4 font-semibold tabular-nums text-ink-900">{{ $completedTasks }}</dd></div>
                    <div class="flex items-center gap-2.5"><span class="h-2.5 w-2.5 rounded-full bg-ink-200"></span><dt class="text-ink-500">Open</dt><dd class="ml-auto pl-4 font-semibold tabular-nums text-ink-900">{{ $totalTasks - $completedTasks }}</dd></div>
                </dl>
            </div>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const dailySessions = @json($dailySessions);
    const rangeDays = {{ $days }};
    const workTime = {{ $totalWorkTime }};
    const breakTime = {{ $totalBreakTime }};
    const completedTasks = {{ $completedTasks }};
    const incompleteTasks = {{ $totalTasks - $completedTasks }};

    const palette = { brand: '#0d9488', amber: '#fbbf24', track: '#e4e4e7', grid: '#f4f4f5', text: '#71717a' };
    const animate = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    Chart.defaults.font.family = '"Plus Jakarta Sans", ui-sans-serif, system-ui, sans-serif';
    Chart.defaults.font.size = 12;
    Chart.defaults.color = palette.text;
    Chart.defaults.animation = animate ? { duration: 700, easing: 'easeOutQuart' } : false;
    Object.assign(Chart.defaults.plugins.tooltip, {
        backgroundColor: '#18181b', padding: 10, cornerRadius: 8, displayColors: false,
        titleFont: { weight: '600' }, bodyFont: { size: 12 },
    });
    Chart.defaults.plugins.legend.display = false;

    // Fill in days without sessions so the axis always covers the full range.
    const counts = Object.fromEntries(dailySessions.map(d => [d.date, Number(d.sessions)]));
    const labels = [], values = [];
    for (let i = rangeDays - 1; i >= 0; i--) {
        const day = new Date();
        day.setDate(day.getDate() - i);
        const key = `${day.getFullYear()}-${String(day.getMonth() + 1).padStart(2, '0')}-${String(day.getDate()).padStart(2, '0')}`;
        labels.push(day.toLocaleDateString(undefined, rangeDays > 7 ? { day: 'numeric', month: 'short' } : { weekday: 'short' }));
        values.push(counts[key] || 0);
    }

    new Chart(document.getElementById('dailySessionsChart'), {
        type: 'bar',
        data: { labels, datasets: [{ label: 'Sessions', data: values, backgroundColor: palette.brand, hoverBackgroundColor: '#0f766e', borderRadius: 6, maxBarThickness: 36 }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { maxRotation: 0, autoSkipPadding: 12 } },
                y: { beginAtZero: true, grid: { color: palette.grid }, border: { display: false }, ticks: { precision: 0 } },
            },
            plugins: { tooltip: { callbacks: { label: ctx => `${ctx.parsed.y} session${ctx.parsed.y === 1 ? '' : 's'}` } } },
        },
    });

    const doughnut = (id, data, colors, labels) => new Chart(document.getElementById(id), {
        type: 'doughnut',
        data: {
            labels,
            // An all-zero dataset renders nothing, so show an empty grey ring instead.
            datasets: [{ data: data.some(v => v > 0) ? data : [1], backgroundColor: data.some(v => v > 0) ? colors : [palette.track], borderWidth: 0, hoverOffset: 4 }],
        },
        options: { cutout: '74%', responsive: true, maintainAspectRatio: false, plugins: { tooltip: { enabled: data.some(v => v > 0) } } },
    });

    doughnut('workBreakChart', [workTime, breakTime], [palette.brand, palette.amber], ['Focus (min)', 'Break (min)']);
    doughnut('taskCompletionChart', [completedTasks, incompleteTasks], [palette.brand, palette.track], ['Completed', 'Open']);
</script>
@endsection
