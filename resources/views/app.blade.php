<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Blog Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-100">
    <div id="app">
        <header class="bg-white shadow-sm">
            <div class="container mx-auto px-4 py-3 flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.svg') }}" alt="Blog Logo" class="h-10 mr-4">
                    <h1 class="text-xl font-semibold text-gray-800">Blog Management</h1>
                </div>
                <nav class="flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard ({{ route('dashboard') }})</a>
                    <a href="{{ url('/posts') }}" class="text-gray-600 hover:text-gray-900">Posts (/posts)</a>
                    <a href="{{ url('/search') }}" class="text-gray-600 hover:text-gray-900">Search (/search)</a>
                    <a href="{{ url('/categories') }}" class="text-gray-600 hover:text-gray-900">Categories (/categories)</a>
                    <a href="{{ url('/notifications') }}" class="text-gray-600 hover:text-gray-900">Notifications (/notifications)</a>
                </nav>
            </div>
        </header>
        <main class="container mx-auto py-4">
            @inertia
        </main>
    </div>
</body>

</html>