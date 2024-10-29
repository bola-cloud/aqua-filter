<!DOCTYPE html>
<html lang="ar">
<head>
    <title>توليد باركود الفاتورة</title>
    <style>
        :root {
            --width: 38mm;
            --height: 25mm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
        }
        .barcode_content {
            width: var(--width);
            height: var(--height);
            margin: 10px;
            background-color: white;
            text-align: center;
            padding: 5px;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .barcode_content p {
            margin: 3px 0;
        }
        img {
            width: calc(var(--width) - 6mm);
            height: auto;
        }
        .code_price {
            font-size: 14px;
            font-weight: bold;
        }

        /* Print settings */
        @media print {
            @page {
                margin: 0 !important;
                size: var(--width) var(--height);
            }
            body {
                margin: 0;
                padding: 0;
                background-color: white;
            }
            .barcode_content {
                border: none;
                box-shadow: none;
                margin: 0;
            }
            button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div id="BarCodeArea">
        <div class="barcode_content">
            <p>رقم الفاتورة: {{ $invoice->invoice_code }}</p>
            <p class="code_price">المبلغ الإجمالي: {{ number_format($invoice->total_amount, 2) }} ج.م</p>
            <img src="data:image/svg+xml;base64,{{ base64_encode($barcodeImage) }}" alt="barcode" />
        </div>
    </div>

    <button onclick="window.print()" style="margin-top: 15px; padding: 10px 20px; font-size: 16px; cursor: pointer;">طباعة الباركود</button>
</body>
</html>
