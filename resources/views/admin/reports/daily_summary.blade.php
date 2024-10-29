@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>تقرير الأقساط اليومية</h1>

    <!-- Date Selection -->
    <form action="{{ route('sales.installments.dailySummary') }}" method="GET">
        <div class="form-group">
            <label for="date">تاريخ التحصيل:</label>
            <input type="date" id="date" name="date" value="{{ $date }}" class="form-control" onchange="this.form.submit()">
        </div>
    </form>

    <h3>إجمالي الأقساط المحصلة: {{ number_format($totalCollected, 2) }} ج.م</h3>

    <!-- Installments Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>رقم الفاتورة</th>
                <th>المبلغ المدفوع</th>
                <th>تاريخ الدفع</th>
                <th>المسؤول عن التحصيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($installments as $installment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $installment->invoice->invoice_code }}</td>
                    <td>{{ number_format($installment->amount_paid, 2) }} ج.م</td>
                    <td>{{ $installment->date_paid }}</td>
                    <td>{{ $installment->agent }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
