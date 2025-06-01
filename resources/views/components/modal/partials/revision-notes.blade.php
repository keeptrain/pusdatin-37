<template x-if="revisionPart.includes('1')">
    <flux:textarea wire:model.defer="revisionNotes.1" cols="66" rows="2"
        placeholder="Catatan untuk SPBE " resize="vertical" />
</template>

<template x-if="revisionPart.includes('2')">
    <flux:textarea wire:model.defer="revisionNotes.2" cols="66" rows="2"
        placeholder="Catatan untuk SOP aplikasi" resize="vertical" />
</template>

<template x-if="revisionPart.includes('3')">
    <flux:textarea wire:model.defer="revisionNotes.3" cols="66" rows="2"
        placeholder="Catatan untuk pakta integritas pemanfaatan aplikasi" resize="vertical" />
</template>

<template x-if="revisionPart.includes('4')">
    <flux:textarea wire:model.defer="revisionNotes.4" cols="66" rows="2"
        placeholder="Catatan untuk RFC Pusdatinkes" resize="vertical" />
</template>

<template x-if="revisionPart.includes('5')">
    <flux:textarea wire:model.defer="revisionNotes.5" cols="66" rows="2"
        placeholder="Catatan untuk NDA pusdatin dinkes" resize="vertical" />
</template>