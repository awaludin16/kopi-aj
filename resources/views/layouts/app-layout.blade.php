<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $title ? 'Kopi AJ - ' . $title : 'Kopi AJ' }}</title>

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-white">
    @isset($toolBar)
        <div class="fixed left-0 right-0 top-0 z-50 mx-auto bg-white shadow-[0px_1px_8px_1px_rgba(0,0,0,0.07)]">
            {{ $toolBar }}
        </div>
    @endisset

    <main class="min-h-screen">
        {{ $slot }}
    </main>
</body>

<script src="//unpkg.com/alpinejs" defer></script>

</html>
