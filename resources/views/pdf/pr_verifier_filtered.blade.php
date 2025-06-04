<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>PR Verifier Report (Filtered)</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 0 20px;
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .meta {
            margin-bottom: 20px;
            font-size: 11px;
        }

        .meta span {
            display: inline-block;
            margin-right: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>PR Verifier Report</h1>
    <h2>Filtered</h2>

    <div class="meta">
        <span><strong>Start Date:</strong> {{ $start ?? '—' }}</span>
        <span><strong>End Date:</strong> {{ $end ?? '—' }}</span>
        <span><strong>Status:</strong>
            @if(!$status || $status === 'all')
            All
            @else
            @switch($status)
            @case('antrian_promkes') Antrian Promkes @break
            @case('kurasi_promkes') Kurasi Promkes @break
            @case('antrian_pusdatin') Antrian Pusdatin @break
            @case('proses_pusdatin') Proses Pusdatin @break
            @case('completed') Completed @break
            @default {{ $status }}
            @endswitch
            @endif
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
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
            @forelse($requests as $index => $request)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $request->user?->name ?? '—' }}</td>
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
                <td colspan="9" style="text-align: center; padding: 12px;">No records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>