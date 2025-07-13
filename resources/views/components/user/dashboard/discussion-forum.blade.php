<div class="flex items-start gap-4">
    <x-lucide-message-circle-more class="size-8 text-accent dark:text-white" />
    <div class="flex flex-col space-y-2">
        <flux:heading size="xl" class="text-accent dark:text-white">Forum Diskusi</flux:heading>
        <flux:subheading class="text-accent dark:text-white">Apakah kamu mempunyai kendala saat menggunakan layanan
            Pusdatin?</flux:subheading>
        <div>
            <!-- Button to toggle discussion form -->
            <flux:button @click="createDiscussion = !createDiscussion" x-show="!createDiscussion" size="sm"
                icon:trailing="chevron-down" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">
                Buat forum diskusi
            </flux:button>
        </div>
    </div>
</div>

<livewire:discussions.index :requests="$requests" :perPage="3" />