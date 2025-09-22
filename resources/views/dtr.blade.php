<!DOCTYPE html>
<html>
<head>
    <title>{{ $intern->first_name }} {{ $intern->last_name }} - DTR</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            background-color: #fff;
            color: #333;
        }

        h2, h4 {
            text-align: center;
            margin: 0;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #3490dc;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f4f8fc;
        }

        .button {
            margin-top: 20px;
            display: inline-block;
            width: 140px;
            padding: 10px;
            text-align: center;
            background-color: #38c172;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .button:hover {
            background-color: #2f9e63;
        }

        .back-button {
            background-color: #6c757d;
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        .button-group {
            text-align: center;
            margin-top: 30px;
        }

        @media print {
            .button-group {
                display: none;
            }
        }
    </style>
</head>
<body>

    <h2>Daily Time Record</h2>
    <h4>{{ $intern->first_name }} {{ $intern->last_name }}</h4>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($log->date)->format('F d, Y') }}</td>
                    <td>{{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->timezone('Asia/Manila')->format('h:i:s A') : '—' }}</td>

                   <td>{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->timezone('Asia/Manila')->format('h:i:s A') : '—' }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="3">No attendance records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="button-group">
        <a href="#" class="button" onclick="window.print()">🖨️ Print</a>
        <a href="{{ url()->previous() }}" class="button back-button">⬅️ Back</a>
    </div>

</body>
</html>
