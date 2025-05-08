<header class="bg-white shadow-sm fixed w-full z-10">
    <nav class="container mx-auto px-4 py-3 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center">
            <span class="text-2xl font-bold text-blue-600">Toeic</span>
            <span class="text-xs text-gray-500 ml-1">tc.</span>
        </div>
        
        <!-- Navigation Links -->
        <div class="hidden md:flex space-x-8">
            <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Halaman Awal</a>
            <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Tentang Kami</a>
            <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Seputar TOEIC</a>
            <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Benefit</a>
        </div>
        
        <!-- Mobile Menu Button -->
        <div class="md:hidden">
            <button id="mobile-menu-button" class="text-gray-700 hover:text-blue-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-white py-2 px-4">
        <a href="#" class="block py-2 text-gray-700 hover:text-blue-600">Halaman Awal</a>
        <a href="#" class="block py-2 text-gray-700 hover:text-blue-600">Tentang Kami</a>
        <a href="#" class="block py-2 text-gray-700 hover:text-blue-600">Seputar TOEIC</a>
        <a href="#" class="block py-2 text-gray-700 hover:text-blue-600">Benefit</a>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>