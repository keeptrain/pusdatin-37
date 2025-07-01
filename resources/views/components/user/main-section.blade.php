{{-- resources/views/components/main-section.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-white via-slate-50 to-slate-100 flex items-center justify-center px-4 py-16 relative">
    <div class="max-w-screen mx-auto text-center">

        <!-- Badge -->
        <div class="inline-flex items-center gap-2 bg-[#364872] bg-opacity-10 border border-[#364872] border-opacity-30 rounded-full px-4 py-2 mb-8">
            <div class="w-8 h-8 bg-[#364872] rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <span class=" font-medium  text-white text-lg">Jakreq</span>
        </div>

        <!-- Main Heading with Typing Animation -->
        <div class="mb-6">
            <h1 class=" font-bold text-gray-900 leading-tight">
                <span class="block mb-2">
                    <span id="typing-text" class="text-5xl 2xl:text-7xl"></span>
                    <span id="cursor" class="animate-pulse text-[#364872]">|</span>
                </span>
                <span class="block text-[#364872] text-4xl 2xl:text-5xl">
                    Ajukan Melalui Jakreq
                </span>
            </h1>
        </div>

        <!-- Description -->
        <div class="mb-12 max-w-3xl mx-auto">
            <p class="text-[14px] md:text-xl text-gray-600 leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('list.request') }}"
                wire:navigate
                class="group bg-[#364872] hover:bg-[#2d3a5f] text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center gap-2 no-underline">
                <span>Ajukan Permohonan Sekarang</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>


    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typingElement = document.getElementById('typing-text');
        const texts = ['Permohonan Sistem Informasi & Data', 'Permohonan Kehumasan'];
        let currentTextIndex = 0;
        let currentCharIndex = 0;
        let isDeleting = false;

        function typeText() {
            const currentText = texts[currentTextIndex];

            if (isDeleting) {
                typingElement.textContent = currentText.substring(0, currentCharIndex - 1);
                currentCharIndex--;
            } else {
                typingElement.textContent = currentText.substring(0, currentCharIndex + 1);
                currentCharIndex++;
            }

            if (!isDeleting && currentCharIndex === currentText.length) {
                setTimeout(() => isDeleting = true, 2000);
            } else if (isDeleting && currentCharIndex === 0) {
                isDeleting = false;
                currentTextIndex = (currentTextIndex + 1) % texts.length;
            }

            setTimeout(typeText, isDeleting ? 50 : 100);
        }

        typeText();
    });
</script>