<x-guest-layout>
    <x-slot name="heading">Welcome back</x-slot>
    <x-slot name="subheading">Log in to pick up where you left off.</x-slot>
    <x-slot name="footer">New to FocusPro? <a href="{{ route('register') }}" class="link">Create an account</a></x-slot>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" value="Password" />
                @if (Route::has('password.request'))
                    <a class="mb-1.5 text-xs font-medium text-ink-500 transition hover:text-ink-900" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="flex cursor-pointer items-center gap-2.5 text-sm text-ink-600">
            <input id="remember_me" type="checkbox" class="h-4 w-4 cursor-pointer rounded border-ink-300 text-brand-700 focus:ring-brand-600" name="remember">
            Remember me
        </label>

        <x-primary-button class="w-full btn-lg">Log in</x-primary-button>
    </form>
</x-guest-layout>
