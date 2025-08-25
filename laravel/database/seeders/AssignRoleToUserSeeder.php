<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignRoleToUserSeeder extends Seeder
{
    public function run()
    {
        // Ambil user pertama (biasanya admin/owner)
        $user = DB::table('users')->first();
        
        if ($user) {
            // Ambil role owner
            $ownerRole = DB::table('roles')->where('name', 'owner')->first();
            
            if ($ownerRole) {
                // Berikan role owner ke user
                DB::table('role_user')->insert([
                    'user_id' => $user->id,
                    'role_id' => $ownerRole->id
                ]);
            }
        }
    }
}
