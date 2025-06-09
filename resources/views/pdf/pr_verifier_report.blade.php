<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>List Data Permohonan Kehumasan</title>
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
    <h1>List Data Permohonan Kehumasan</h1>

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
            @forelse($requests as $index => $request)
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