@extends('layouts.admin')

@section('content')

<div class="container p-4">
    <div class="card p-3">
        <h1>تعديل القرية</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('villages.update', $village->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">اسم القرية</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $village->name }}" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">تحديث القرية</button>
                <a href="{{ route('villages.index') }}" class="btn btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>

@endsection
