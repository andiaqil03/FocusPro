{{-- Turns session flash messages into toasts (window.toast comes from resources/js/motion.js). --}}
@php
    $flashes = array_filter([
        ['success', session('success')],
        ['error', session('error')],
    ], fn ($flash) => filled($flash[1]));
@endphp

@if ($flashes)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @foreach ($flashes as [$type, $message])
                window.toast?.(@json($message), @json($type));
            @endforeach
        });
    </script>
@endif
