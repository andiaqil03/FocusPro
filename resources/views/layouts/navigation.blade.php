@php
    $links = [
        ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
        ['route' => 'tasks.index', 'match' => 'tasks.*', 'label' => 'Tasks', 'icon' => 'list-checks'],
        ['route' => 'pomodoro', 'match' => 'pomodoro', 'label' => 'Focus', 'icon' => 'timer'],
        ['route' => 'analytics', 'match' => 'analytics*', 'label' => 'Analytics', 'icon' => 'chart'],
    ];
    $user = Auth::user();
    $initials = collect(explode(' ', $user->name))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('');
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-ink-200/70 bg-white/80 backdrop-blur-md">
    <div class="mx-auto flex h-16 max-w-6xl items-center gap-6 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard') }}" class="shrink-0 rounded-lg" aria-label="FocusPro home">
            <x-application-logo />
        </a>

        {{-- Desktop links --}}
        <div class="hidden items-center gap-1 md:flex">
            @foreach ($links as $link)
                <x-nav-link :href="route($link['route'])" :active="request()->routeIs($link['match'])">
                    <x-icon :name="$link['icon']" class="h-4 w-4" />
                    {{ $link['label'] }}
                </x-nav-link>
            @endforeach
        </div>

        {{-- User menu --}}
        <div class="ml-auto hidden md:block">
            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <button type="button" class="flex cursor-pointer items-center gap-2.5 rounded-full py-1 pl-1 pr-2.5 text-sm font-medium text-ink-700 transition hover:bg-ink-100">
                        @if ($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-50 text-xs font-semibold uppercase text-brand-800">{{ $initials }}</span>
                        @endif
                        <span class="max-w-[10rem] truncate">{{ $user->name }}</span>
                        <x-icon name="chevron-down" class="h-4 w-4 text-ink-400" />
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="border-b border-ink-100 px-4 py-3">
                        <p class="truncate text-sm font-medium text-ink-900">{{ $user->name }}</p>
                        <p class="truncate text-xs text-ink-500">{{ $user->email }}</p>
                    </div>
                    <div class="p-1">
                        <x-dropdown-link :href="route('profile.edit')">
                            <x-icon name="user" class="h-4 w-4 text-ink-400" /> Profile
                        </x-dropdown-link>
                        @if ($user->role === 'admin')
                            <x-dropdown-link :href="route('admin.dashboard')">
                                <x-icon name="shield" class="h-4 w-4 text-ink-400" /> Admin panel
                            </x-dropdown-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm text-ink-700 transition hover:bg-ink-50 hover:text-ink-900">
                                <x-icon name="log-out" class="h-4 w-4 text-ink-400" /> Log out
                            </button>
                        </form>
                    </div>
                </x-slot>
            </x-dropdown>
        </div>

        {{-- Mobile toggle --}}
        <button type="button" @click="open = !open" class="icon-btn ml-auto md:hidden" :aria-expanded="open" aria-label="Toggle menu">
            <x-icon name="menu" x-show="!open" />
            <x-icon name="x" x-show="open" x-cloak />
        </button>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="border-t border-ink-200/70 bg-white md:hidden">
        <div class="space-y-1 px-4 py-3">
            @foreach ($links as $link)
                <x-responsive-nav-link :href="route($link['route'])" :active="request()->routeIs($link['match'])">
                    <x-icon :name="$link['icon']" class="h-5 w-5" />
                    {{ $link['label'] }}
                </x-responsive-nav-link>
            @endforeach
        </div>
        <div class="border-t border-ink-100 px-4 py-3">
            <p class="px-3 text-sm font-medium text-ink-900">{{ $user->name }}</p>
            <p class="px-3 text-xs text-ink-500">{{ $user->email }}</p>
            <div class="mt-2 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                    <x-icon name="user" class="h-5 w-5" /> Profile
                </x-responsive-nav-link>
                @if ($user->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')">
                        <x-icon name="shield" class="h-5 w-5" /> Admin panel
                    </x-responsive-nav-link>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full cursor-pointer items-center gap-3 rounded-xl px-3 py-2.5 text-left text-base font-medium text-ink-600 transition hover:bg-ink-50 hover:text-ink-900">
                        <x-icon name="log-out" class="h-5 w-5" /> Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
