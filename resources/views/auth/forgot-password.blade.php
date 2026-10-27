<x-guest-layout>
    <x-slot name="heading">Reset your password</x-slot>
    <x-slot name="subheading">Enter your email and we'll send you a link to choose a new password.</x-slot>
    <x-slot name="footer"><a href="{{ route('login') }}" class="link">Back to log in</a></x-slot>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full btn-lg">Email reset link</x-primary-button>
    </form>
</x-guest-layout>
