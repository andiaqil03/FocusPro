<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.partials.head', ['title' => trim($__env->yieldContent('title'))])
    </head>
    <body class="min-h-screen font-sans">
        @include('layouts.navigation')

        <main>
            @yield('content')
        </main>

        @include('layouts.partials.flash')
        @stack('scripts')
    </body>
</html>
