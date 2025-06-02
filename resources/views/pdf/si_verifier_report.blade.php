<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Si Verifier Report</title>
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
    <h1>Si Verifier Report</h1>

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
</body>

</html>