<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    @vite([
        'resources/css/pages/admin/sidebar.css', 
        'resources/js/admin/menuBtn.js'
    ])
</head>
<body>

    <div class="layout-wrapper">
        @include('components.sidebar') 

        <main class="content-area">
            {{ $slot }}
        </main>
    </div>

</body>
</html>