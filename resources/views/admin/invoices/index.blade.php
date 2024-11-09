@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="text-center mb-3">جميع الفواتير</h1>

    <!-- Search Form -->
    <form id="search-form" class="mb-4" action="{{ route('invoices.search') }}" method="GET">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control" id="search-query" name="query" placeholder="بحث عن طريق اسم العميل أو الهاتف" value="{{ request('query') }}">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" id="date_from" name="date_from" placeholder="من تاريخ" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" id="date_to" name="date_to" placeholder="إلى تاريخ" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3">
                <!-- Village Select Dropdown -->
                <select id="village" name="village" class="form-control select2">
                    <option value="">اختر القرية</option>
                    @foreach($villages as $village)
                        <option value="{{ $village->name }}" {{ request('village') == $village->name ? 'selected' : '' }}>
                            {{ $village->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Company Select Dropdown -->
            <div class="col-md-3">
                <select id="company" name="company" class="form-control">
                    <option value="">اختر الشركة</option>
                    <option value="اكوا فلتر" {{ request('company') == 'اكوا فلتر' ? 'selected' : '' }}>اكوا فلتر</option>
                    <option value="اكوا ستار" {{ request('company') == 'اكوا ستار' ? 'selected' : '' }}>اكوا ستار</option>
                </select>
            </div>

            <div class="col-md-2 mt-2">
                <!-- Filter for installment exceeding one month -->
                <input type="checkbox" id="installment_exceeded" name="installment_exceeded" {{ request('installment_exceeded') ? 'checked' : '' }}>
                <label for="installment_exceeded">تصفية الفواتير التي تجاوزت الشهر</label>
            </div>
            <div class="col-md-2 mt-3">
                <button type="submit" class="btn btn-primary">بحث</button>
            </div>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Invoices Table -->
    <table class="table table-bordered" id="invoice-table">
        <thead>
            <tr>
                <th>كود الفاتورة</th>
                <th>اسم المشتري</th>
                <th>اسم البائع</th>
                <th>تاريخ الإنشاء</th>
                <th>الأقساط</th>
                <th>الشركة</th>
                <th>إجراءات</th>
                <th>حذف</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr 
                    @if($invoice->isFullyPaid())
                        style="background-color: #d4edda;"  {{-- Success alert for fully paid invoices --}}
                    @elseif($invoice->hasInstallmentExceededOneMonth())
                        style="background-color: #f8d7da;"  {{-- Danger alert for overdue installments --}}
                    @endif
                >
                    <td>{{ $invoice->invoice_code }}</td>
                    <td>{{ $invoice->client ? $invoice->client->name : 'لا يوجد عميل' }}</td>
                    <td>{{ $invoice->user ? $invoice->user->name : 'لا يوجد مستخدم' }}</td>
                    <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                    <td>
                        @if($invoice->total_amount > $invoice->paid_amount)
                            @if(auth()->user()->hasPermission('عرض الاقساط'))
                                <a href="{{ route('sales.installments.index', $invoice->id) }}" class="btn btn-info btn-sm">عرض الأقساط</a>
                            @endif
                        @endif
                    </td>
                    <td> {{$invoice->company}} </td>
                    <td>
                        <a href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}" class="btn btn-secondary btn-sm">عرض التفاصيل</a>
                        <a class="btn btn-primary btn-sm" href="{{ route('cashier.printInvoice', $invoice->id) }}">طباعة الفاتورة</a>
                        <a href="{{ route('invoices.generateBarcode', $invoice->id) }}" class="btn btn-secondary btn-sm">توليد الباركود</a>
                    </td>
                    <td>
                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف الفاتورة؟ سيتم إرجاع الكميات إلى المخزون.')">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        
    </table>

    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            {{ $invoices->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- Select2 css -->
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="{{asset('js/select2.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('#village').select2({
                placeholder: "اختر القرية",
                allowClear: true
            });
        });
    </script>
@endpush
