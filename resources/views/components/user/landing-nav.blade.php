<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent ">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="/" class="flex items-center">
                    <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-[#364872] to-[#697AA4]">Pusdatin</span>
                </a>
            </div>

            {{-- Desktop menu --}}
            <div class="hidden md:flex items-center space-x-8">
                <a href="#layanan" class="text-gray-700 hover:text-[#364872] transition-colors">Layanan</a>
                <a href="#cara-kerja" class="text-gray-700 hover:text-[#364872] transition-colors">Cara Kerja</a>
                <a href="{{ route('login') }}" class="bg-[#364872] hover:bg-[#25262a] text-white px-4 py-1 rounded-md">Masuk</a>

            </div>

            {{-- Mobile menu button --}}
            <div class="md:hidden">
                <button onclick="toggleMenu()" class="text-gray-700 hover:text-[#364872] focus:outline-none" aria-label="Toggle menu">
                    <span id="menu-icon" class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </span>
                    <span id="close-icon" class="close-icon hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="md:hidden py-4 hidden">
            <div class="flex flex-col space-y-4">
                <a href="#layanan" class="text-gray-700 hover:text-[#364872] transition-colors" onclick="closeMenu()">Layanan</a>
                <a href="#cara-kerja" class="text-gray-700 hover:text-[#364872] transition-colors" onclick="closeMenu()">Cara Kerja</a>
                <a href="#testimonial" class="text-gray-700 hover:text-[#364872] transition-colors" onclick="closeMenu()">Testimonial</a>
                <a href="#faq" class="text-gray-700 hover:text-[#364872] transition-colors" onclick="closeMenu()">FAQ</a>
                <button class="bg-[#364872] hover:bg-purple-800 text-white px-4 py-2 rounded w-full">Masuk</button>
            </div>
        </div>
    </div>
</nav>