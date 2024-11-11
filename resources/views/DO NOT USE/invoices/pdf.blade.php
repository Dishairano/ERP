<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
    }

    .invoice-header {
        text-align: center;
    }

    .invoice-details {
        width: 100%;
        margin-bottom: 20px;
    }

    .invoice-details td {
        padding: 5px;
    }

    .invoice-items {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-items th,
    .invoice-items td {
        border: 1px solid #000;
        padding: 10px;
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="invoice-header">
        <h1>Invoice #{{ $invoice->invoice_number }}</h1>
        <p>Date: {{ $invoice->created_at->format('Y-m-d') }}</p>
    </div>

    <table class="invoice-details">
        <tr>
            <td><strong>Client Name:</strong> {{ $invoice->client_name }}</td>
            <td><strong>Status:</strong> {{ ucfirst($invoice->status) }}</td>
        </tr>
        <tr>
            <td><strong>Amount:</strong> ${{ number_format($invoice->amount, 2) }}</td>
            <td><strong>Date:</strong> {{ $invoice->created_at->format('Y-m-d') }}</td>
        </tr>
    </table>

    <!-- Add more details if needed -->
    <p>Thank you for your business!</p>
</body>

</html>