<section class="grid gap-6 md:grid-cols-3">
    <header>
        <h2 class="section-title">Profile information</h2>
        <p class="mt-1 text-sm text-ink-500">Your name, email address and photo.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5 md:col-span-2" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Photo --}}
        <div x-data="{ preview: null }" class="flex items-center gap-4">
            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-full bg-ink-100 ring-1 ring-ink-200">
                <template x-if="preview"><img :src="preview" alt="New profile photo preview" class="h-full w-full object-cover"></template>
                @if ($user->profile_photo_path)
                    <img x-show="!preview" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile photo" class="h-full w-full object-cover">
                @else
                    <span x-show="!preview" class="flex h-full w-full items-center justify-center text-ink-400"><x-icon name="user" class="h-7 w-7" /></span>
                @endif
            </div>
            <div>
                <label for="profile_photo" class="btn-secondary cursor-pointer">Change photo</label>
                <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/webp" class="sr-only"
                       @change="const f = $event.target.files[0]; preview = f ? URL.createObjectURL(f) : null">
                <p class="hint">JPG, PNG or WebP, up to 2 MB.</p>
            </div>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />

        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="mt-2 text-sm text-ink-700">
                    Your email address is unverified.
                    <button form="send-verification" class="link">Re-send the verification email</button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm font-medium text-brand-700">A new verification link has been sent to your email address.</p>
                @endif
            @endif
        </div>

        <div class="flex items-center gap-4 pt-1">
            <x-primary-button>Save changes</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                   class="inline-flex items-center gap-1.5 text-sm text-brand-700"><x-icon name="check" class="h-4 w-4" /> Saved</p>
            @endif
        </div>
    </form>
</section>
