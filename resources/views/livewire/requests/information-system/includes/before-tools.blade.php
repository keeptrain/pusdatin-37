{{-- resources/views/livewire/requests/information-system/includes/before-tools.blade.php --}}
<div class="flex justify-between items-center mb-4">
    <div class="flex items-center space-x-2">
        @if(count($this->getSelected()) > 0)
        <flux:button size="sm" variant="danger" icon="trash"
            wire:click="deleteSelected"
            wire:confirm="Are you sure you want to delete the selected items?">
            Delete ({{ count($this->getSelected()) }})
        </flux:button>
        @endif
    </div>
</div>