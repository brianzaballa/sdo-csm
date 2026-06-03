<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CSM') }}</title>
    <link rel="icon" type="image/png" href="https://sdolegazpicity.com/wp-content/uploads/2025/12/cropped-LOGO-sdo-leg-1-1.png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased">
    <nav class="sticky top-0 z-50 bg-gradient-to-r from-blue-950 via-blue-900 to-indigo-950 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center space-x-3">
                    <img src="https://sdolegazpicity.com/wp-content/uploads/2025/12/cropped-LOGO-sdo-leg-1-1.png"
                         alt="SDO Legazpi City Logo"
                         class="h-10 w-10 rounded-full border-2 border-white/30">
                    <span class="text-white font-bold text-lg hidden sm:block">Client Satisfaction Measurement</span>
                </a>
                <div class="flex items-center space-x-1 sm:space-x-4">
                    <a href="/" class="text-white/80 hover:text-white px-2 sm:px-3 py-2 text-sm font-medium transition-colors flex items-center gap-1">
                        <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span class="hidden sm:inline">Home</span>
                    </a>
                    <a href="/#about" class="text-white/80 hover:text-white px-2 sm:px-3 py-2 text-sm font-medium transition-colors flex items-center gap-1">
                        <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="hidden sm:inline">About Us</span>
                    </a>
                    <a href="/#units-sections" class="text-white/80 hover:text-white px-2 sm:px-3 py-2 text-sm font-medium transition-colors flex items-center gap-1">
                        <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="hidden sm:inline">Units &amp; Sections</span>
                    </a>
                    {{-- <a href="/#contact" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Contact</a> --}}
                    @if(request()->path() !== 'survey')
                        <a href="{{ route('survey') }}"
                           class="bg-teal-500 hover:bg-teal-400 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                            Take Survey
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    @yield('content')
    @isset($slot){{ $slot }}@endisset
</body>
</html>
