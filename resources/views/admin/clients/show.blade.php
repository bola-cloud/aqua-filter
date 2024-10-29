@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>عرض تفاصيل العميل</h1>

    <p><strong>الاسم:</strong> {{ $client->name }}</p>
    <p><strong>الهاتف:</strong> {{ $client->phone }}</p>

    <!-- Display Success Message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Existing Client Details and Invoice Table... -->
    <h3>فواتير العميل</h3>
    <p><strong>إجمالي الفواتير:</strong> {{ $totalInvoices }} جنيه</p>
    <p><strong>إجمالي المدفوعات:</strong> {{ $totalPaidAmount }} جنيه</p>
    <p><strong>إجمالي الباقي:</strong> {{ $totalChange }} جنيه</p>
    <a href="{{ route('clients.printStatement', $client->id) }}" target="_blank" class="btn btn-primary mt-3 mb-3">طباعة كشف الحساب</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>رقم الفاتورة</th>
                <th>إجمالي الفاتورة</th>
                <th>المدفوع</th>
                <th>الباقي</th>
                <th>الإجراءات</th>
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
                    <td>
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info">عرض التفاصيل</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

        <!-- Form to Update Flags -->
        <h3 class="mt-5">تحديث حالة العميل</h3>
        <form action="{{ route('clients.updateFlags', $client->id) }}" method="POST" class="mb-4">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="can_have_invoice">مسموح بإنشاء فواتير:</label>
                    <select name="can_have_invoice" id="can_have_invoice" class="form-control">
                        <option value="1" {{ $client->can_have_invoice ? 'selected' : '' }}>نعم</option>
                        <option value="0" {{ !$client->can_have_invoice ? 'selected' : '' }}>لا</option>
                    </select>
                </div>
        
                <div class="form-group col-md-6">
                    <label for="can_have_maintenance">مسموح بصيانة:</label>
                    <select name="can_have_maintenance" id="can_have_maintenance" class="form-control">
                        <option value="1" {{ $client->can_have_maintenance ? 'selected' : '' }}>نعم</option>
                        <option value="0" {{ !$client->can_have_maintenance ? 'selected' : '' }}>لا</option>
                    </select>
                </div>
            </div>
    
    
            <button type="submit" class="btn btn-primary">تحديث الحالة</button>
        </form>

    <a href="{{ route('clients.index') }}" class="btn btn-secondary">عودة إلى قائمة العملاء</a>
</div>
@endsection
