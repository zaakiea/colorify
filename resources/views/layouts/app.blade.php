<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wernoin</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">
    @if(!Route::is('login') && !Route::is('register'))
    <div class="flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <div class="flex-1">
            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Content -->
            <main class="ml-64 pt-16">
                @yield('content')
            </main>
        </div>
    </div>
    @else
    @yield('content')
    @endif
    @stack('scripts')
</body>
</html>
