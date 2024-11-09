<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Exception;

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
        DB::beginTransaction();
    
        try {
            $user = User::findOrFail($userId);
            $role = Role::where('name', $request->role_name)->firstOrFail();
    
            $user->assignRole($role); // Use assignRole method from User model
            DB::commit();
            return redirect()->back()->with('success', 'Role assigned successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => "Failed to assign role: " . $e->getMessage(), 'user_id' => $userId]);
        }
    }

    /**
     * Remove a role from a user.
     */
    public function removeRole(Request $request, $userId)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($userId);
            $role = Role::where('name', $request->role_name)->firstOrFail();

            $user->detachRole($role);
            DB::commit();
            return redirect()->back()->with('success', 'Role removed successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => "Failed to remove role: " . $e->getMessage(), 'user_id' => $userId]);
        }
    }

    /**
     * Add specific permissions to a user.
     */
    public function addPermission(Request $request, $userId)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($userId);
            $permission = Permission::where('name', $request->permission_name)->firstOrFail();

            $user->attachPermission($permission);
            DB::commit();
            return redirect()->back()->with('success', 'Permission added successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => "Failed to add permission: " . $e->getMessage(), 'user_id' => $userId]);
        }
    }

    /**
     * Remove specific permissions from a user.
     */
    public function removePermission(Request $request, $userId)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($userId);
            $permission = Permission::where('name', $request->permission_name)->firstOrFail();

            $user->detachPermission($permission);
            DB::commit();
            return redirect()->back()->with('success', 'Permission removed successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => "Failed to remove permission: " . $e->getMessage(), 'user_id' => $userId]);
        }
    }
}
