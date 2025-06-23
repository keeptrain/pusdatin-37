<div class="flex items-center justify-between bg-blue-50 px-6 py-2 border rounded-lg mt-4">
    <flux:legend>
        <span class="text-blue-800 font-normal">Bantu kami untuk meningkatkan layanan dengan memberikan
            rating.
        </span>
    </flux:legend>
    <button x-on:click="$dispatch('modal-show', { name: 'rating-modal' });"
        class="flex items-center gap-2 font-bold text-blue-800 px-4 py-2 hover:text-blue-600 cursor-pointer">
        <flux:icon.star class="w-6 h-6" />
        Beri rating
    </button>
    {{-- <flux:button variant="primary" icon="star" color="yellow"
        x-on:click="$dispatch('modal-show', { name: 'rating-modal' });">Beri rating
    </flux:button> --}}
</div>