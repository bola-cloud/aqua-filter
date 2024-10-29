<!DOCTYPE html>
<html lang="ar">
<head>
    <title>طباعة جدول العملاء</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
        }
        h1, p {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>جدول العملاء</h1>
    <p>إجمالي عدد العملاء: {{ $clients->count() }}</p>
    <p>إجمالي عدد الفواتير: {{ $clients->sum('invoices_count') }}</p>
    <p>إجمالي مبلغ الفواتير: {{ number_format($totalInvoicesAmount, 2) }} ج.م</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الهاتف</th>
                <th>العنوان</th>
                <th>الكود</th>
                <th>عدد الفواتير</th>
                <th>إجمالي المبلغ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->address }}</td>
                    <td>{{ $client->code }}</td>
                    <td>{{ $client->invoices->count() }}</td>
                    <td>{{ number_format($client->invoices->sum('total_amount'), 2) }} ج.م</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>
