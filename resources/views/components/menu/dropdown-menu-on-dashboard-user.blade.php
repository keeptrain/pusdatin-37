<!-- Dropdown Content -->
<div x-show="openDropdown" @mouseenter="openDropdown = true" @mouseleave="openDropdown = false"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
    class="absolute left-0 w-100 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-10 mt-1 py-1"
    x-cloak>
    <!-- Item 1: Sistem Informasi & Data -->
    @if (Route::currentRouteName() != 'si-data.form')
        <a @click.prevent="handleSIDataRequest()"
            class="flex items-start px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-150">
            <div class="flex-shrink-0 mt-1">
                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <x-lucide-code class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                </div>
            </div>
            <div class="ml-3">
                <p class="font-medium">Layanan Sistem Informasi & Data</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Terkait dengan permbuatan/pengembangan aplikasi dan pengelolaan data
                </p>
            </div>
        </a>
        <hr class="border-t border-gray-200 dark:border-gray-700 mx-4 my-1">
    @endif

    <!-- Item 2: Kehumasan -->
    @if (Route::currentRouteName() != 'pr.form')
        <a @click.prevent="handlePRRequest()"
            class="flex items-start px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-150">
            <div class="flex-shrink-0 mt-1">
                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <x-lucide-file-video class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                </div>
            </div>
            <div class="ml-3">
                <p class="font-medium">Layanan Kehumasan</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Terkait dengan kehumasan seperti pembuatan video, audio, poster, dll
                </p>
            </div>
        </a>
    @endif
</div>