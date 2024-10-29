@extends('layouts.admin')

@section('content')

<div class="container p-4">
    <div class="card p-3">
        <div class="d-flex justify-content-between mb-3">
            <h1>إدارة القرى</h1>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createVillageModal">إضافة قرية جديدة</button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Villages Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($villages as $village)
                    <tr>
                        <td>{{ $village->id }}</td>
                        <td>{{ $village->name }}</td>
                        <td class="d-flex justify-content-between">
                            <a href="{{ route('villages.edit', $village->id) }}" class="btn btn-warning">تعديل</a>
                            <form action="{{ route('villages.destroy', $village->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $villages->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Create Village Modal -->
<div class="modal fade" id="createVillageModal" tabindex="-1" role="dialog" aria-labelledby="createVillageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createVillageModalLabel">إضافة قرية جديدة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('villages.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">اسم القرية</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">إضافة القرية</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
