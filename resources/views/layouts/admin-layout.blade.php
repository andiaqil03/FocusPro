<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head', ['title' => 'Admin · ' . trim($__env->yieldContent('title', 'Dashboard'))])
</head>
<body class="min-h-screen font-sans">
@php
    $adminLinks = [
        ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'label' => 'Overview', 'icon' => 'dashboard'],
        ['route' => 'admin.users', 'match' => 'admin.users*', 'label' => 'Users', 'icon' => 'users'],
    ];
@endphp

<div x-data="{ open: false }" class="lg:flex">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-ink-200/70 bg-white transition-transform duration-200 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
           :class="open && 'translate-x-0'">
        <div class="flex h-16 items-center gap-2 px-5">
            <x-application-logo />
            <span class="chip-neutral ml-1">Admin</span>
        </div>

        <nav class="flex-1 space-y-1 px-3 py-4">
            @foreach ($adminLinks as $link)
                <x-responsive-nav-link :href="route($link['route'])" :active="request()->routeIs($link['match'])" class="!text-sm">
                    <x-icon :name="$link['icon']" class="h-4 w-4" /> {{ $link['label'] }}
                </x-responsive-nav-link>
            @endforeach
        </nav>

        <div class="space-y-1 border-t border-ink-100 p-3">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-ink-600 transition hover:bg-ink-50 hover:text-ink-900">
                <x-icon name="arrow-right" class="h-4 w-4 rotate-180" /> Back to app
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full cursor-pointer items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-ink-600 transition hover:bg-ink-50 hover:text-ink-900">
                    <x-icon name="log-out" class="h-4 w-4" /> Log out
                </button>
            </form>
        </div>
    </aside>

    {{-- Mobile backdrop --}}
    <div x-show="open" x-cloak x-transition.opacity @click="open = false" class="fixed inset-0 z-40 bg-ink-900/30 lg:hidden"></div>

    <div class="min-w-0 flex-1">
        <header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-ink-200/70 bg-white/80 px-4 backdrop-blur-md lg:hidden">
            <button type="button" @click="open = true" class="icon-btn" aria-label="Open menu"><x-icon name="menu" /></button>
            <x-application-logo />
        </header>

        <main class="page">
            @yield('content')
        </main>
    </div>
</div>

@include('layouts.partials.flash')
</body>
</html>
