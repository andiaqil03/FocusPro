<x-guest-layout>
    <x-slot name="heading">Verify your email</x-slot>
    <x-slot name="subheading">We've emailed you a verification link. Didn't get it? We can send another.</x-slot>

    @if (session('status') == 'verification-link-sent')
        <x-auth-session-status class="mb-5" status="A new verification link has been sent to your email address." />
    @endif

    <div class="flex items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>Resend email</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-ghost">Log out</button>
        </form>
    </div>
</x-guest-layout>
