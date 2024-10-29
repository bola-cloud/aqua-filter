<!DOCTYPE html>
<html lang="ar">
<head>
    <title>كشف حساب العميل</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            text-align: center;
        }
        h1, p {
            margin: 0;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #333;
            padding: 8px;
        }
        th, td {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>كشف حساب العميل</h1>
    <p><strong>اسم العميل:</strong> {{ $client->name }}</p>
    <p><strong>الهاتف:</strong> {{ $client->phone }}</p>
    <p><strong>العنوان:</strong> {{ $client->address }}</p>
    <p><strong>إجمالي الفواتير:</strong> {{ $totalInvoices }} جنيه</p>
    <p><strong>إجمالي المدفوعات:</strong> {{ $totalPaidAmount }} جنيه</p>
    <p><strong>إجمالي الباقي:</strong> {{ $totalChange }} جنيه</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>رقم الفاتورة</th>
                <th>إجمالي الفاتورة</th>
                <th>المدفوع</th>
                <th>الباقي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($client->invoices as $invoice)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $invoice->invoice_code }}</td>
                    <td>{{ $invoice->total_amount }} جنيه</td>
                    <td>{{ $invoice->paid_amount }} جنيه</td>
                    <td>{{ $invoice->change }} جنيه</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>
