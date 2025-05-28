@extends('layouts.admin-layout')

@section('content')
<div class="container mx-auto p-6 max-w-md">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Edit User Role</h2>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Name:</label>
            <p class="text-gray-900">{{ $user->name }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Email:</label>
            <p class="text-gray-900">{{ $user->email }}</p>
        </div>

        <div class="mb-4">
            <label for="role" class="block text-gray-700 font-medium mb-2">Role:</label>
            <select name="role" id="role" class="w-full border px-4 py-2 rounded">
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
        <a href="{{ route('admin.users') }}" class="text-gray-600 ml-4">Cancel</a>
    </form>
</div>
@endsection

