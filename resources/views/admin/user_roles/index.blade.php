@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>User Roles & Permissions</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Roles</th>
                <th>Permissions</th>
                <th>Actions</th>
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
                        <!-- Assign Role Form -->
                        <form action="{{ route('users.assignRole', $user->id) }}" method="POST">
                            @csrf
                            <select name="role_name">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Assign Role</button>
                        </form>
                        
                        <!-- Add Permission Form -->
                        <form action="{{ route('users.addPermission', $user->id) }}" method="POST">
                            @csrf
                            <select name="permission_name">
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-secondary">Add Permission</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
