<!DOCTYPE html>
<html>

<head>
    <title>Budget Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h2>Budget Report for {{ $budget->category_name }}</h2>
    <p>Allocated Amount: ${{ number_format($budget->allocated, 2) }}</p>
    <p>Spent Amount: ${{ number_format($budget->spent, 2) }}</p>
    <p>Remaining Amount: ${{ number_format($budget->remaining, 2) }}</p>
    <p>Spent Percentage: {{ number_format($budget->spentPercentage, 2) }}%</p>

    <h3>Expenses</h3>
    <table>
        <thead>
            <tr>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($budget->expenses as $expense)
                <tr>
                    <td>${{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>{{ $expense->date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
