<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard</title>

    @vite([
        'resources/css/pages/admin/sidebar.css', 
        'resources/js/admin/menuBtn.js',
        'resources/js/session-timeout.js'
    ])
</head>
<body>
    <div class="layout-wrapper">
        <x-sidebar />
        <main class="content-area">
            {{ $slot }}
        </main>
    </div>
</body>
</html>