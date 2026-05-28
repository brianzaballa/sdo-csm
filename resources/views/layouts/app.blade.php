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
                    <a href="/" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Home</a>
                    <a href="#about" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">About Us</a>
                    <a href="#contact" class="text-white/80 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Contact</a>
                    <a href="{{ route('survey') }}"
                       class="bg-teal-500 hover:bg-teal-400 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                        Take Survey
                    </a>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')
    @isset($slot){{ $slot }}@endisset
</body>
</html>
