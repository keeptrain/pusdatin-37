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
                <th>User Name</th>
                <th>Title</th>
                <th>Reference Number</th>
                <th>Status</th>
                <th>Current Division</th>
                <th>Active Revision</th>
                <th>Need Review</th>
                <th>Meeting</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($letters as $letter)
            <tr>
                <td>{{ $letter->user ? $letter->user->name : '—' }}</td>
                <td>{{ $letter->title }}</td>
                <td>{{ $letter->reference_number }}</td>
                <td>{{ $letter->status->label() }}</td>
                <td>{{ $letter->current_division }}</td>
                <td>{{ $letter->active_revision }}</td>
                <td>{{ $letter->need_review }}</td>
                <td>{{ $letter->meeting }}</td>
                <td>{{ $letter->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section-title">Public Relation Requests</h2>
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
            @foreach($prRequests as $request)
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