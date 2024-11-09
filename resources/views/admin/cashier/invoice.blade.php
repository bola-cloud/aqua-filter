<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عقد بيع أو فاتورة بيع</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            margin: 0;
            direction: rtl;
            color: #333;
            background-color: #f8f9fa;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #555;
        }

        .header .date {
            margin-top: 10px;
            font-size: 14px;
        }

        .header .invoice-code {
            margin-top: 5px;
            font-size: 14px;
            color: #777;
        }

        .details {
            margin-bottom: 20px;
        }

        .details p {
            margin: 5px 0;
            font-size: 14px;
        }

        .details .info-label {
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th, .table td {
            border: 1px solid #000;
            text-align: center;
            padding: 10px;
        }

        .table th {
            background-color: #f8f8f8;
            font-weight: bold;
        }

        .totals {
            margin-top: 20px;
            width: 100%;
        }

        .totals th, .totals td {
            text-align: right;
            padding: 10px;
        }

        .totals th, .totals td {
            border-top: 1px solid #000;
        }

        .footer {
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 20px;
            font-size: 14px;
        }

        .footer p {
            margin: 0;
            color: #777;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .signature p {
            display: inline-block;
            margin: 0;
            padding-top: 30px;
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
        }

        .no-print {
            margin-top: 20px;
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-primary:hover, .btn-secondary:hover {
            opacity: 0.9;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="invoice-POS" class="receipt">
            <div id="top" class="header">
                <h1>شركة {{$invoice->company}}</h1>
                <h2>فاتورة بيع</h2>
                <div class="date">التاريخ: {{ \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d') }}</div>
                <div class="invoice-code">رقم الفاتورة: INV-{{$invoice->invoice_code}}</div>
            </div>

            <div id="mid" class="details">
                <p><span class="info-label">معلومات العميل:</span></p>
                <p>
                    <span class="info-label">اسم العميل:</span> {{$invoice->buyer_name}}<br>
                    <span class="info-label">رقم العميل:</span> {{$invoice->buyer_phone}}<br>
                    <span class="info-label">تاريخ الشراء:</span> {{ \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d') }}<br>
                </p>
            </div>

            <div id="bot">
                <table class="table">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>الكمية</th>
                            <th>الإجمالي الفرعي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->sales as $sale)
                        <tr class="service">
                            <td>{{ $sale->product->name }}</td>
                            <td>{{ $sale->quantity }}</td>
                            <td>{{ number_format($sale->product->selling_price * $sale->quantity, 2) }} ج.م</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="totals">
                    <tr>
                        <td></td>
                        <th>الإجمالي الفرعي:</th>
                        <td><strong>{{ number_format($invoice->subtotal, 2) }} ج.م</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <th>الخصم:</th>
                        <td><strong>{{ number_format($invoice->discount, 2) }} ج.م</strong></td>
                    </tr>  
                    <tr>
                        <td></td>
                        <th>الإجمالي:</th>
                        <td><strong>{{ number_format($invoice->total_amount, 2) }} ج.م</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <th>المبلغ المدفوع:</th>
                        <td><strong>{{ number_format($invoice->paid_amount, 2) }} ج.م</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <th>المتبقي:</th>
                        <td><strong>{{ number_format($invoice->change, 2) }} ج.م</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <th>الدفعة الشهرية:</th>
                        <td><strong>{{ number_format($invoice->installment_amount, 2) }} ج.م</strong></td>
                    </tr>
                </table>
            </div>

            <div class="signature">
                <p>توقيع العميل</p>
            </div>

            <div class="footer">
                <p>شكراً لتعاملكم معنا!</p>
            </div>
        </div>

        <div class="row no-print text-center">
            <button onclick="window.print()" class="btn btn-primary">طباعة الفاتورة</button>
            <a href="{{ route('cashier.viewCart') }}" class="btn btn-secondary">عودة إلى الكاشير</a>
        </div>
    </div>
</body>
</html>
