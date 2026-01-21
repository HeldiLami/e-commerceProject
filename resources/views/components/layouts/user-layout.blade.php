@props(['title' => 'User Panel'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    @vite(['resources/css/admin/sidebar.css', 'resources/js/admin/menuBtn.js'])
    {{ $css ?? '' }}
    <style>
    </style>
</head>
<body>
    <div class="layout-wrapper">
        <x-user-sidebar />

        <main class="content-area">
            {{ $slot }}
        </main>
    </div>

    {{ $scripts ?? '' }}
</body>
</html>