<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('permission_role', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('permission_user', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('permission_role', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('permission_user', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
