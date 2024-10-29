@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>قائمة العملاء في إدارة الصيانة</h1>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم العميل</th>
                <th>الهاتف</th>
                <th>عدد سجلات الصيانة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->maintenances_count }}</td>
                    <td>
                        <a href="{{ route('clients.maintenance.statement', $client->id) }}" class="btn btn-info btn-sm">عرض كشف الصيانة</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $clients->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
