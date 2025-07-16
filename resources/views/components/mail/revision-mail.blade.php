<x-mail.partials.base-pusdatin-layout
    title="Pemberitahuan Perbaikan Dokumen - Pusat Data dan Teknologi Informasi Dinas Kesehatan">

    <!-- Body -->
    <div style="max-width: 600px; margin: 0 auto;">
        <p style="font-size: 14px; color: #333;">
            Kepada Yth.<br>
            <strong>Bapak/Ibu Pemohon</strong><br>
        </p>

        <p style="font-size: 14px; color: #333; margin-top: 20px;">
            Dengan hormat,
        </p>

        <p style="font-size: 14px; color: #333;">
            Setelah kami melakukan verifikasi terhadap dokumen permohonan yang telah Bapak/Ibu ajukan, kami menemukan
            beberapa hal yang perlu diperbaiki agar permohonan dapat diproses lebih lanjut. Adapun hal-hal yang perlu
            diperbaiki adalah sebagai berikut:
        </p>

        {{-- <ul style="font-size: 14px; color: #333; list-style: none; padding: 0;">
            @foreach ($data['revision_notes'] as $key => $value)
            <li style="display: inline-block; vertical-align: top; margin-bottom: 10px;">
                <span style="display: inline-block; margin-right: 10px;">{{ $key }}</span>
                <p style="display: inline-block; margin: 0;">Catatan: {{ $value }}</p>
            </li>
            @endforeach
        </ul> --}}

        <ul style="font-size: 14px; color: #333;">
            @foreach ($data['revision_notes'] as $key => $value)
                <div>
                    <li style="margin-top: 10px;">{{ $key }}</li>
                    <p style="margin: 0;">Catatan: {{ $value }}</p>
                </div>
            @endforeach
        </ul>

        <p style="font-size: 14px; color: #333;">
            Untuk itu, kami memohon kesediaan Bapak/Ibu untuk segera melakukan perbaikan dokumen sesuai dengan catatan
            yang telah kami sampaikan. Silahkan klik tombol di bawah ini untuk:
        </p>
        <a href="{{ $data['url'] }}"
            style="display: inline-block; padding: 5px 10px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 5px;">Perbaikan
            Dokumen</a>

        {{-- <p style="font-size: 14px; color: #333;">
            Apabila Bapak/Ibu memerlukan informasi lebih lanjut atau mengalami kendala dalam proses perbaikan dokumen,
            silakan menghubungi kami melalui:
        </p>

        <ul style="font-size: 14px; color: #333;">
            <li>Email: <a href="mailto:pusdatin@dinkes.go.id" style="color: #007bff;">pusdatin@dinkes.go.id</a></li>
            <li>Telepon: (021) 123-456</li>
        </ul>

        <p style="font-size: 14px; color: #333;">
            Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerjasama Bapak/Ibu, kami ucapkan terima
            kasih.
        </p> --}}

        <p style="font-size: 14px; color: #333; margin-top: 20px;">
            Hormat kami,<br>
            <strong>Pusat Data dan Teknologi Informasi</strong><br>
            Dinas Kesehatan
        </p>
    </div>
</x-mail.partials.base-pusdatin-layout>