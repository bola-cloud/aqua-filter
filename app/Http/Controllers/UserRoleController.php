<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class UserRoleController extends Controller
{
    /**
     * Display a listing of users and their roles/permissions.
     */
    public function index()
    {
        $users = User::with('roles', 'permissions')->get();
        $roles = Role::all();
        $permissions = Permission::all();
        
        return view('admin.user_roles.index', compact('users', 'roles', 'permissions'));
    }

    /**
     * Assign a role to a user.
     */
    public function assignRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $role = Role::where('name', $request->role_name)->firstOrFail();

        $user->attachRole($role);
        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    /**
     * Remove a role from a user.
     */
    public function removeRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $role = Role::where('name', $request->role_name)->firstOrFail();

        $user->detachRole($role);
        return redirect()->back()->with('success', 'Role removed successfully.');
    }

    /**
     * Add specific permissions to a user.
     */
    public function addPermission(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $permission = Permission::where('name', $request->permission_name)->firstOrFail();

        $user->attachPermission($permission);
        return redirect()->back()->with('success', 'Permission added successfully.');
    }

    /**
     * Remove specific permissions from a user.
     */
    public function removePermission(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $permission = Permission::where('name', $request->permission_name)->firstOrFail();

        $user->detachPermission($permission);
        return redirect()->back()->with('success', 'Permission removed successfully.');
    }
}
