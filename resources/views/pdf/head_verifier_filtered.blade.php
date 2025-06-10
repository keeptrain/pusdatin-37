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

    <h1>Data Permohonan Sistem Informasi , Permintaan Data dan Kehumasan</h1>

    <div class="meta mt-1">
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
    <table class="mt-1">
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
            @forelse($data as $index => $letter)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $letter->user?->name ?? '—' }}</td>
                <td>{{ $letter->title }}</td>
                <td>{{ $letter->reference_number }}</td>
                <td>{{ $letter->status->label() }}</td>
                <td>{{ $letter->division_label }}</td>
                <!-- <td>{{ $letter->active_revision }}</td>
                <td>{{ $letter->need_review }}</td> -->
                <td>{{ $letter->meeting }}</td>
                <td>{{ $letter->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 12px;">
                    Tidak Ada Permohonan.
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
                <th>No</th>
                <th>Nama Penanggung Jawab</th>
                <th>Tema</th>
                <th>Bulan Publikasi</th>
                <th>Tanggal Spesifik</th>
                <th>Status</th>
                <th>Target</th>
                <th>Link</th>
                <th>Tanggal Pengajuan</th>
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
                <td>{!! $request->getExportLinksAttribute() !!}</td>
                </td>
                <td>{{ $request->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 12px;">
                    Tidak Ada Permohonan
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