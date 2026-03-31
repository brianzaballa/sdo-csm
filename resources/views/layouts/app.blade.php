<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CSM') }}</title>
    @vite('resources/css/app.css')
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased">
    {{ $slot }}
</body>
</html>
