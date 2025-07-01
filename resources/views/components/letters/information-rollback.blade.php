<template x-if="status === 'pending'">
    <ul>
        <li>
            1.Mengembalikan status ke permohonan masuk
        </li>
        <li>
            2.Menghapus pesan tracking selain permohonan masuk
        </li>
        <li>
            3.Menghapus pesan tracking selain permohonan masuk
        </li>
    </ul>
</template>

<template x-if="status === 'disposition'">
    <ul>
        <li>
            1.Mengembalikan status ke disposisi
        </li>
    </ul>
</template>

<template x-if="status === 'process'">
    <flux:text>Ubah status ke proses</flux:text>
    <ul>
        <li>
            1.Mengembalikan status ke proses
        </li>
    </ul>
</template>

<template x-if="status == 'approved_kasatpel'">
    <ul>
        <li>
            1.Mengembalikan status ke disetujui kasatpel
        </li>
    </ul>
</template>

<template x-if="status == 'approved_kapusdatin'">
    <ul>
        <li>
            1.Mengembalikan status ke disetujui kapusdatin
        </li>
    </ul>
</template>

<template x-if="status == 'process'">
    <ul>
        <li>
            1.Mengembalikan status ke permohonan masuk
        </li>
    </ul>
</template>

<template x-if="status == 'rejected'">
    <ul>
        <li>
            1.Mengembalikan status ke permohonan masuk
        </li>
    </ul>
</template>