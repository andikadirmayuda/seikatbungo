<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->primary(['user_id', 'role_id']);
            });
        }

        if (!Schema::hasTable('permission_user')) {
            Schema::create('permission_user', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('permission_id')->constrained()->onDelete('cascade');
                $table->primary(['user_id', 'permission_id']);
            });
        }

        if (!Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) {
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->foreignId('permission_id')->constrained()->onDelete('cascade');
                $table->primary(['role_id', 'permission_id']);
            });
        }

        // Insert default roles if not exists
        $roles = [
            [
                'name' => 'owner',
                'display_name' => 'Pemilik',
                'description' => 'Pemilik toko',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrator sistem',
            ],
            [
                'name' => 'kasir',
                'display_name' => 'Kasir',
                'description' => 'Kasir toko',
            ]
        ];

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

        // Insert default permissions if not exists
        $permissions = [
            [
                'name' => 'manage-settings',
                'display_name' => 'Kelola Pengaturan',
                'description' => 'Dapat mengelola pengaturan sistem',
            ]
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission['name']],
                [
                    'display_name' => $permission['display_name'],
                    'description' => $permission['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Assign manage-settings permission to owner and admin roles
        $ownerRole = DB::table('roles')->where('name', 'owner')->first();
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        $manageSettingsPermission = DB::table('permissions')->where('name', 'manage-settings')->first();

        if ($ownerRole && $manageSettingsPermission) {
            DB::table('permission_role')->updateOrInsert([
                'role_id' => $ownerRole->id,
                'permission_id' => $manageSettingsPermission->id,
            ]);
        }

        if ($adminRole && $manageSettingsPermission) {
            DB::table('permission_role')->updateOrInsert([
                'role_id' => $adminRole->id,
                'permission_id' => $manageSettingsPermission->id,
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('role_user');
    }
};
