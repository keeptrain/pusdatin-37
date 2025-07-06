@props([
    'id' => null,
    'overViewRoute' => null,
    'activityRoute' => null,
])
<section x-data="tabsNavigation()" class="min-h-screen" x-init="init()">
    <!-- Main Content -->
    <div class="grid grid-rows-[auto_1fr] h-full">
        <!-- Tabs -->
        <nav class="border-b border-gray-200 dark:border-zinc-700 h-[55px] overflow-x-hidden">
            <div class="flex space-x-4 md:space-x-8 px-2 md:px-2 w-max">
                <x-layouts.requests.tab tab="Overview" active-tab="activeTab" label="Overview" />
                <x-layouts.requests.tab tab="Activity" active-tab="activeTab" label="Aktivitas" />
                <template x-if="isInformationSystemRoute">
                    <x-layouts.requests.tab tab="Meeting" active-tab="activeTab" label="Meeting" />
                </template>
                <template x-if="isInformationSystemRoute">
                    <x-layouts.requests.tab tab="Version" active-tab="activeTab" label="Versi" />
                </template>
            </div>
        </nav>

        @if (isset($rightSidebar))
        <!-- Mobile Details Toggle (visible on small screens) -->
        <div class="lg:hidden border-b border-gray-200 p-4">
            <button @click="mobileDetailsOpen = !mobileDetailsOpen"
                class="flex items-center justify-between w-full text-left">
                <span class="font-medium">Lihat Detail</span>
                <flux:icon.chevron-up-down class="size-5" />
            </button>
        </div>
        @endif

        <!-- Content Area -->
        <div class="flex flex-col lg:flex-row flex-1">
            <!-- Left Content -->
            <main class="flex-1 p-4 md:p-3">
                {{ $slot }}
            </main>

            <!-- Right Sidebar - Hidden on mobile unless toggled -->
            @if (isset($rightSidebar))
                <div class="lg:w-90 lg:border-l lg:border-gray-200 p-4 md:p-6 bg-white dark:bg-zinc-800 dark:border-zinc-700"
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
    function tabsNavigation() {
        return {
            activeTab: 'Overview',
            mobileDetailsOpen: false,
            isInformationSystemRoute: window.location.pathname.includes('information-system/'),
            isPublicRelationRoute: window.location.pathname.includes('public-relation/'),
            
            init() {
                this.setActiveTabFromRoute();
                this.setupResponsiveBehavior();
            },
            
            setActiveTabFromRoute() {
                const path = window.location.pathname;
                if (path.includes('overview')) this.activeTab = 'Overview';
                else if (path.includes('activity')) this.activeTab = 'Activity';
                else if (this.isInformationSystemRoute && path.includes('meeting')) this.activeTab = 'Meeting';
                else if (this.isInformationSystemRoute && path.includes('version')) this.activeTab = 'Version';
            },
            
            goTo(tab) {
                const routes = {
                    'Overview': '{{ route($overViewRoute, $id) }}',
                    'Activity': '{{ route($activityRoute, $id) }}',
                    'Meeting': '{{ route('is.meeting', $id) }}',
                    'Version': '{{ route('comparison.version', $id) }}'
                };
                window.location.href = routes[tab];
            },
            
            setupResponsiveBehavior() {
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        this.mobileDetailsOpen = true;
                    }
                });
            }
        };
    }
</script>
