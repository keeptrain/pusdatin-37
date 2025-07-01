<section id="faq" class="py-16 lg:py-24 bg-none">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                Frequently Asked Questions
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Find answers to common questions about our services
            </p>
        </div>

        <!-- FAQ Items -->
        <div class="space-y-4">
            <!-- FAQ Item 1 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <button
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200 "
                    onclick="toggleFAQ('faq1')"
                    aria-expanded="false">
                    <span class="text-lg font-medium text-gray-900 pr-4">
                        Who can use Pusdatin services?
                    </span>
                    <svg
                        id="faq1-icon"
                        class="w-5 h-5 text-gray-500 transform transition-transform duration-200"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div
                    id="faq1"
                    class="hidden px-6 pb-5 text-gray-600 leading-relaxed">
                    Pusdatin services are available to government officials, authorized personnel, and registered public institutions who need access to official data or multimedia services for government-related purposes.
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <button
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200 "
                    onclick="toggleFAQ('faq2')"
                    aria-expanded="false">
                    <span class="text-lg font-medium text-gray-900 pr-4">
                        How long does it take to process a request?
                    </span>
                    <svg
                        id="faq2-icon"
                        class="w-5 h-5 text-gray-500 transform transition-transform duration-200"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div
                    id="faq2"
                    class="hidden px-6 pb-5 text-gray-600 leading-relaxed">
                    Processing times vary by service type. Information System & Data Requests typically take 3-5 business days, while Multimedia & Public Relations requests may take 5-7 business days depending on complexity and current workload.
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <button
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200 "
                    onclick="toggleFAQ('faq3')"
                    aria-expanded="false">
                    <span class="text-lg font-medium text-gray-900 pr-4">
                        Is there a fee for using Pusdatin services?
                    </span>
                    <svg
                        id="faq3-icon"
                        class="w-5 h-5 text-gray-500 transform transition-transform duration-200"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div
                    id="faq3"
                    class="hidden px-6 pb-5 text-gray-600 leading-relaxed">
                    Most standard services are provided free of charge to government agencies. However, specialized services or requests requiring extensive resources may incur a fee, which will be communicated during the request process.
                </div>
            </div>

            <!-- FAQ Item 4 - Additional FAQ -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <button
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200 "
                    onclick="toggleFAQ('faq4')"
                    aria-expanded="false">
                    <span class="text-lg font-medium text-gray-900 pr-4">
                        What documents do I need to submit a request?
                    </span>
                    <svg
                        id="faq4-icon"
                        class="w-5 h-5 text-gray-500 transform transition-transform duration-200"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div
                    id="faq4"
                    class="hidden px-6 pb-5 text-gray-600 leading-relaxed">
                    Required documents typically include official identification, institutional authorization letter, and detailed description of your request. Specific requirements may vary depending on the type of service requested.
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function toggleFAQ(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById(id + '-icon');
        const button = content.previousElementSibling;

        if (content.classList.contains('hidden')) {
            // Open
            content.classList.remove('hidden');
            content.classList.add('animate-fadeIn');
            icon.style.transform = 'rotate(180deg)';
            button.setAttribute('aria-expanded', 'true');
        } else {
            // Close
            content.classList.add('hidden');
            content.classList.remove('animate-fadeIn');
            icon.style.transform = 'rotate(0deg)';
            button.setAttribute('aria-expanded', 'false');
        }
    }
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
</style>