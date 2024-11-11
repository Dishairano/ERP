<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Budget Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Budget Report</h1>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Planned</th>
                <th>Actual</th>
                <th>Variance</th>
                <th>Spent %</th>
                <th>Department</th>
                <th>Project</th>
                <th>Cost Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['category'] }}</td>
                    <td>{{ number_format($item['planned'], 2) }}</td>
                    <td>{{ number_format($item['actual'], 2) }}</td>
                    <td>{{ number_format($item['variance'], 2) }}</td>
                    <td>{{ number_format($item['spent_percentage'], 1) }}%</td>
                    <td>{{ $item['department'] ?? 'N/A' }}</td>
                    <td>{{ $item['project'] ?? 'N/A' }}</td>
                    <td>{{ $item['cost_category'] ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report is automatically generated. Please contact the finance department for any questions.</p>
    </div>
</body>

</html>
