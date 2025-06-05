<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Head Verifier Report (Filtered)</title>
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

    <h1>Head Verifier Report</h1>
    <h2>Filtered</h2>

    <div class="meta">
        <span><strong>Start Date:</strong> {{ $start_date ?? '—' }}</span>
        <span><strong>End Date:</strong> {{ $end_date ?? '—' }}</span>
        <span><strong>Status:</strong>
            @if(!$status || $status === 'all')
            All
            @else
            @switch($status)
            {{-- Letter statuses --}}
            @case('disposition') Disposition @break
            @case('process') Process @break
            @case('replied') Replied @break
            @case('approved_kasatpel') Approved Kasatpel @break
            @case('replied_kapusdatin') Replied Kapusdatin @break
            @case('approved_kapusdatin') Approved Kapusdatin @break
            @case('rejected') Rejected @break

            {{-- PR statuses --}}
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

    @php
    // Determine if data items are Letter or PublicRelationRequest
    $firstItem = $data->first();
    @endphp

    @if($firstItem instanceof \App\Models\Letters\Letter)
    {{-- Table for Letters --}}
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
            @forelse($data as $index => $letter)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $letter->user?->name ?? '—' }}</td>
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
                <td colspan="10" style="text-align: center; padding: 12px;">
                    No Letter records found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @elseif($firstItem instanceof \App\Models\PublicRelationRequest)
    {{-- Table for Public Relation Requests --}}
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
            @forelse($data as $index => $request)
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
                <td colspan="9" style="text-align: center; padding: 12px;">
                    No Public Relation records found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @else
    <p style="text-align: center; margin-top: 20px;">No data available.</p>
    @endif

</body>

</html>