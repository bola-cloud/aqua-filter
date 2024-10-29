@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="text-center mb-4">تقرير المنتجات المباعة في {{ $soldDate->format('Y-m-d') }}</h1>

    <form action="{{ route('reports.soldProductsReport') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="date" name="sold_date" class="form-control" 
                       value="{{ request('sold_date', \Carbon\Carbon::now()->toDateString()) }}" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">عرض التقرير</button>
            </div>
        </div>
    </form>

    @if($sales->isEmpty())
        <div class="alert alert-info">لا توجد مبيعات في هذا اليوم.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>اسم المنتج</th>
                    <th>الكمية المباعة</th>
                    <th>السعر الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale->product->name }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>{{ number_format($sale->total_price, 2) }} ج.م</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <h5>إجمالي الكمية المباعة: {{ $totalQuantity }}</h5>
            <h5>إجمالي الإيرادات: {{ number_format($totalRevenue, 2) }} ج.م</h5>
        </div>
    @endif
</div>
@endsection
