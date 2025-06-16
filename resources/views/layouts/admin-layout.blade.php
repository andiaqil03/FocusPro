<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Adjust this if you're using Laravel Mix --}}
</head>
<body class="bg-gray-100 font-sans antialiased">

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4 text-center">
            {{ session('error') }}
        </div>
    @endif


    <div class="flex h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-blue-800 text-white flex flex-col">
            <div class="p-4 font-bold text-xl border-b border-blue-700">
                Admin Panel
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-blue-700">Dashboard</a>
                <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded hover:bg-blue-700">Manage Users</a>
                {{-- Add more admin links here --}}
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="p-4">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold px-3 py-2 rounded">
                    Logout
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <h1 class="text-2xl font-semibold mb-4">@yield('title', 'Admin Dashboard')</h1>
            @yield('content')
        </main>

    </div>

</body>
</html>
