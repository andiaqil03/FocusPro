@extends('layouts.admin-layout')

@section('title', 'Edit user')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.users') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-ink-500 transition hover:text-ink-900" data-reveal>
        <x-icon name="arrow-right" class="h-4 w-4 rotate-180" /> Back to users
    </a>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="card" data-reveal="0.05">
        @csrf
        @method('PATCH')

        <div class="flex items-center gap-4 border-b border-ink-100 px-6 py-5">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-ink-100 font-semibold uppercase text-ink-600">{{ mb_substr($user->name, 0, 1) }}</span>
            <div class="min-w-0">
                <h1 class="truncate text-lg font-semibold text-ink-900">{{ $user->name }}</h1>
                <p class="truncate text-sm text-ink-500">{{ $user->email }}</p>
            </div>
        </div>

        <fieldset class="px-6 py-6">
            <legend class="label">Role</legend>
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach (['user' => 'Can use the app normally.', 'admin' => 'Can also manage users and see platform stats.'] as $role => $description)
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="{{ $role }}" class="peer sr-only" @checked(old('role', $user->role) === $role)>
                        <span class="block h-full rounded-xl border border-ink-200 p-4 transition hover:border-ink-300 peer-checked:border-brand-600 peer-checked:bg-brand-50 peer-checked:ring-1 peer-checked:ring-brand-600 peer-focus-visible:ring-2 peer-focus-visible:ring-brand-600">
                            <span class="block text-sm font-semibold text-ink-900">{{ ucfirst($role) }}</span>
                            <span class="mt-0.5 block text-xs text-ink-500">{{ $description }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </fieldset>

        <div class="flex justify-end gap-2 border-t border-ink-100 bg-ink-50/50 px-6 py-4">
            <a href="{{ route('admin.users') }}" class="btn-ghost">Cancel</a>
            <button type="submit" class="btn-primary">Save role</button>
        </div>
    </form>
</div>
@endsection
