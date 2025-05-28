<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Adjust this if you're using Laravel Mix --}}
</head>
<body class="bg-gray-100 font-sans antialiased">

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
