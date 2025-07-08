<x-mail.partials.base-pusdatin-layout title="Rapat Pembahasan {{ $data['topic'] }}">
    <p style="font-size: 14px; color: #333;">
        Kepada Yth.<br>
        <strong>{{ $data['name']}}</strong><br>
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
            @if ($data['place']['type'] == 'location')
                Lokasi: {{ $data['place']['value'] }}
            @elseif ($data['place']['type'] == 'link')
                Link: <a href="{{ $data['place']['value'] }}" style="color: #0000FF;">{{ $data['place']['value'] }}</a>
                <br>
                Password: <span>{{ $data['place']['password'] }}</span>
            @endif
        </strong>
    @elseif ($mode === 'update')
        <p style="font-size: 14px; color: #333;">
            Kami informasikan bahwa rapat pembahasan
            <strong>{{ $data['topic'] }}</strong> berhubungan dengan permohonan layanan Sistem Informasi & Data yang di
            ajukan dengan judul <strong>{{ $data['title'] }}</strong> yang di jadwalkan pada
            <strong>{{ \Carbon\Carbon::parse($data['date'])->format('l, d F Y') }}</strong> pukul
            <strong>{{ $data['start'] }} -
                {{ $data['end'] }}</strong> telah diubah menjadi:
        </p>
        <strong style="font-size: 15px; color: #333;">
            Hari, Tanggal: {{ \Carbon\Carbon::parse($data['date'])->format('l, d F Y') }}
            <br>
            Waktu: {{ $data['start'] }} - {{ $data['end'] }}
            <br>
            @if ($data['place']['type'] == 'location')
                Lokasi: {{ $data['place']['value'] }}
            @elseif ($data['place']['type'] == 'link')
                Link: <a href="{{ $data['place']['value'] }}" style="color: #0000FF;">{{ $data['place']['value'] }}</a>
                <br>
                Password: <span>{{ $data['place']['password'] }}</span>
            @endif
        </strong>
    @elseif ($mode === 'delete')
        <p style="font-size: 14px; color: #333;">
            Kami informasikan bahwa rapat pembahasan
            <strong>{{ $data['topic'] }}</strong> berhubungan dengan permohonan layanan Sistem Informasi & Data yang di
            ajukan dengan judul <strong>{{ $data['title'] }}</strong> yang di jadwalkan pada
            <strong>{{ \Carbon\Carbon::parse($data['date'])->format('l, d F Y') }}</strong> pukul
            <strong>{{ $data['start'] }} -
                {{ $data['end'] }}</strong> telah dibatalkan.
        </p>

        <p style="font-size: 14px; color: #333;">
            Kami mohon maaf sebesar-besarnya atas ketidaknyamanan yang ditimbulkan. Informasi mengenai jadwal rapat
            pengganti akan kami sampaikan kembali.
        </p>
    @endif

    <p style="font-size: 14px; color: #333; margin-top: 20px;">
        Hormat kami,<br>
        <strong>Pusat Data dan Teknologi Informasi</strong><br>
        Dinas Kesehatan
    </p>
</x-mail.partials.base-pusdatin-layout>