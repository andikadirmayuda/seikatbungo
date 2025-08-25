<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'role_id']);
        });

        Schema::create('permission_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'permission_id']);
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });

        // Insert default roles
        DB::table('roles')->insert([
            [
                'name' => 'owner',
                'display_name' => 'Pemilik',
                'description' => 'Pemilik toko',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrator sistem',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'kasir',
                'display_name' => 'Kasir',
                'description' => 'Kasir toko',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Insert default permissions
        DB::table('permissions')->insert([
            [
                'name' => 'manage-settings',
                'display_name' => 'Kelola Pengaturan',
                'description' => 'Dapat mengelola semua pengaturan sistem',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'view-reports',
                'display_name' => 'Lihat Laporan',
                'description' => 'Dapat melihat laporan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Assign permissions to roles
        $ownerRole = DB::table('roles')->where('name', 'owner')->first();
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        
        $manageSettings = DB::table('permissions')->where('name', 'manage-settings')->first();
        $viewReports = DB::table('permissions')->where('name', 'view-reports')->first();

        // Owner gets all permissions
        DB::table('permission_role')->insert([
            ['role_id' => $ownerRole->id, 'permission_id' => $manageSettings->id],
            ['role_id' => $ownerRole->id, 'permission_id' => $viewReports->id],
        ]);

        // Admin gets view reports permission
        DB::table('permission_role')->insert([
            ['role_id' => $adminRole->id, 'permission_id' => $viewReports->id],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('role_user');
    }
};
