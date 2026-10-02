<!DOCTYPE html>
<html lang="ckb" dir="rtl" class="h-full bg-slate-50 dark:bg-slate-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'چوونەژوورەوە - سیستەمی هیمۆفیلیا' }}</title>

        <link rel="stylesheet" href="{{ asset('fonts/vazirmatn.css') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="h-full font-sans antialiased text-slate-800 dark:text-slate-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            {{ $slot }}
        </div>

        @livewireScripts
        @wireUiScripts
    </body>
</html>
