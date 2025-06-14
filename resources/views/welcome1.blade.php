@extends('layouts.guest1')

@section('content')
<div class="min-h-screen bg-white flex flex-col justify-center items-center px-4">
    <div class="text-center max-w-3xl">
        <h1 class="text-5xl font-bold text-gray-900 mb-4">Welcome to FocusPro</h1>
        <p class="text-lg text-gray-600 mb-8">Your personal focus assistant — plan tasks, block distractions, and boost productivity.</p>
        <br>
        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
            Get Started
        </a>
    </div>
    <div class="text-center mt-8">
        <p class="text-sm text-gray-500">Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a></p>
        <p class="text-sm text-gray-500">New here? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Create an account</a></p>

    <div class="mt-12">
        <img src="{{ asset('images/focus-illustration.png') }}" alt="Focus Illustration" class="w-full max-w-md">
    </div>

    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
        <div>
            <h3 class="text-xl font-semibold text-gray-800">Distraction Blocking</h3>
            <p class="text-sm text-gray-600">Block websites that break your flow.</p>
        </div>
        <div>
            <h3 class="text-xl font-semibold text-gray-800">Focus Sessions</h3>
            <p class="text-sm text-gray-600">Pomodoro-style tracking with insights.</p>
        </div>
        <div>
            <h3 class="text-xl font-semibold text-gray-800">Analytics</h3>
            <p class="text-sm text-gray-600">See your sessions, tasks, and focus trends.</p>
        </div>
    </div>
</div>
@endsection