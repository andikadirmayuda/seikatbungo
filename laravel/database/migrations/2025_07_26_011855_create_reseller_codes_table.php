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
        Schema::create('reseller_codes', function (Blueprint $table) {
            $table->id();
            $table->string('wa_number');
            $table->string('code')->unique();
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->unsignedBigInteger('used_for_order_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Add indexes
            $table->index('wa_number');
            $table->index('code');
            $table->index('is_used');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_codes');
    }
};
