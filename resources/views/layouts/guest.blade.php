<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.partials.head', ['title' => isset($heading) ? strip_tags($heading) : null])
    </head>
    <body class="min-h-screen font-sans">
        <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-12">
            {{-- Soft background accent --}}
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-80 bg-gradient-to-b from-brand-50/80 to-transparent" aria-hidden="true"></div>

            <a href="/" class="mb-8 rounded-lg" data-reveal aria-label="FocusPro home">
                <x-application-logo />
            </a>

            <div class="card w-full max-w-md" data-reveal="0.05">
                <div class="card-body sm:p-8">
                    @isset($heading)
                        <div class="mb-6">
                            <h1 class="text-xl font-semibold tracking-tight text-ink-900">{{ $heading }}</h1>
                            @isset($subheading)
                                <p class="mt-1.5 text-sm text-ink-500">{{ $subheading }}</p>
                            @endisset
                        </div>
                    @endisset

                    {{ $slot }}
                </div>
            </div>

            @isset($footer)
                <p class="mt-6 text-sm text-ink-500" data-reveal="0.1">{{ $footer }}</p>
            @endisset
        </div>

        @include('layouts.partials.flash')
    </body>
</html>
