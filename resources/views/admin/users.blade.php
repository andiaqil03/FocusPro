@extends('layouts.admin-layout')

@section('title', 'Users')

@section('content')
<div class="page-header" data-reveal>
    <div>
        <h1 class="page-title">Users</h1>
        <p class="page-subtitle">{{ $users->count() }} registered {{ Str::plural('account', $users->count()) }}</p>
    </div>
</div>

<div class="card overflow-hidden" data-reveal="0.05">
    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th class="hidden sm:table-cell">Joined</th>
                    <th class="text-right"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if ($user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="" class="h-9 w-9 rounded-full object-cover">
                                @else
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ink-100 text-xs font-semibold uppercase text-ink-600">{{ mb_substr($user->name, 0, 1) }}</span>
                                @endif
                                <div class="min-w-0">
                                    <p class="truncate font-medium text-ink-900">{{ $user->name }}</p>
                                    <p class="truncate text-xs text-ink-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="{{ $user->role === 'admin' ? 'chip-brand' : 'chip-neutral' }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td class="hidden whitespace-nowrap text-ink-500 sm:table-cell">{{ $user->created_at->format('j M Y') }}</td>
                        <td>
                            <div class="flex justify-end">
                                <a href="{{ route('admin.users.edit', $user) }}" class="icon-btn" aria-label="Edit {{ $user->name }}" title="Edit role"><x-icon name="pencil" class="h-4 w-4" /></a>
                                @if ($user->role !== 'admin')
                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Delete {{ addslashes($user->name) }} and all of their data?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="icon-btn hover:bg-red-50 hover:text-red-600" aria-label="Delete {{ $user->name }}" title="Delete user"><x-icon name="trash" class="h-4 w-4" /></button>
                                    </form>
                                @else
                                    <span class="icon-btn cursor-not-allowed opacity-30 hover:bg-transparent" title="Admins can't be deleted. Change their role first."><x-icon name="trash" class="h-4 w-4" /></span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
