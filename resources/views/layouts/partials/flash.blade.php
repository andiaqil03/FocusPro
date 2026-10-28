{{-- Turns session flash messages into toasts (window.toast comes from resources/js/motion.js). --}}
@php
    $flashes = array_filter([
        ['success', session('success')],
        ['error', session('error')],
    ], fn ($flash) => filled($flash[1]));
@endphp

@php $undo = session('undo'); @endphp

@if ($flashes)
    {{-- An "undo" flash (set when a task is completed) adds an Undo button that PATCHes the task back. --}}
    @if ($undo)
        <form id="flash-undo" action="{{ $undo['url'] }}" method="POST" hidden>
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="{{ $undo['status'] }}">
        </form>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @foreach ($flashes as [$type, $message])
                window.toast?.(@json($message), @json($type), @if ($undo && $type === 'success') { duration: 7000, action: { label: 'Undo', onClick: () => document.getElementById('flash-undo').submit() } } @else {} @endif);
            @endforeach
        });
    </script>
@endif
