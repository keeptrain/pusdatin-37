<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>PR Verifier Report</title>
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
    <h1>PR Verifier Report</h1>

    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Theme</th>
                <th>Month Publication</th>
                <th>Specific Date</th>
                <th>Status</th>
                <th>Target</th>
                <th>Links</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $request)
            <tr>
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
            @endforeach
        </tbody>
    </table>
</body>

</html>