@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Profile</h2>

    <div class="bg-gray-800 text-white p-6 rounded-lg shadow-md space-y-10">

        <!-- Update Profile Information -->
        @include('profile.partials.update-profile-information-form')

        <!-- Update Password -->
        @include('profile.partials.update-password-form')

        <!-- Delete User Account -->
        @include('profile.partials.delete-user-form')

    </div>
</div>
@endsection
