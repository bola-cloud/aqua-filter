@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Installment Page Barcode for Invoice {{ $invoice->invoice_code }}</h1>
    <div>
        {!! $barcode !!}
    </div>
    <p>Scan this barcode to be redirected to the installment page for this invoice.</p>
</div>
@endsection
