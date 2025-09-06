<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Car Lookup')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-gray-800 shadow-lg fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <h1 class="text-xl font-bold text-white">Car Info Lookup</h1>

            <!-- Shared Plate Search -->
            <form action="{{ route('car.lookup') }}" method="GET" class="flex space-x-2">
                <input type="text" id="plate" name="plate"
                       value="{{ request('plate') }}"
                       class="px-3 py-2 rounded-lg bg-gray-700 text-white placeholder-gray-400 border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Enter license plate" required>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg">
                    Check
                </button>
            </form>
        </div>
    </nav>

    <!-- Main page content -->
    <main class="pt-24 px-4">
        @yield('content')
    </main>

</body>
</html>
