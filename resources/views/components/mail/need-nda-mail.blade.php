<x-mail.partials.base-pusdatin-layout title="Pemberitahuan membutuhkan dokumen NDA" :message="$message">
    <p style="font-size: 14px; color: #333;">
        Kepada Yth.<br>
        <strong>{{ $data['name'] }}</strong><br>
    </p>

    <p style="font-size: 14px; color: #333; margin-top: 20px;">
        Dengan hormat,
    </p>

    <p style="font-size: 14px; color: #333;">
        Permohonan layanan yang anda ajukan dengan judul <span style="font-weight: bold;">{{ $data['title'] }}</span>,
        jenis layanan <span style="font-weight: bold;">Sistem
            Informasi & Data</span>, dan tanggal pengajuan <span style="font-weight: bold;">{{ $data['created_at'] }}
        </span>. Agar dapat di selesaikan, kami membutuhkan dokumen Surat Perjanjian Kerahasiaan (NDA).
    </p>

    <p style="font-size: 14px; color: #333;">
        Harap kesediaannya untuk segera mengirimkan dokumen Surat Perjanjian Kerahasiaan (NDA).
        Berikut tata cara mengirimkan dokumen Surat Perjanjian Kerahasiaan:
    </p>
    <p style="font-size: 14px; color: #333;">1. Klik tombol di bawah ini untuk diarahkan ke halaman terkait (memerlukan
        login)</p>
    <a href="{{ $data['url'] }}"
        style="display: inline-block; padding: 3px 6px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px; cursor: pointer;">
        Upload dokumen NDA
    </a>
    <p style="font-size: 14px; color: #333;">2. Selanjutnya akan terdapat sebuah peringatan seperti gambar berikut, lalu
        klik tombol "Silahkan upload disini"
    </p>

    <img src="{{ $message->embed(storage_path('app/public/callout-additional-document.png')) }}" alt="NDA"
        style="width: 600px;">

    <p style="font-size: 14px; color: #333; margin-top: 20px;">
        Hormat kami,<br>
        <strong>Pusat Data dan Teknologi Informasi</strong><br>
        Dinas Kesehatan
    </p>
</x-mail.partials.base-pusdatin-layout>