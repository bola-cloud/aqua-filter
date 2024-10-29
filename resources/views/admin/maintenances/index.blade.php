@extends('layouts.admin')

@section('content')
<style>
    a{
        text-decoration: none !important;
    }
</style>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between mb-3">
        <h1>إدارة الصيانة</h1>
        <a href="{{ route('maintenances.create') }}" class="btn btn-primary">إضافة سجل صيانة جديد</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-3">
            <!-- Search Bar -->
            <input type="text" id="searchInput" class="form-control" placeholder="بحث..." onkeyup="searchMaintenance()">
        </div>
    
        <div class="col-md-4 mb-3">
            <!-- Client Filter -->
            <select id="clientFilter" class="form-control" onchange="searchMaintenance()" style="width: 100%;">
                <option value="">اختر العميل</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
    
        <div class="col-md-4 mb-3">
            <!-- Maintenance Date Filter -->
            <input type="date" id="maintenanceDate" class="form-control" onchange="searchMaintenance()">
        </div>
    
    </div>
    <!-- Filter Section -->
    
    <div class="card p-3">
        <div class="row mb-3 d-flex justify-content-end">
            <a href="{{ route('maintenances.printTable') }}" target="_blank" class="btn btn-secondary">طباعة جدول الصيانة</a>
        </div>
        <!-- Maintenance Records Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>العميل</th>
                    <th>المنتج</th>
                    <th>تكلفة الصيانة</th>
                    <th>تاريخ الصيانة</th>
                    <th>الوصف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="maintenanceTableBody">
                @foreach($maintenances as $maintenance)
                    <tr>
                        <td>{{ $maintenance->id }}</td>
                        <td>{{ $maintenance->client ? $maintenance->client->name : 'لا يوجد عميل' }}</td>
                        <td>{{ $maintenance->product ? $maintenance->product->name : 'لا يوجد منتج' }}</td>
                        <td>{{ number_format($maintenance->maintenance_cost, 2) }} ج.م</td>
                        <td>{{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('Y-m-d') }}</td>
                        <td>{{ $maintenance->description ?? 'لا يوجد' }}</td>
                        <td>
                            <a href="{{ route('maintenances.edit', $maintenance->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                            <a href="{{ route('maintenances.print', $maintenance->id) }}" target="_blank" class="btn btn-info btn-sm">طباعة الفاتورة</a>
                            <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $maintenances->links('pagination::bootstrap-5') }}
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
function searchMaintenance() {
    let query = document.getElementById('searchInput').value;
    let clientId = document.getElementById('clientFilter').value;
    let maintenanceDate = document.getElementById('maintenanceDate').value;

    // Send an AJAX request to the server with the search query, client filter, and maintenance date
    fetch('{{ route('maintenances.search') }}?query=' + query + '&client_id=' + clientId + '&maintenance_date=' + maintenanceDate, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);  // Add this line to log the data

        // Ensure the data is an array
        if (Array.isArray(data)) {
            let tableBody = document.getElementById('maintenanceTableBody');
            tableBody.innerHTML = '';

            // Update the table with the filtered results
            data.forEach(maintenance => {
                let row = `
                    <tr>
                        <td>${maintenance.id}</td>
                        <td>${maintenance.client ? maintenance.client.name : 'لا يوجد عميل'}</td>
                        <td>${maintenance.product ? maintenance.product.name : 'لا يوجد منتج'}</td>
                        <td>${maintenance.maintenance_cost} ج.م</td>
                        <td>${maintenance.maintenance_date}</td>
                        <td>${maintenance.description ? maintenance.description : 'لا يوجد'}</td>
                        <td>
                            <a href="/maintenances/${maintenance.id}/edit" class="btn btn-warning btn-sm">تعديل</a>
                            <a href="/maintenances/${maintenance.id}/print" target="_blank" class="btn btn-info btn-sm">طباعة الفاتورة</a>
                            <form action="/maintenances/${maintenance.id}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        } else {
            console.error('Data is not an array:', data);
        }
    });
}

// Initialize Select2 for client filter
$(document).ready(function() {
    $('#clientFilter').select2();
});
</script>

@endpush