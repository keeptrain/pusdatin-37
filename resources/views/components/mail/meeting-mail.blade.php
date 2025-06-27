<x-mail.partials.base-pusdatin-layout title="Undangan Rapat: Pembahasan {{ $data['topic'] }}" :message="$message">
    <p style="font-size: 14px; color: #333;">
        Kepada Yth.<br>
        <strong>{{ $data['name']}}</strong><br>
    </p>

    <p style="font-size: 14px; color: #333; margin-top: 20px;">
        Dengan hormat,
    </p>

    @if ($mode === 'create')
        <p style="font-size: 14px; color: #333;">
            Bersama ini kami mengundang {{ $data['name'] }} untuk hadir dalam rapat pembahasan
            <strong>{{ $data['topic'] }}</strong> berhubungan dengan permohonan layanan Sistem Informasi & Data yang di
            ajukan dengan judul <strong>{{ $data['title'] }}</strong>, yang akan dilaksanakan pada:
        </p>
        <strong style="font-size: 15px; color: #333;">
            Hari, Tanggal: {{ \Carbon\Carbon::parse($data['date'])->format('l, d F Y') }}
            <br>
            Waktu: {{ $data['start'] }} - {{ $data['end'] }}
            <br>
            @if (isset($data['location']))
                Lokasi: {{ $data['location'] }}
            @elseif (isset($data['link']))
                Link: <a href="{{ $data['link'] }}" style="color: #0000FF;">{{ $data['link'] }}</a>
                <br>
                Password: <span>{{ $data['password'] }}</span>
            @endif
        </strong>
    @elseif ($mode === 'delete')
        <p style="font-size: 14px; color: #333;">
            Kami informasikan bahwa rapat pembahasan
            <strong>{{ $data['topic'] }}</strong> berhubungan dengan permohonan layanan Sistem Informasi & Data yang di
            ajukan dengan judul <strong>{{ $data['title'] }}</strong> yang di jadwalkan pada
            <strong>{{ \Carbon\Carbon::parse($data['date'])->format('l, d F Y') }}</strong> pukul {{ $data['start'] }} -
            {{ $data['end'] }} telah dibatalkan.
        </p>

        <p style="font-size: 14px; color: #333;">
            Kami mohon maaf sebesar-besarnya atas ketidaknyamanan yang ditimbulkan. Informasi mengenai jadwal rapat
            pengganti akan kami sampaikan.
        </p>
    @endif

    <p style="font-size: 14px; color: #333; margin-top: 20px;">
        Hormat kami,<br>
        <strong>Pusat Data dan Teknologi Informasi</strong><br>
        Dinas Kesehatan
    </p>

</x-mail.partials.base-pusdatin-layout>