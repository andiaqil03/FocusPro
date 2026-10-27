<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#fafafa">

<title>{{ isset($title) && $title ? $title . ' · FocusPro' : 'FocusPro' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

{{-- Lets app.css hide [data-reveal] content until Motion animates it in. If the JS bundle hasn't
     started within 2.5s (failed to load), drop the class so nothing stays invisible. --}}
<script>
    document.documentElement.classList.add('js');
    setTimeout(function () {
        if (!window.__motionReady) document.documentElement.classList.remove('js');
    }, 2500);
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
