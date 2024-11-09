@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1>قائمة المنتجات</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('products.create') }}" class="btn btn-primary">إضافة منتج جديد</a>
        <!-- <button id="print-selected-barcodes" class="btn btn-secondary">طباعة الباركودات المختارة</button> -->
    </div>

    @php
        $user = auth()->user();
        $permissions = $user->roles()->with('permissions')->get()->pluck('permissions.*.name')->flatten()->unique();
    @endphp
    
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

    <div class="row mb-4">
        <div class="col-md-5">
            <div class="form-group">
                <label for="search-barcode">البحث عن المنتج بواسطة الباركود أو الاسم</label>
                <input type="text" class="form-control" id="search-barcode" name="search" value="{{ request('search') }}" placeholder="أدخل الباركود أو اسم المنتج...">
            </div>
        </div>

        <div class="col-md-5">
            <div class="form-group">
                <label for="category-filter">فلترة حسب الفئة</label>
                <select class="form-control" id="category-filter" name="category_id">
                    <option value="">كل الفئات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-1 d-flex align-items-center mt-1">
            <button type="button" id="search-button" class="btn btn-primary btn-block">بحث</button>
        </div>
    </div>

    <form id="filter-form" method="GET" action="{{ route('products.index') }}">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center" id="products-table">
            <thead class="thead-dark">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>ID</th>
                    <th>اسم المنتج</th>
                    <th>صورة المنتج</th>
                    <th>الفئة</th>
                    @if($user->hasRole('admin'))
                        <th>سعر التكلفة</th>
                    @endif
                    <th>سعر البيع</th>
                    <th>الكمية</th>
                    <th>الباركود</th>
                    <th>الباركود</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <input type="radio" name="selected_product" value="{{ $product->id }}" data-barcode="{{ asset('storage/' . $product->barcode_path) }}" data-name="{{ $product->name }}">
                        </td>                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="50">
                            @else
                                لا توجد صورة
                            @endif
                        </td>                    
                        <td>{{ $product->category ? $product->category->name : 'لا يوجد فئة' }}</td>
                        @if($user->hasRole('admin'))
                            <td>{{ $product->cost_price }}</td>
                        @endif
                        <td>{{ $product->selling_price }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->color }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $product->barcode_path) }}" 
                            alt="barcode" />
                            <br>{{ $product->barcode }}
                        </td>
                        @php
                            $user = auth()->user();
                            $permissions = $user->roles()->with('permissions')->get()->pluck('permissions.*.name')->flatten()->unique();
                        @endphp
                        <td>
                            <div class="d-flex justify-content-between">
                                @if(auth()->user()->hasPermission('تعديل المنتجات'))
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                @endif
                                @if(auth()->user()->hasPermission('حذف المنتجات'))
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                    </form>
                                @endif
                                <!-- Add a button to redirect to the print barcode blade -->
                                <a href="{{ route('products.printBarcodes', $product->id) }}" class="btn btn-secondary btn-sm">طباعة الباركود</a>
                            </div>
                        </td>            
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>

<script src="{{ asset('assets/js/jquery.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#search-button').on('click', function() {
            $('#filter-form').submit();
        });

        $('#search-barcode').on('input', function() {
            $('input[name="search"]').val($(this).val());
        });

        $('#category-filter').on('change', function() {
            $('input[name="category_id"]').val($(this).val());
        });


    });
</script>

@endsection

@push('css')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@push('js')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush