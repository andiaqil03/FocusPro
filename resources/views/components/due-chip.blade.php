@props(['date', 'done' => false])

@if ($date)
    @php
        $due = \Carbon\Carbon::parse($date)->startOfDay();
        $days = (int) now()->startOfDay()->diffInDays($due, false);
        $date = $due->format($due->isCurrentYear() ? 'j M' : 'j M Y');

        [$class, $label] = match (true) {
            $done => ['chip-neutral', $date],
            $days < 0 => ['chip-red', 'Overdue · ' . $date],
            $days === 0 => ['chip-amber', 'Due today'],
            $days === 1 => ['chip-amber', 'Due tomorrow'],
            $days <= 7 => ['chip-blue', 'Due ' . $due->format('D, j M')],
            default => ['chip-neutral', 'Due ' . $date],
        };
    @endphp

    <span {{ $attributes->merge(['class' => $class]) }}>
        <x-icon name="calendar" class="h-3 w-3" />
        {{ $label }}
    </span>
@endif
