<x-mail.partials.base-pusdatin-layout :message="$message">
    <p style="font-size: 14px; color: #333;">
        Kepada Yth.<br>
        <strong>Bapak/Ibu Pemohon</strong><br>
    </p>

    <p style="font-size: 14px; color: #333; margin-top: 20px;">
        Dengan hormat,
    </p>

    <p style="font-size: 14px; color: #333; margin-top: 20px;">
        @if ($rating == 1 || $rating == 2)
            Mohon maaf atas pengalaman layanan yang tidak memuaskan.

            Sangat disesalkan hal ini terjadi, karena kepuasan Anda adalah prioritas utama. Penilaian yang diberikan menjadi
            perhatian serius dan akan ditindaklanjuti dengan evaluasi internal secara menyeluruh.

            Tindakan perbaikan akan segera dilakukan untuk mencegah kejadian serupa terulang.

            Terima kasih telah menyampaikan hal ini. Umpan balik Anda sangat penting untuk perbaikan.
        @elseif ($rating == 3)
            Terima kasih atas waktu dan penilaian yang telah diberikan.

            Masukan Anda telah diterima dan menjadi catatan penting untuk evaluasi. Umpan balik seperti ini sangat membantu
            dalam meninjau kembali standar layanan agar menjadi lebih baik.

            Sebuah komitmen untuk dapat memberikan pengalaman yang lebih memuaskan di masa mendatang.
        @elseif ($rating == 4 || $rating == 5)
            Terima kasih telah meluangkan waktu untuk memberikan penilaian layanan.

            Sangat menyenangkan mengetahui Anda memiliki pengalaman yang memuaskan. Penilaian seperti ini menjadi
            penyemangat terbaik bagi seluruh tim untuk terus memberikan layanan yang berkualitas.

            Semoga dapat melayani Anda kembali di kesempatan berikutnya.
        @endif
    </p>

    <p style="font-size: 14px; color: #333; margin-top: 20px;">
        Hormat kami,<br>
        <strong>Pusat Data dan Teknologi Informasi</strong><br>
        Dinas Kesehatan
    </p>
</x-mail.partials.base-pusdatin-layout>