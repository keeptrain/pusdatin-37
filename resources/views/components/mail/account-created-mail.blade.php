<x-mail.partials.base-pusdatin-layout
    title="Akun anda berhasil dibuat - Pusat Data dan Teknologi Informasi Dinas Kesehatan">

    <p style="font-size: 14px; color: #333;">
        Kepada Yth.<br>
        <strong>{{ $data['name'] }}</strong><br>
    </p>

    <p style="font-size: 14px; color: #333; margin-top: 20px;">
        Dengan hormat,
    </p>

    <p style="font-size: 14px; color: #333;">
        Akun anda berhasil dibuat dengan detail sebagai berikut:
    </p>

    <ul style="font-size: 14px; color: #333;">
        <li>Email: {{ $data['email'] }}</li>
        <li>Password: {{ $data['password'] }}</li>
    </ul>

    <p style="font-size: 14px; color: #333;">
        Harap untuk segera login ke dalam akun anda dan mengubah password.
    </p>

    <a href="http://pusdatin-37.test/login"
        style="display: inline-block; padding: 5px 10px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 5px;">
        Login ke dalam akun anda
    </a>

    <p style="font-size: 14px; color: #333; margin-top: 20px;">
        Hormat kami,<br>
        <strong>Pusat Data dan Teknologi Informasi</strong><br>
        Dinas Kesehatan
    </p>
</x-mail.partials.base-pusdatin-layout>