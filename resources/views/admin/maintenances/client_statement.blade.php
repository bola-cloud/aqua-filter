<!DOCTYPE html>
<html lang="ar">
<head>
    <title>كشف صيانة للعميل</title>
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
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <h1>كشف صيانة للعميل: {{ $client->name }}</h1>
    <p><strong>الهاتف:</strong> {{ $client->phone }}</p>
    <p><strong>إجمالي تكلفة الصيانة:</strong> {{ number_format($totalMaintenanceCost, 2) }} جنيه</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>المنتج</th>
                <th>تكلفة الصيانة</th>
                <th>تاريخ الصيانة</th>
                <th>الوصف</th>
            </tr>
        </thead>
        <tbody>
            @foreach($maintenances as $maintenance)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $maintenance->product ? $maintenance->product->name : 'غير متوفر' }}</td>
                    <td>{{ number_format($maintenance->maintenance_cost, 2) }} جنيه</td>
                    <td>{{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('Y-m-d') }}</td>
                    <td>{{ $maintenance->description ?? 'لا يوجد' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <button class="print-button" onclick="window.print()">طباعة</button>
    <a href="{{ route('clients.index') }}" class="print-button">رجوع إلى قائمة العملاء</a>
</body>
</html>
