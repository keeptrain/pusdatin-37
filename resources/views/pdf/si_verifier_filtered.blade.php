<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Si Verifier Report (Filtered)</title>
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

    <h1>Data Permohonan Sistem Informasi (Filter)</h1>

    <div class="meta">
        <span><strong>Start Date:</strong> {{ $start ?? '—' }}</span>
        <span><strong>End Date:</strong> {{ $end ?? '—' }}</span>
        <span><strong>Status:</strong>
            @if(!$status || $status === 'all')
            All
            @else
            @switch($status)
            @case('disposition') Disposition @break
            @case('process') Process @break
            @case('replied') Replied @break
            @case('approved_kasatpel') Approved Kasatpel @break
            @case('replied_kapusdatin') Replied Kapusdatin @break
            @case('approved_kapusdatin') Approved Kapusdatin @break
            @case('rejected') Rejected @break
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
            @forelse($letters as $index => $letter)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $letter->user?->name ?: '—' }}</td>
                <td>{{ $letter->title }}</td>
                <td>{{ $letter->reference_number }}</td>
                <td>{{ $letter->status->label() }}</td>
                <td>{{ $letter->current_division }}</td>
                <td>{{ $letter->active_revision }}</td>
                <td>{{ $letter->need_review }}</td>
                <td>{{ $letter->meeting }}</td>
                <td>{{ $letter->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 12px;">No records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>