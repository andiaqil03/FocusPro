@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="page max-w-4xl">
    <div class="page-header" data-reveal>
        <div>
            <h1 class="page-title">Profile</h1>
            <p class="page-subtitle">Manage your account details and security.</p>
        </div>
    </div>

    <div class="space-y-6" data-stagger>
        <div class="card card-body sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card card-body sm:p-8">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card card-body border-red-200/70 sm:p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
