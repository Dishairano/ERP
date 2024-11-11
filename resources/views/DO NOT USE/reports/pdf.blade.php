<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report PDF</title>
</head>

<body>
    <h1>Report Type: {{ ucfirst(str_replace('_', ' ', $report->type)) }}</h1>
    <p><strong>Total Income:</strong> ${{ number_format($report->total_income, 2) }}</p>
    <p><strong>Total Expense:</strong> ${{ number_format($report->total_expense, 2) }}</p>
    <p><strong>Net Income:</strong> ${{ number_format($report->net_income, 2) }}</p>
    <p><strong>Assets:</strong> ${{ number_format($report->assets, 2) }}</p>
    <p><strong>Liabilities:</strong> ${{ number_format($report->liabilities, 2) }}</p>
    <p><strong>Equity:</strong> ${{ number_format($report->equity, 2) }}</p>
    <p><strong>Date Range:</strong> {{ $report->start_date }} - {{ $report->end_date }}</p>
</body>

</html>