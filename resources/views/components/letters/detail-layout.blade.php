@props([
    'letterId' => null,
])
<section x-data="{
    activeTab: 'Overview',
    mobileDetailsOpen: false,
    init() {
        const path = window.location.pathname;
        if (path.includes('overview')) this.activeTab = 'Overview';
        else if (path.includes('activity')) this.activeTab = 'Activity';
        else if (path.includes('chat')) this.activeTab = 'Chat';
    },
    goTo(tab) {
        const routes = {
            'Overview': '{{ route('letter.detail', $letterId) }}',
            'Activity': '{{ route('letter.activity', $letterId) }}',
            'Chat': '{{ route('letter.chat', $letterId) }}',
        };
        window.location.href = routes[tab];
    }
}" class="min-h-screen" x-init="init()">

    <flux:heading size="xl" class="p-4">Application Request Service</flux:heading>

    <!-- Main Content -->
    <div class="flex flex-col h-full">
        <!-- Tabs -->
        <div class="border-b border-gray-200 overflow-x-auto">
            <div class="flex space-x-4 md:space-x-8 px-2 md:px-2">
                <template x-for="tab in ['Overview', 'Activity', 'Chat']" :key="tab">
                    <button @click="goTo(tab)"
                        :class="{
                            'text-blue-600 border-b-2 border-blue-600': activeTab === tab,
                            'text-gray-500 hover:text-gray-700': activeTab !== tab
                        }"
                        class="py-4 px-2 text-sm font-medium whitespace-nowrap" x-text="tab">
                    </button>
                </template>
            </div>
        </div>

        @if (isset($rightSidebar))
        <!-- Mobile Details Toggle (visible on small screens) -->
        <div class="lg:hidden border-b border-gray-200 p-4">
            <button @click="mobileDetailsOpen = !mobileDetailsOpen"
                class="flex items-center justify-between w-full text-left">
                <span class="font-medium">Details</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    :class="{ 'rotate-180': mobileDetailsOpen }">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
        </div>
        @endif

        <!-- Content Area -->
        <div class="flex flex-col lg:flex-row flex-1">
            <!-- Left Content -->
            <div class="flex-1 p-4 md:p-3">
                <!-- No active orders section -->
                <div>
                    {{ $slot }}
                </div>
            </div>

            <!-- Right Sidebar - Hidden on mobile unless toggled -->
            @if (isset($rightSidebar))
                <div class="lg:w-80 lg:border-l lg:border-gray-200 p-4 md:p-6 bg-white"
                    :class="{ 'hidden lg:block': !mobileDetailsOpen }"
                    x-show="mobileDetailsOpen || window.innerWidth >= 1024"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="mb-32">
                        {{ $rightSidebar }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<script>
    // Listen for window resize to handle responsive behavior
    window.addEventListener('resize', function() {
        const alpineData = Alpine.store('data');
        if (window.innerWidth >= 1024 && Alpine.$data(document.body).mobileDetailsOpen === false) {
            // Force details to be shown on large screens
            document.querySelector('[x-show="mobileDetailsOpen || window.innerWidth >= 1024"]').style.display =
                'block';
        }
    });
</script>
