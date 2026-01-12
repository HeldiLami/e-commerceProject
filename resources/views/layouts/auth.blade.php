<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Authentication' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                {{ $heading }}
            </h2>
            
            @yield('content')

            @if(isset($footer))
                <div class="mt-6 text-center text-sm text-gray-600">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>