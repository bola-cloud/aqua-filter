<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Define permissions grouped by categories
        $permissions = [
            'dashboard' => ['عرض لوحة التحكم'],
            'categories' => ['عرض الفئات', 'إنشاء الفئات', 'تعديل الفئات'],
            'products' => ['عرض المنتجات', 'إنشاء المنتجات', 'تعديل المنتجات', 'عرض تقارير المنتجات'],
            'cashier' => ['عرض عربة التسوق', 'إدارة الفواتير', 'عرض المنتجات'],
            'sales_invoices' => ['عرض الفواتير', 'إنشاء الفواتير', 'عرض تقارير المبيعات'],
            'purchase_invoices' => ['عرض فواتير الشراء', 'إضافة فاتورة شراء', 'إدارة الموردين'],
            'reports' => ['عرض تقرير الأقساط اليومية', 'عرض تقارير المنتجات', 'عرض تقارير المبيعات', 'عرض التقارير الشهرية', 'عرض التقارير اليومية'],
            'permissions' => ['إدارة الصلاحيات'],
            'maintenance' => ['عرض فواتير الصيانة', 'عرض عملاء الصيانة'],
            'treasury' => ['عرض الخزينة'],
            'villages' => ['إدارة القري'],
        ];

        // Create each permission
        foreach ($permissions as $group => $permissionNames) {
            foreach ($permissionNames as $permissionName) {
                Permission::firstOrCreate(['name' => $permissionName]);
            }
        }

        // Define roles and assign relevant permissions
        $roles = [
            'admin' => array_merge(...array_values($permissions)), // Admin gets all permissions
            'cashier' => array_merge($permissions['cashier'], $permissions['sales_invoices']),
            'supervisor' => array_merge($permissions['dashboard'], $permissions['products'], $permissions['reports']),
        ];

        foreach ($roles as $roleKey => $permissionNames) {
            $role = Role::firstOrCreate(['name' => $roleKey]);
            $role->permissions()->sync(Permission::whereIn('name', $permissionNames)->pluck('id'));
        }

        // Create or update the admin user and assign all roles and permissions
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@goo.com'],
            ['name' => 'Admin User', 'password' => Hash::make('12345678')]
        );

        // Attach all roles with the user_type to the admin user
        $adminUser->roles()->sync(
            Role::all()->pluck('id')->mapWithKeys(function ($id) {
                return [$id => ['user_type' => 'App\Models\User']];
            })
        );

        // Attach all permissions with the user_type to the admin user
        $adminUser->permissions()->sync(
            Permission::all()->pluck('id')->mapWithKeys(function ($id) {
                return [$id => ['user_type' => 'App\Models\User']];
            })
        );

        echo "Admin user with all roles and permissions created successfully.";
    }
}
