@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mt-4">البحث عن الفاتورة بواسطة الباركود</h1>

    <!-- Display error message if the barcode is invalid -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form to input barcode -->
    <form action="{{ route('invoices.redirectByBarcode') }}" method="POST" class="mt-4">
        @csrf
        <div class="form-group">
            <label for="barcode">الباركود</label>
            <input type="text" class="form-control" id="barcode" name="barcode" required placeholder="أدخل الباركود هنا">
        </div>
        <button type="submit" class="btn btn-primary mt-3">ابحث عن الفاتورة</button>
    </form>
</div>
@endsection
