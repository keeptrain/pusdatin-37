<template x-for="(statuses, dateLabel) in notifications" :key="dateLabel">
    <template x-if="activeTab === 'all' || Object.keys(statuses).some(status => {
        if (activeTab === 'disposisi') return status === 'Permohonan Masuk';
        if (activeTab === 'revisi') return status === 'App\\States\\Replied';
        if (activeTab === 'disetujui') return status === 'App\\States\\ApprovedKasatpel';
        return true;
    })">
        <div>
            <div class="px-4 py-2 bg-zinc-50 border-b border-gray-200 mt-4">
                <h3 class="text-sm font-medium text-gray-500" x-text="dateLabel"></h3>
            </div>

            <template x-for="(notificationsByStatus, status) in statuses" :key="status">
                <template x-if="activeTab === 'all' ||
                    (activeTab === 'disposisi' && status === 'Permohonan Masuk') ||
                    (activeTab === 'revisi' && status === 'App\\States\\Replied') || 
                    (activeTab === 'disetujui' && status === 'App\\States\\ApprovedKasatpel')">

                    <template x-for="notification in (notificationsByStatus)" :key="notification.id">
                        <div @click="$wire.goDetailPage(notification.id)"
                            class="relative flex gap-3 p-4 bg-zinc-50 hover:bg-blue-50 transition cursor-pointer">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center text-blue-500">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                        </path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-700" x-text="notification.message"></p>
                            
                                <div class="flex items-center flex-wrap gap-1 md:gap-2 text-xs text-gray-400 mt-1">
                                    <p class="truncate" x-text="notification.username"></p>
                                    <p class="shrink-0">-</p>
                                    <p class="truncate" x-text="notification.created_at"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>
            </template>
        </div>
    </template>
</template>