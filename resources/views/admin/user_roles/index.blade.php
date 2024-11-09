@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-start">
            <h1>الأدوار والصلاحيات للمستخدمين</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>الأدوار</th>
                        <th>الصلاحيات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    {{ $role->name }}
                                @endforeach
                            </td>
                            <td>
                                @foreach($user->permissions as $permission)
                                    {{ $permission->name }}
                                @endforeach
                            </td>
                            <td>
                                <!-- Display error message if available for this user -->
                                @if(session('errors') && session('errors')->has('error') && session('errors')->get('user_id') == $user->id)
                                    <div class="alert alert-danger">
                                        {{ session('errors')->first('error') }}
                                    </div>
                                @endif
                                
                                <!-- نموذج تعيين دور -->
                                <form action="{{ route('users.assignRole', $user->id) }}" method="POST">
                                    @csrf
                                    <select name="role_name" class="form-select">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">تعيين دور</button>
                                </form>
                                
                                <!-- نموذج إضافة صلاحية -->
                                <form action="{{ route('users.addPermission', $user->id) }}" method="POST">
                                    @csrf
                                    <select name="permission_name">
                                        @foreach($permissions as $permission)
                                            <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-secondary">إضافة صلاحية</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
