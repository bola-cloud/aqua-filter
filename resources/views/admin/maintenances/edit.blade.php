@extends('layouts.admin')

@section('content')
<div class="container p-4">
    <div class="card p-3">
        <h2>تعديل سجل الصيانة</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('maintenances.update', $maintenance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="client_id">العميل</label>
                <select name="client_id" id="client_id" class="form-control select2" >
                    <option value="">اختر العميل</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ $client->id == $maintenance->client_id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="product_id">المنتج</label>
                <select name="product_id" id="product_id" class="form-control select2" >
                    <option value="">اختر المنتج</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $product->id == $maintenance->product_id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="maintenance_cost">تكلفة الصيانة</label>
                <input type="number" step="0.01" name="maintenance_cost" id="maintenance_cost" class="form-control" value="{{ $maintenance->maintenance_cost }}" required>
            </div>

            <div class="form-group">
                <label for="maintenance_date">تاريخ الصيانة</label>
                <input type="date" name="maintenance_date" id="maintenance_date" class="form-control" value="{{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label for="description">الوصف (اختياري)</label>
                <textarea name="description" id="description" class="form-control">{{ $maintenance->description }}</textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">تحديث السجل</button>
                <a href="{{ route('maintenances.index') }}" class="btn btn-secondary">إلغاء</a>
            </div>
        </form>
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
            // Initialize Select2 on both client and product select fields
            $('#client_id').select2({
                placeholder: "اختر العميل",
                allowClear: true
            });

            $('#product_id').select2({
                placeholder: "اختر المنتج",
                allowClear: true
            });
        });
    </script>
@endpush
