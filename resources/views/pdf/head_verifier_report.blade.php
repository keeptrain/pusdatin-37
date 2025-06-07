<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Head Verifier Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .section-title {
            font-weight: bold;
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <h1>Head Verifier Report</h1>

    <h2 class="section-title">Letters</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penanggung Jawab</th>
                <th>Judul Permohonan</th>
                <th>Nomor Surat</th>
                <th>Status</th>
                <th>Divisi</th>
                <!-- <th>Active Revision</th>
                <th>Need Review</th> -->
                <th>Meeting</th>
                <th>Tanggal Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($letters as $index => $letter)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $letter->user?->name ?? '—' }}</td>
                <td>{{ $letter->title }}</td>
                <td>{{ $letter->reference_number }}</td>
                <td>{{ $letter->status->label() }}</td>
                <td>{{ $letter->division_label}}</td>
                <!-- <td>{{ $letter->active_revision }}</td>
                <td>{{ $letter->need_review }}</td> -->
                <td>{{ $letter->meeting }}</td>
                <td>{{ $letter->createdAtDMY() }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 12px;">Tidak Ada Permohonan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="section-title">Public Relation Requests</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penanggung Jawab</th>
                <th>Tema</th>
                <th>Bulan Usulan Publikasi</th>
                <th>Tanggal Spesifik Publikasi Media</th>
                <th>Status</th>
                <th>Sasaran</th>
                <th>Link Publikasi</th>
                <th>Tanggal Permohonan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prRequests as $index => $request)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $request->user ? $request->user->name : '—' }}</td>
                <td>{{ $request->theme }}</td>
                <td>{{ $request->month_publication }}</td>
                <td>{{ $request->spesific_date }}</td>
                <td>{{ $request->status->label() }}</td>
                <td>{{ $request->target }}</td>
                <td>
                    @if(is_array($request->links))
                    @foreach($request->links as $link)
                    <a href="{{ $link }}" target="_blank" rel="noopener">{{ $link }}</a><br />
                    @endforeach
                    @else
                    {{ $request->links }}
                    @endif
                </td>
                <td>{{ $request->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 12px;">Tidak Ada Permohonan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>