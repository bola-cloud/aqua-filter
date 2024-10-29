<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة صيانة - {{ $maintenance->client ? $maintenance->client->name : 'لا يوجد عميل' }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header h3 {
            margin: 0;
            font-size: 18px;
        }
        .details {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        .details p {
            margin: 5px 0;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .invoice-table th {
            background-color: #f4f4f4;
        }
        .total {
            font-size: 18px;
            text-align: right;
        }
        .signature {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Company Header -->
        <div class="header">
            <h1>شركة أكوا فلتر</h1>
            <h3>فاتورة صيانة</h3>
        </div>

        <!-- Client and Maintenance Details -->
        <div class="details">
            <p><strong>العميل:</strong> {{ $maintenance->client ? $maintenance->client->name : 'لا يوجد عميل' }}</p>
            <p><strong>رقم الهاتف:</strong> {{ $maintenance->client ? $maintenance->client->phone : 'لا يوجد عميل' }}</p>
            <p><strong>المنتج:</strong> {{ $maintenance->product ? $maintenance->product->name : 'لا يوجد عميل' }}</p>
            <p><strong>تاريخ الصيانة:</strong> {{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('Y-m-d') }}</p>
        </div>

        <!-- Invoice Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>الوصف</th>
                    <th>التكلفة</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $maintenance->description ?? 'صيانة عامة' }}</td>
                    <td>{{ number_format($maintenance->maintenance_cost, 2) }} ج.م</td>
                </tr>
            </tbody>
        </table>

        <!-- Total -->
        <p class="total"><strong>إجمالي التكلفة: {{ number_format($maintenance->maintenance_cost, 2) }} ج.م</strong></p>

        <!-- Signature -->
        <div class="signature">
            <p><strong>التوقيع:</strong> _____________________</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();  // Automatically trigger print on page load
        };
    </script>
</body>
</html>
