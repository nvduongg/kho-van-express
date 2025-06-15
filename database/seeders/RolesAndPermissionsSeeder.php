<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Tạo các quyền hạn
        // Module Product
        Permission::firstOrCreate(['name' => 'view_products']);
        Permission::firstOrCreate(['name' => 'create_products']);
        Permission::firstOrCreate(['name' => 'edit_products']);
        Permission::firstOrCreate(['name' => 'delete_products']);

        // Module Warehouse
        Permission::firstOrCreate(['name' => 'view_warehouses']);
        Permission::firstOrCreate(['name' => 'create_warehouses']);
        Permission::firstOrCreate(['name' => 'edit_warehouses']);
        Permission::firstOrCreate(['name' => 'delete_warehouses']);

        // Module Inventory
        Permission::firstOrCreate(['name' => 'view_inventory']);
        Permission::firstOrCreate(['name' => 'manage_inventory']); // Bao gồm add/remove stock

        // Module Customer
        Permission::firstOrCreate(['name' => 'view_customers']);
        Permission::firstOrCreate(['name' => 'create_customers']);
        Permission::firstOrCreate(['name' => 'edit_customers']);
        Permission::firstOrCreate(['name' => 'delete_customers']);

        // Module Order
        Permission::firstOrCreate(['name' => 'view_orders']);
        Permission::firstOrCreate(['name' => 'create_orders']);
        Permission::firstOrCreate(['name' => 'edit_orders']);
        Permission::firstOrCreate(['name' => 'cancel_orders']);
        Permission::firstOrCreate(['name' => 'complete_orders']);

        // Module Vehicle
        Permission::firstOrCreate(['name' => 'view_vehicles']);
        Permission::firstOrCreate(['name' => 'create_vehicles']);
        Permission::firstOrCreate(['name' => 'edit_vehicles']);
        Permission::firstOrCreate(['name' => 'delete_vehicles']);

        // Module Shipment
        Permission::firstOrCreate(['name' => 'view_shipments']);
        Permission::firstOrCreate(['name' => 'create_shipments']);
        Permission::firstOrCreate(['name' => 'edit_shipments']);
        Permission::firstOrCreate(['name' => 'delete_shipments']);

        // Module Report
        Permission::firstOrCreate(['name' => 'view_reports']);
        Permission::firstOrCreate(['name' => 'view_revenue_report']);
        Permission::firstOrCreate(['name' => 'view_inventory_report']);
        Permission::firstOrCreate(['name' => 'view_shipment_report']);

        // Quản lý người dùng (cho Admin)
        Permission::firstOrCreate(['name' => 'manage_users']);
        Permission::firstOrCreate(['name' => 'manage_roles']);
        Permission::firstOrCreate(['name' => 'manage_permissions']);


        // Tạo các vai trò và gán quyền hạn
        // Vai trò Admin (có tất cả các quyền)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all()); // Gán tất cả quyền cho admin

        // Vai trò Nhân viên Kho (Warehouse Staff)
        $warehouseStaffRole = Role::firstOrCreate(['name' => 'warehouse_staff']);
        $warehouseStaffRole->givePermissionTo([
            'view_products',
            'view_warehouses',
            'view_inventory',
            'manage_inventory',
            'view_shipments',
            'edit_shipments', // Có thể update trạng thái vận chuyển
        ]);

        // Vai trò Nhân viên Bán hàng (Sales Staff)
        $salesStaffRole = Role::firstOrCreate(['name' => 'sales_staff']);
        $salesStaffRole->givePermissionTo([
            'view_products',
            'view_customers',
            'create_customers',
            'edit_customers',
            'view_orders',
            'create_orders',
            'edit_orders',
            'cancel_orders',
            'view_revenue_report', // Có thể xem báo cáo doanh thu
        ]);

        // Vai trò Vận chuyển (Driver/Logistics Staff)
        $logisticsStaffRole = Role::firstOrCreate(['name' => 'logistics_staff']);
        $logisticsStaffRole->givePermissionTo([
            'view_shipments',
            'edit_shipments', // Cập nhật trạng thái giao hàng
            'view_vehicles',
            'view_shipment_report', // Có thể xem báo cáo vận chuyển
        ]);


        // Gán vai trò 'admin' cho một người dùng cụ thể (ví dụ: người dùng đầu tiên)
        // Đảm bảo có ít nhất một người dùng trong DB
        $user = User::first();
        if ($user) {
            $user->assignRole('admin');
        } else {
            // Nếu chưa có user nào, tạo một user admin mặc định
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'), // Đổi mật khẩu này trong production!
            ]);
            $adminUser->assignRole('admin');
        }
    }
}