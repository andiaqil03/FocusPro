<x-guest-layout>
    <x-slot name="heading">Confirm your password</x-slot>
    <x-slot name="subheading">This is a secure area. Please confirm your password before continuing.</x-slot>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full btn-lg">Confirm</x-primary-button>
    </form>
</x-guest-layout>
