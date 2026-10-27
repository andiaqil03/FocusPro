<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head', ['title' => trim($__env->yieldContent('title'))])
</head>
<body class="min-h-screen bg-white font-sans">
    <header class="sticky top-0 z-40 border-b border-ink-100 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="/" class="rounded-lg" aria-label="FocusPro home"><x-application-logo /></a>
            <nav class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary">Open app <x-icon name="arrow-right" class="h-4 w-4" /></a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Log in</a>
                    <a href="{{ route('register') }}" class="btn-primary">Get started</a>
                @endauth
            </nav>
        </div>
    </header>

    @yield('content')

    <footer class="border-t border-ink-100">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-4 py-8 text-sm text-ink-500 sm:flex-row sm:px-6 lg:px-8">
            <p>&copy; {{ date('Y') }} FocusPro. Stay focused, achieve more.</p>
            <a href="https://github.com/andiaqil03/FocusPro" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg px-2 py-1 transition hover:text-ink-900">
                <x-icon name="github" class="h-4 w-4" /> Source on GitHub
            </a>
        </div>
    </footer>
</body>
</html>
