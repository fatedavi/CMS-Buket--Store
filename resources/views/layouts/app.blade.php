<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SEO Meta Tags -->
    @stack('meta')
    
    <!-- JSON-LD Schema -->
    @stack('schema')
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    

    
    <style>
        body {
            background-color: #faf8f4;
            color: #3d2f1e;
        }
    </style>
</head>
<body class="font-dm antialiased">
    <!-- Grain Overlay -->
    <div class="grain-overlay"></div>
    
    <!-- Navbar -->
    @include('layouts.partials.navbar')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('layouts.partials.footer')
    
    <!-- Floating Chat Widget -->
    @include('layouts.partials.chat-widget')
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 50
        });
    </script>
    
    <!-- Scripts Stack -->
    @stack('scripts')
</body>
</html>
