<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        try {
            // Disable foreign key checks
            Schema::disableForeignKeyConstraints();

            // Clear existing data
            DB::table('permission_role')->truncate();
            DB::table('permission_user')->truncate();
            DB::table('role_user')->truncate();
            DB::table('permissions')->truncate();
            DB::table('roles')->truncate();

            // Create Permissions
            $permissions = [
                // User Management
                ['name' => 'view-users', 'display_name' => 'View Users'],
                ['name' => 'create-user', 'display_name' => 'Create User'],
                ['name' => 'edit-user', 'display_name' => 'Edit User'],
                ['name' => 'delete-user', 'display_name' => 'Delete User'],

                // Role Management
                ['name' => 'view-roles', 'display_name' => 'View Roles'],
                ['name' => 'create-role', 'display_name' => 'Create Role'],
                ['name' => 'edit-role', 'display_name' => 'Edit Role'],
                ['name' => 'delete-role', 'display_name' => 'Delete Role'],

                // Product Management
                ['name' => 'view-products', 'display_name' => 'View Products'],
                ['name' => 'create-product', 'display_name' => 'Create Product'],
                ['name' => 'edit-product', 'display_name' => 'Edit Product'],
                ['name' => 'delete-product', 'display_name' => 'Delete Product'],

                // Category Management
                ['name' => 'view-categories', 'display_name' => 'View Categories'],
                ['name' => 'create-category', 'display_name' => 'Create Category'],
                ['name' => 'edit-category', 'display_name' => 'Edit Category'],
                ['name' => 'delete-category', 'display_name' => 'Delete Category'],

                // Inventory Management
                ['name' => 'view-inventory', 'display_name' => 'View Inventory'],
                ['name' => 'manage-inventory', 'display_name' => 'Manage Inventory'],
                ['name' => 'adjust-stock', 'display_name' => 'Adjust Stock'],

                // Order Management
                ['name' => 'view-orders', 'display_name' => 'View Orders'],
                ['name' => 'create-order', 'display_name' => 'Create Order'],
                ['name' => 'edit-order', 'display_name' => 'Edit Order'],
                ['name' => 'delete-order', 'display_name' => 'Delete Order'],
                ['name' => 'update-order-status', 'display_name' => 'Update Order Status'],
                ['name' => 'process-payment', 'display_name' => 'Process Payment'],

                // Customer Management
                ['name' => 'view-customers', 'display_name' => 'View Customers'],
                ['name' => 'create-customer', 'display_name' => 'Create Customer'],
                ['name' => 'edit-customer', 'display_name' => 'Edit Customer'],
                ['name' => 'delete-customer', 'display_name' => 'Delete Customer'],
                ['name' => 'manage-reseller', 'display_name' => 'Manage Reseller'],

                // Sales Management
                ['name' => 'view-sales', 'display_name' => 'View Sales'],
                ['name' => 'create-sale', 'display_name' => 'Create Sale'],
                ['name' => 'edit-sale', 'display_name' => 'Edit Sale'],
                ['name' => 'delete-sale', 'display_name' => 'Delete Sale'],

                // Bouquet Management
                ['name' => 'view-bouquets', 'display_name' => 'View Bouquets'],
                ['name' => 'create-bouquet', 'display_name' => 'Create Bouquet'],
                ['name' => 'edit-bouquet', 'display_name' => 'Edit Bouquet'],
                ['name' => 'delete-bouquet', 'display_name' => 'Delete Bouquet'],

                // Report Management
                ['name' => 'view-reports', 'display_name' => 'View Reports'],
                ['name' => 'view-sales-report', 'display_name' => 'View Sales Report'],
                ['name' => 'view-inventory-report', 'display_name' => 'View Inventory Report'],
                ['name' => 'view-customer-report', 'display_name' => 'View Customer Report'],
                ['name' => 'view-income-report', 'display_name' => 'View Income Report'],
                ['name' => 'generate-report', 'display_name' => 'Generate Report'],
                ['name' => 'export-report', 'display_name' => 'Export Report'],

                // Settings Management
                ['name' => 'manage-settings', 'display_name' => 'Manage Settings'],
                ['name' => 'view-dashboard', 'display_name' => 'View Dashboard'],

                // WhatsApp & Communication
                ['name' => 'send-whatsapp', 'display_name' => 'Send WhatsApp Message'],
                ['name' => 'view-notifications', 'display_name' => 'View Notifications'],
            ];

            // Insert permissions
            foreach ($permissions as $permission) {
                DB::table('permissions')->updateOrInsert(
                    ['name' => $permission['name']],
                    [
                        'display_name' => $permission['display_name'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            // Create Roles
            $roles = [
                [
                    'name' => 'owner',
                    'display_name' => 'Owner',
                    'description' => 'Pemilik toko bunga dengan akses penuh',
                    'permissions' => Permission::all()->pluck('name')->toArray() // All permissions
                ],
                [
                    'name' => 'admin',
                    'display_name' => 'Administrator',
                    'description' => 'Administrator toko dengan akses hampir penuh',
                    'permissions' => [
                        // User Management (terbatas)
                        'view-users',
                        'create-user',
                        'edit-user',

                        // Product & Category Management (penuh)
                        'view-products',
                        'create-product',
                        'edit-product',
                        'delete-product',
                        'view-categories',
                        'create-category',
                        'edit-category',
                        'delete-category',

                        // Inventory Management (penuh)
                        'view-inventory',
                        'manage-inventory',
                        'adjust-stock',

                        // Order Management (penuh)
                        'view-orders',
                        'create-order',
                        'edit-order',
                        'delete-order',
                        'update-order-status',
                        'process-payment',

                        // Customer Management (penuh)
                        'view-customers',
                        'create-customer',
                        'edit-customer',
                        'delete-customer',
                        'manage-reseller',

                        // Sales Management (penuh)
                        'view-sales',
                        'create-sale',
                        'edit-sale',
                        'delete-sale',

                        // Bouquet Management (penuh)
                        'view-bouquets',
                        'create-bouquet',
                        'edit-bouquet',
                        'delete-bouquet',

                        // Report Management (penuh)
                        'view-reports',
                        'view-sales-report',
                        'view-inventory-report',
                        'view-customer-report',
                        'view-income-report',
                        'generate-report',
                        'export-report',

                        // Settings & Communication
                        'manage-settings',
                        'view-dashboard',
                        'send-whatsapp',
                        'view-notifications'
                    ]
                ],
                [
                    'name' => 'kasir',
                    'display_name' => 'Kasir',
                    'description' => 'Petugas kasir untuk transaksi penjualan',
                    'permissions' => [
                        // Product (view only untuk transaksi)
                        'view-products',
                        'view-categories',

                        // Order Management (fokus transaksi)
                        'view-orders',
                        'create-order',
                        'edit-order',
                        'update-order-status',
                        'process-payment',

                        // Customer (minimal untuk transaksi)
                        'view-customers',

                        // Sales Management (penuh untuk tugas kasir)
                        'view-sales',
                        'create-sale',
                        'edit-sale',

                        // Report (terbatas untuk sales)
                        'view-reports',
                        'view-sales-report',

                        // Basic access
                        'view-dashboard',
                        'send-whatsapp',
                        'view-notifications'
                    ]
                ],
                [
                    'name' => 'karyawan',
                    'display_name' => 'Karyawan',
                    'description' => 'Staff operasional untuk pengelolaan produk dan inventaris',
                    'permissions' => [
                        // Product Management (edit untuk update stok/status)
                        'view-products',
                        'edit-product',
                        'view-categories',

                        // Inventory Management (penuh)
                        'view-inventory',
                        'manage-inventory',
                        'adjust-stock',

                        // Order (view untuk persiapan pesanan)
                        'view-orders',
                        'update-order-status',

                        // Bouquet Management (untuk persiapan)
                        'view-bouquets',
                        'edit-bouquet',

                        // Report (inventory only)
                        'view-reports',
                        'view-inventory-report',

                        // Basic access
                        'view-dashboard',
                        'view-notifications'
                    ]
                ],
                [
                    'name' => 'customers service',
                    'display_name' => 'Customer Service',
                    'description' => 'Petugas layanan pelanggan',
                    'permissions' => [
                        // Customer Management (penuh)
                        'view-customers',
                        'create-customer',
                        'edit-customer',
                        'manage-reseller',

                        // Order Management (untuk bantuan pelanggan)
                        'view-orders',
                        'create-order',
                        'edit-order',
                        'update-order-status',

                        // Product (view untuk bantuan pelanggan)
                        'view-products',
                        'view-categories',

                        // Report (customer focus)
                        'view-reports',
                        'view-customer-report',

                        // Communication
                        'send-whatsapp',
                        'view-notifications',

                        // Basic access
                        'view-dashboard'
                    ]
                ]
            ];

            // Insert roles
            foreach ($roles as $role) {
                DB::table('roles')->updateOrInsert(
                    ['name' => $role['name']],
                    [
                        'display_name' => $role['display_name'],
                        'description' => $role['description'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            // Assign permissions to roles
            foreach ($roles as $roleData) {
                $role = Role::where('name', $roleData['name'])->first();
                $permissions = Permission::whereIn('name', $roleData['permissions'])->get();

                foreach ($permissions as $permission) {
                    // Insert into pivot table without timestamps
                    DB::table('permission_role')->insertOrIgnore([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id
                    ]);
                }
            }

            // Create default owner user if not exists
            $owner = DB::table('users')->updateOrInsert(
                ['email' => 'owner@seikatbungo.com'],
                [
                    'name' => 'Owner',
                    'password' => bcrypt('owner123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Assign owner role to default user
            $ownerUser = DB::table('users')->where('email', 'owner@seikatbungo.com')->first();
            $ownerRole = Role::where('name', 'owner')->first();

            DB::table('role_user')->insertOrIgnore([
                'role_id' => $ownerRole->id,
                'user_id' => $ownerUser->id
            ]);

            // Re-enable foreign key checks
            Schema::enableForeignKeyConstraints();
        } catch (Exception $e) {
            // Re-enable foreign key checks even if error occurs
            Schema::enableForeignKeyConstraints();
            throw $e;
        }
    }
}
