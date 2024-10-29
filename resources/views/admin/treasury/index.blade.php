@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">الخزينة ليوم واحد</h1>

    <!-- Form to Select Date -->
    <form action="{{ route('treasury') }}" method="GET" class="mb-4">
        <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label">اختر التاريخ</label>
            <div class="col-sm-4">
                <input type="date" id="date" name="date" class="form-control" value="{{ $date }}" required>
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary">عرض الخزينة</button>
            </div>
        </div>
    </form>

    <!-- Display Treasury Summary -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">الخزينة ليوم: {{ $date }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h4>إجمالي الأقساط من المبيعات: {{ number_format($salesInstallments, 2) }} ج.م</h4>
                </div>
                <div class="col-md-4">
                    <h4>إجمالي الأقساط من المشتريات: {{ number_format($purchaseInstallments, 2) }} ج.م</h4>
                </div>
                <div class="col-md-4">
                    <h4>إجمالي فواتير الصيانة: {{ number_format($maintenanceInvoices, 2) }} ج.م</h4>
                </div>
            </div>

            <hr>

            <h3 class="text-center">
                الإجمالي (الصيانة + المبيعات - المشتريات): 
                <strong>{{ number_format($total, 2) }} ج.م</strong>
            </h3>

            @if ($total > 0)
                <p class="text-center text-success">هناك فائض اليوم.</p>
            @elseif ($total < 0)
                <p class="text-center text-danger">هناك عجز اليوم.</p>
            @else
                <p class="text-center text-warning">لا يوجد فرق بين المبيعات والمشتريات والصيانة اليوم.</p>
            @endif
        </div>
    </div>
</div>
@endsection
