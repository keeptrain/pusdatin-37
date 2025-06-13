<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Permohonan Data</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Permohonan Data</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penanggung Jawab</th>
                <th>Judul Permohonan</th>
                <th>Nomor Surat</th>
                <th>Status</th>
                <!-- <th>Divisi</th> -->
                <!-- <th>Active Revision</th>
                <th>Need Review</th> -->
                <th>Link Meeting</th>
                <th>Tanggal Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($letters as $index => $letter)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $letter->user?->name ?: '—' }}</td>
                <td>{{ $letter->title }}</td>
                <td>{{ $letter->reference_number }}</td>
                <td>{{ $letter->status->label() }}</td>
                <!-- <td>{{ $letter->current_division }}</td>
                <td>{{ $letter->active_revision }}</td>
                <td>{{ $letter->need_review }}</td> -->
                <td>{!! nl2br(e($letter->formatted_meetings)) !!}</td>
                <td>{{ $letter->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 12px;">Tidak Ada Permohonan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>