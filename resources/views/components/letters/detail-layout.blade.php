@props([
    'id' => null,
    'overViewRoute' => null,
    'activityRoute' => null,
])
<section x-data="{
    activeTab: 'Overview',
    mobileDetailsOpen: false,
    isInformationSystemRoute: window.location.pathname.includes('letter/'),
    isPublicRelationRoute: window.location.pathname.includes('public-relation/'),
    init() {
        const path = window.location.pathname;

        // Set active tab based on current route
        if (path.includes('overview')) this.activeTab = 'Overview';
        else if (path.includes('activity')) this.activeTab = 'Activity';
        else if (this.isInformationSystemRoute && path.includes('meeting')) this.activeTab = 'Meeting';
        else if (this.isInformationSystemRoute && path.includes('version')) this.activeTab = 'Version';
    },
    goTo(tab) {
        const routes = {
            'Overview': '{{ route( $overViewRoute , $id) }}',
            'Activity': '{{ route( $activityRoute , $id) }}',
            {{-- 'Chat': '{{ route('letter.chat', $id) }}', --}}
            'Meeting': '{{ route('is.meeting', $id) }}',
            'Version': '{{ route('letter.version', $id) }}'
        };
        window.location.href = routes[tab];
    }
}" class="min-h-screen" x-init="init()">
    <!-- Main Content -->
    <div class="flex flex-col h-full">
        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
            <div class="flex space-x-4 md:space-x-8 px-2 md:px-2">
                <button @click="goTo('Overview')"
                    :class="{
                        'text-blue-600 border-blue-600 dark:border-blue-600': activeTab === 'Overview',
                        'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600': activeTab !== 'Overview'
                    }"
                    class="py-4 px-2 text-sm font-medium border-b-2 whitespace-nowrap cursor-pointer focus:outline-none">
                    Overview
                </button>

                <!-- Activity Tab -->
                <button @click="goTo('Activity')"
                    :class="{
                        'text-blue-600 border-blue-600 dark:border-blue-600': activeTab === 'Activity',
                        'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600': activeTab !== 'Activity'
                    }"
                    class="py-4 px-2 text-sm font-medium border-b-2 whitespace-nowrap cursor-pointer focus:outline-none">
                    Activity
                </button>

                <!-- Meeting Tab -->
                <template x-if="isInformationSystemRoute">
                    <button @click="goTo('Meeting')"
                        :class="{
                            'text-blue-600 border-blue-600 dark:border-blue-600': activeTab === 'Meeting',
                            'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600': activeTab !== 'Meeting'
                        }"
                        class="py-4 px-2 text-sm font-medium border-b-2 whitespace-nowrap cursor-pointer focus:outline-none">
                        Meeting
                    </button>
                </template>

                <!-- Version Tab -->
                <template x-if="isInformationSystemRoute">
                    <button @click="goTo('Version')"
                        :class="{
                            'text-blue-600 border-blue-600 dark:border-blue-600': activeTab === 'Version',
                            'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600': activeTab !== 'Version'
                        }"
                        class="py-4 px-2 text-sm font-medium border-b-2 whitespace-nowrap cursor-pointer focus:outline-none">
                        Version
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
                <div class="lg:w-90 lg:border-l lg:border-gray-200 p-4 md:p-6 bg-white"
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
