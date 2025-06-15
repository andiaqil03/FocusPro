<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FocusPro - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-gray-50">
    @yield('content')

    <footer class="mt-16 bg-white border-t border-gray-200">
        <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-gray-600 text-sm text-center md:text-left">
                © 2025 <span class="font-semibold text-blue-600">FocusPro</span>. All rights reserved.
            </div>
            <div class="flex space-x-6 justify-center">
                <a href="#" class="text-gray-500 hover:text-blue-600 transition">About</a>
                <a href="#" class="text-gray-500 hover:text-blue-600 transition">Contact</a>
                <a href="#" class="text-gray-500 hover:text-blue-600 transition">Privacy Policy</a>
                <a href="#" class="text-gray-500 hover:text-blue-600 transition">Terms</a>
            </div>
            <div class="flex space-x-4 justify-center">
                <a href="#" aria-label="Twitter" class="text-gray-400 hover:text-blue-500 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.59-2.47.7a4.3 4.3 0 0 0 1.88-2.37 8.59 8.59 0 0 1-2.72 1.04A4.28 4.28 0 0 0 16.11 4c-2.37 0-4.29 1.92-4.29 4.29 0 .34.04.67.11.99A12.13 12.13 0 0 1 3.1 5.13a4.28 4.28 0 0 0 1.33 5.72c-.7-.02-1.36-.21-1.94-.53v.05c0 2.07 1.47 3.8 3.42 4.19-.36.1-.74.16-1.13.16-.28 0-.54-.03-.8-.08.54 1.7 2.12 2.94 3.99 2.97A8.6 8.6 0 0 1 2 19.54a12.13 12.13 0 0 0 6.56 1.92c7.88 0 12.2-6.53 12.2-12.2 0-.19 0-.38-.01-.57A8.7 8.7 0 0 0 24 4.59a8.5 8.5 0 0 1-2.54.7z"/></svg>
                </a>
                <a href="https://github.com/andiaqil03/FocusPro.git" aria-label="GitHub" class="text-gray-400 hover:text-gray-900 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.58 2 12.26c0 4.5 2.87 8.32 6.84 9.67.5.09.68-.22.68-.48 0-.24-.01-.87-.01-1.7-2.78.62-3.37-1.36-3.37-1.36-.45-1.18-1.1-1.5-1.1-1.5-.9-.63.07-.62.07-.62 1 .07 1.53 1.05 1.53 1.05.89 1.56 2.34 1.11 2.91.85.09-.66.35-1.11.63-1.37-2.22-.26-4.56-1.14-4.56-5.07 0-1.12.39-2.03 1.03-2.74-.1-.26-.45-1.3.1-2.7 0 0 .84-.28 2.75 1.04A9.38 9.38 0 0 1 12 6.84c.85.004 1.7.12 2.5.34 1.9-1.32 2.74-1.04 2.74-1.04.55 1.4.2 2.44.1 2.7.64.71 1.03 1.62 1.03 2.74 0 3.94-2.34 4.8-4.57 5.06.36.32.68.95.68 1.92 0 1.39-.01 2.51-.01 2.85 0 .27.18.58.69.48A10.01 10.01 0 0 0 22 12.26C22 6.58 17.52 2 12 2z"/></svg>
                </a>
            </div>
        </div>
        <div class="text-center text-xs text-gray-400 pb-2">Stay focused. Achieve more.</div>
    </footer>
</body>
</html>
