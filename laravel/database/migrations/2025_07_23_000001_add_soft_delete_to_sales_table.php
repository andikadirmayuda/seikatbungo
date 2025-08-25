<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable()->after('updated_at');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
            $table->string('deletion_reason')->nullable()->after('deleted_by');
            
            // Index untuk performa query
            $table->index(['deleted_at']);
            
            // Foreign key untuk user yang menghapus
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropIndex(['deleted_at']);
            $table->dropColumn(['deleted_at', 'deleted_by', 'deletion_reason']);
        });
    }
};
