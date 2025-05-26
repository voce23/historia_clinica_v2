<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Historia Clínica' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">
    <main class="min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4">
            {{ $slot }}
        </div>
    </main>
    @livewireScripts
</body>
</html>
