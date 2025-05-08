<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    @include('landing.headerlanding')
    
    <main class="pt-16"> <!-- Tambahkan padding top untuk mengkompensasi fixed header -->
        @yield('content')
    </main>
    
    @include('landing.footerlanding') <!-- Jika ada footer -->
</body>
</html>