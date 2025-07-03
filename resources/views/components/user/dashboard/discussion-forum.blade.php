<div class="flex items-start gap-4">
    <x-lucide-message-circle-more class="size-8 text-testing-100" />
    <div class="flex flex-col space-y-2">
        <flux:heading size="xl" class="text-testing-100">Forum Diskusi</flux:heading>
        <flux:subheading class="text-testing-100">Apakah kamu mempunyai kendala saat menggunakan layanan
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

<!-- Discussion Form -->
<form x-show="createDiscussion" class="mt-6 ml-12 space-y-2"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-4">

    <div class="grid grid-cols-1 md:grid-cols-2 items-start gap-4 md:gap-6">
        <flux:input label="Topic" placeholder="Topik yang ingin kamu diskusikan" />
        <flux:select label="Terkait dengan layanan yang di ajukan?">
            <flux:select.option value="">Pilih Kategori</flux:select.option>
            <flux:select.option value="">Selain itu</flux:select.option>

        </flux:select>
    </div>
    <flux:textarea label="Deskripsi" placeholder="Deskripsi masalah yang ingin kamu diskusikan" rows="2" />

    <div class="flex justify-end mt-6 space-x-2">
        <flux:button type="button" variant="subtle" @click="createDiscussion = !createDiscussion">Batal
        </flux:button>
        <flux:button type="submit" variant="primary" icon="plus">Buat</flux:button>
    </div>
</form>

<!-- Empty State -->
<div x-show="!createDiscussion" class="px-8 py-8 text-center"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95">

    <div class="max-w-md mx-auto">
        <!-- Illustration -->
        <div class="relative mb-6">
            <div
                class="w-20 h-20 mx-auto bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                <x-lucide-message-circle class="size-10 text-blue-500" />
            </div>
            <!-- Floating elements -->
            <div class="absolute -top-2 -right-2 w-6 h-6 bg-blue-400 rounded-full animate-bounce"></div>
            <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-blue-800 rounded-full animate-bounce"
                style="animation-delay: 0.2s"></div>
        </div>

        <flux:heading size="lg">
            Belum ada diskusi
        </flux:heading>
    </div>
</div>