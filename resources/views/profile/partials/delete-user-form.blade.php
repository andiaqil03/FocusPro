<section class="grid gap-6 md:grid-cols-3">
    <header>
        <h2 class="section-title text-red-700">Delete account</h2>
        <p class="mt-1 text-sm text-ink-500">Permanently remove your account and all of its data.</p>
    </header>

    <div class="md:col-span-2">
        <p class="text-sm text-ink-600">Once your account is deleted, all of your tasks and focus sessions are permanently deleted. Export anything you want to keep first.</p>

        <x-danger-button class="mt-5" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            <x-icon name="trash" class="h-4 w-4" /> Delete account
        </x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" maxWidth="md" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-ink-900">Delete your account?</h2>
            <p class="mt-1.5 text-sm text-ink-500">This permanently deletes your account and all of its data. Enter your password to confirm.</p>

            <div class="mt-5">
                <x-input-label for="password" value="Password" class="sr-only" />
                <x-text-input id="password" name="password" type="password" placeholder="Password" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-danger-button>Delete account</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
