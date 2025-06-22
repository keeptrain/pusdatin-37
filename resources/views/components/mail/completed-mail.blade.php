<!DOCTYPE html>
<html>

<head>
    <title>Pemberitahuan Perbaikan Dokumen - Pusat Data dan Teknologi Informasi Dinas Kesehatan</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0;">
    <!-- Header -->
    <div style="padding: 2px; text-align: center;">
        <img src="{{ $message->embed(storage_path('app/assets/pusdatin-logo.jpg')) }}" alt="Logo" style="width: 300px;">
    </div>

    <!-- Body -->
    <div style="padding: 20px; max-width: 600px; margin: 0 auto;">
        <p style="font-size: 14px; color: #333;">
            Kepada Yth.<br>
            <strong>Bapak/Ibu Pemohon</strong><br>
        </p>

        <p style="font-size: 14px; color: #333; margin-top: 20px;">
            Dengan hormat,
        </p>

        <p style="font-size: 14px; color: #333;">
            Permohonan layanan dengan tema {{ $data['theme'] }} untuk tanggal selesai {{ $data['completed_date'] }}
            dan target {{ $data['target'] }} telah selesai, berikut kami lampirkan link google drive sesuai dengan
            permintaan materi:
        </p>

        <ul style="font-size: 14px; color: #333;">
            @foreach ($data['media'] as $key => $value)
                <div>
                    <li style="margin-top: 10px;">{{ $key }}</li>
                    <p style="margin: 0;">Link: {{ $value }}</p>
                </div>
            @endforeach
        </ul>

        <p style="font-size: 14px; color: #333;">
            Setelah itu, kami memohon kesediaan Bapak/Ibu untuk segera memberikan rating terhadap pengerjaan permohonan
            tersebut. Silahkan klik tombol di bawah ini:
        </p>
        <a href="{{ $data['rating_link'] }}"
            style="display: inline-block; padding: 5px 10px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 5px;">Berikan
            Rating</a>

        <p style="font-size: 14px; color: #333; margin-top: 20px;">
            Hormat kami,<br>
            <strong>Pusat Data dan Teknologi Informasi</strong><br>
            Dinas Kesehatan
        </p>
    </div>

    <!-- Footer -->
    <div style="background-color: #f4f4f4; padding: 10px; text-align: center; font-size: 12px; color: #777;">
        &copy; 2025 Pusat Data dan Teknologi Informasi Dinas Kesehatan. All rights reserved.
    </div>
</body>

</html>