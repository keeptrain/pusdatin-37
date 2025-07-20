<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10pt;
            margin: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 300px;
            height: auto;
        }

        .header-text {
            text-align: center;
            flex-grow: 1;
        }

        .section-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .main-table {
            margin-top: 20px;
        }

        .meeting-table th {
            background-color: #e6e6e6;
        }

        .request-title {
            font-weight: bold;
            margin: 15px 0 5px 0;
            font-size: 11pt;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <img class="logo" src="{{ storage_path('app/assets/pusdatin-logo.jpg') }}" alt="logo">
        <div class="header-text">
            <h2 class="section-title">{{ $title }}</h2>
            <div>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
        <div style="width: 150px;"></div>
    </div>

    <!-- Tabel Utama -->
    <table class="main-table">
        <thead>
            <tr>
                <th>No</th>
                @foreach ($headings as $heading)
                    @if(!in_array($heading, ['Meeting', 'Total meeting']))
                        <th>{{ $heading }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($collection as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user->name ?? '—' }}</td>
                    {{-- <td>{{ $item->user->section ?? '—' }}</td> --}}
                    {{-- <td>{{ $item->user->email ?? '—' }}</td> --}}
                    <td>{{ $item->user->contact ?? '—' }}</td>
                    <td>{{ $item->request->createdAtWithTime() ?? '—' }}</td>
                    <td>{{ $item->request->title ?? '—' }}</td>
                    <td>{{ $item->request->reference_number ?? '—' }}</td>
                    <td>{{ optional($item->request->status)->label() ?? '—' }}</td>

                    @if(in_array('Kasatpel yang menangani', $headings))
                        <td>{{ $item->request->division_label ?? '—' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Daftar Meeting untuk Setiap Permohonan -->
    @foreach ($collection as $item)
        @if($item->meetings->isNotEmpty())
            <div class="request-title">
                Meeting untuk Permohonan: {{ $item->request->title }}
                (No: {{ $item->request->reference_number }})
            </div>
            <table class="meeting-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Waktu</th>
                        <th width="25%">Topik</th>
                        <th width="20%">Tempat/Link</th>
                        <th width="20%">Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->meetings as $meetingIndex => $meeting)
                        <tr>
                            <td>{{ $loop->iteration }}</td> <!-- Reset numbering for each request -->
                            <td>{{ $meeting->start_at ? \Carbon\Carbon::parse($meeting->start_at)->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($meeting->start_at && $meeting->end_at)
                                    {{ \Carbon\Carbon::parse($meeting->start_at)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($meeting->end_at)->format('H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $meeting->topic ?? '-' }}</td>
                            <td>
                                @if(isset($meeting->place['type']) && $meeting->place['type'] === 'online')
                                    <span style="color: blue;">Link Online</span>
                                @else
                                    {{ $meeting->place['value'] ?? '-' }}
                                @endif
                            </td>
                            <td>{{ $meeting->result ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</body>

</html>