@props([
    'id' => null,
    'overViewRoute' => null,
    'activityRoute' => null,
])

<section x-data="tabsNavigation()" class="min-h-screen flex flex-col" x-init="init()">
    <!-- Tabs Navigation - Fixed Height -->
    <nav class="flex-shrink-0 border-b border-gray-200 dark:border-zinc-700 h-[55px]">
        <div class="h-full overflow-x-auto overflow-y-hidden">
            <div class="flex items-center space-x-4 md:space-x-8 px-2 md:px-2 h-full min-w-max">
                <x-layouts.requests.tab tab="Overview" active-tab="activeTab" label="Overview" />
                <x-layouts.requests.tab tab="Activity" active-tab="activeTab" label="Aktivitas" />
                <template x-if="isInformationSystemRoute">
                    <x-layouts.requests.tab tab="Meeting" active-tab="activeTab" label="Meeting" />
                </template>
                <template x-if="isInformationSystemRoute">
                    <x-layouts.requests.tab tab="Version" active-tab="activeTab" label="Versi" />
                </template>
            </div>
        </div>
    </nav>

    @if (isset($rightSidebar))
    <!-- Mobile Details Toggle - Fixed Height -->
    <div class="lg:hidden flex-shrink-0 border-b border-gray-200 dark:border-zinc-700 px-4 py-3">
        <button @click="mobileDetailsOpen = !mobileDetailsOpen"
            class="flex items-center justify-between w-full text-left">
            <span class="font-medium">Lihat Detail</span>
            <flux:icon.chevron-up-down class="size-5" />
        </button>
    </div>
    @endif

    <!-- Content Area - Flexible Height -->
    <div class="flex-1 flex flex-col lg:flex-row min-h-0">
        <!-- Left Content -->
        <main class="flex-1 p-4 md:p-3 min-h-0 overflow-y-auto">
            {{ $slot }}
        </main>

        <!-- Right Sidebar -->
        @if (isset($rightSidebar))
            <aside class="lg:w-90 lg:border-l lg:border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 lg:flex-shrink-0"
                x-show="mobileDetailsOpen || $store.breakpoints.lg"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-2">
                <div class="p-4 md:p-6 h-full overflow-y-auto">
                    <div class="pb-32">
                        {{ $rightSidebar }}
                    </div>
                </div>
            </aside>
        @endif
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
                this.setupBreakpointStore();
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
                
                if (routes[tab]) {
                    Livewire.navigate(routes[tab]);
                }
            },
            
            setupBreakpointStore() {
                // Create Alpine store for breakpoints to avoid repeated window.innerWidth checks
                if (!Alpine.store('breakpoints')) {
                    Alpine.store('breakpoints', {
                        lg: window.innerWidth >= 1024,
                        updateBreakpoints() {
                            this.lg = window.innerWidth >= 1024;
                        }
                    });
                }
            },
            
            setupResponsiveBehavior() {
                let resizeTimer;
                
                const handleResize = () => {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(() => {
                        Alpine.store('breakpoints').updateBreakpoints();
                        
                        // Auto-open sidebar on desktop
                        if (window.innerWidth >= 1024) {
                            this.mobileDetailsOpen = true;
                        }
                    }, 100); // Debounce resize events
                };
                
                window.addEventListener('resize', handleResize, { passive: true });
                
                // Cleanup on component destroy
                this.$cleanup = () => {
                    window.removeEventListener('resize', handleResize);
                    clearTimeout(resizeTimer);
                };
            }
        };
    }
</script>

<style>
    /* Optimize scrolling performance */
    .overflow-y-auto {
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
    }
    
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background-color: rgba(156, 163, 175, 0.7);
    }
    
    /* Prevent layout shifts during transitions */
    [x-transition] {
        will-change: transform, opacity;
    }
    
    /* Optimize paint containment */
    nav, main, aside {
        contain: layout style paint;
    }
</style>