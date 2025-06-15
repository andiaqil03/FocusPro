@extends('layouts.guest1')

@section('content')
<style>
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 1s forwards;
    }
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: none;
        }
    }
    .feature-card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 10px 24px 0 rgba(59,130,246,0.15);
    }
    .glow-btn {
        box-shadow: 0 0 0 0 #3b82f6;
        transition: box-shadow 0.3s;
    }
    .glow-btn:hover {
        box-shadow: 0 4px 24px 0 #3b82f6aa;
    }
</style>

<div class="min-h-screen flex flex-col justify-center items-center px-4 bg-gradient-to-br from-blue-50 via-indigo-100 to-white">
    <div class="text-center max-w-3xl fade-in">
        <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-4 tracking-tight bg-gradient-to-r from-blue-600 via-indigo-500 to-pink-500 bg-clip-text text-transparent animate-gradient">Welcome to FocusPro</h1>
        <p class="text-lg md:text-xl text-gray-700 mb-8">Your personal focus assistant — plan tasks, block distractions, and boost productivity.</p>
        <a href="{{ route('login') }}" class="glow-btn bg-blue-600 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-lg hover:bg-blue-700 transition-all duration-300 inline-block">
            Get Started
        </a>
    </div>
    <div class="text-center mt-8 fade-in">
        <p class="text-sm text-gray-500">Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a></p>
        <p class="text-sm text-gray-500">New here? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Create an account</a></p>
    </div>

    <div class="mt-12 fade-in">
        <img src="{{ asset('images/focus-illustration.png') }}" alt="Focus Illustration" class="w-full max-w-md mx-auto drop-shadow-xl rounded-2xl animate-bounce-slow">
    </div>

    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 text-center w-full max-w-5xl fade-in">
        <div class="feature-card bg-white bg-opacity-80 rounded-2xl p-8 shadow-md transition-all duration-300 hover:bg-blue-50">
            <div class="flex justify-center mb-4">
                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9.75 17L6 21M6 21l-3.75-4M6 21V3m12 0v18m0 0l3.75-4M18 21l-3.75-4"></path></svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Distraction Blocking</h3>
            <p class="text-sm text-gray-600">Block websites that break your flow and stay in the zone.</p>
        </div>
        <div class="feature-card bg-white bg-opacity-80 rounded-2xl p-8 shadow-md transition-all duration-300 hover:bg-indigo-50">
            <div class="flex justify-center mb-4">
                <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Focus Sessions</h3>
            <p class="text-sm text-gray-600">Pomodoro-style tracking with insights and reminders.</p>
        </div>
        <div class="feature-card bg-white bg-opacity-80 rounded-2xl p-8 shadow-md transition-all duration-300 hover:bg-pink-50">
            <div class="flex justify-center mb-4">
                <svg class="w-12 h-12 text-pink-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 3h18v18H3V3z"/><path d="M8 8h8v8H8V8z"/></svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Analytics</h3>
            <p class="text-sm text-gray-600">See your sessions, tasks, and focus trends in beautiful charts.</p>
        </div>
    </div>
</div>

<!-- Optional: Slow bounce animation for illustration -->
<style>
@keyframes bounce-slow {
  0%, 100% { transform: translateY(0);}
  50% { transform: translateY(-16px);}
}
.animate-bounce-slow {
  animation: bounce-slow 3s infinite;
}
@media (max-width: 768px) {
    .feature-card { margin-bottom: 1.5rem; }
}
</style>
@endsection