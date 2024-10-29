<!DOCTYPE html>
<html lang="ar">
<head>
    <title>طباعة جدول الصيانة</title>
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
    <h1>سجل الصيانة</h1>
    <p>إجمالي عدد الفواتير: {{ $maintenances->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>المنتج</th>
                <th>تكلفة الصيانة</th>
                <th>تاريخ الصيانة</th>
                <th>الوصف</th>
            </tr>
        </thead>
        <tbody>
            @foreach($maintenances as $maintenance)
                <tr>
                    <td>{{ $maintenance->id }}</td>
                    <td>{{ $maintenance->client ? $maintenance->client->name : 'لا يوجد عميل' }}</td>
                    <td>{{ $maintenance->product ? $maintenance->product->name : 'لا يوجد منتج' }}</td>
                    <td>{{ number_format($maintenance->maintenance_cost, 2) }} ج.م</td>
                    <td>{{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('Y-m-d') }}</td>
                    <td>{{ $maintenance->description ?? 'لا يوجد' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>
