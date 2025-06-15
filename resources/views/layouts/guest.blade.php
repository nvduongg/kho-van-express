<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                background: linear-gradient(135deg, #f0f4f8 0%, #cfd9df 100%);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center py-8">
            <div class="mb-8 flex flex-col items-center">
                <a href="/">
                    <x-application-logo class="w-24 h-24 fill-current text-indigo-500 drop-shadow-lg" />
                </a>
                <h1 class="mt-4 text-3xl font-bold text-indigo-700 tracking-tight">{{ config('app.name', 'Laravel') }}</h1>
                <p class="mt-2 text-gray-500 text-center max-w-xs">Chào mừng bạn đến với hệ thống kho vận hiện đại!</p>
            </div>

            <div class="w-full sm:max-w-md bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl px-8 py-6">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
